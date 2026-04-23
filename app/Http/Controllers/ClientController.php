<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Client;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $allowedPerPage = [10, 25, 50, 100];
        
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }
        
        $clients = Client::withCount('deals')
            ->withSum('deals', 'amount')
            ->orderBy('id', 'asc')
            ->paginate($perPage);
        
        $field = 'id';
        $direction = 'asc';
        
        return view('clients.index', compact('clients', 'field', 'direction'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {  
        return view('clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);
        
        Client::create($validated);
        return redirect()->route('clients.index')->with('success', 'Клиент создан');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $client->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);
        
        $client->update($validated);
        return redirect()->route('clients.index')->with('success', 'Клиент обновлён');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index');

    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $sortField = $request->get('sort_field', 'id');
        $sortDir = $request->get('sort_dir', 'asc');
        $perPage = $request->get('per_page', 10);
        
        $allowedFields = ['id', 'name', 'email', 'created_at', 'deals_sum_amount'];
        $allowedDirs = ['asc', 'desc'];
        $allowedPerPage = [10, 25, 50, 100];
        
        if (!in_array($sortField, $allowedFields)) {
            $sortField = 'id';
        }
        
        if (!in_array($sortDir, $allowedDirs)) {
            $sortDir = 'asc';
        }
        
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }
        
        $query = Client::withCount('deals')->withSum('deals', 'amount');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }
        
        $clients = $query->orderBy($sortField, $sortDir)->paginate($perPage);
        
        $field = $sortField;
        $direction = $sortDir;
        
        return view('clients.index', compact('clients', 'field', 'direction'));
    }
    
    public function sort($field, $direction)
    {
        $allowedFields = ['id', 'name', 'email', 'created_at', 'deals_sum_amount'];
        $allowedDirections = ['asc', 'desc'];

        $perPage = request()->get('per_page', 10);
        $allowedPerPage = [10, 25, 50, 100];
        
        if (!in_array($field, $allowedFields)) {
            $field = 'id';
        }
        
        if (!in_array($direction, $allowedDirections)) {
            $direction = 'asc';
        }

        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }
        
        // Получаем параметры фильтра из запроса
        $search = request()->get('search');
        $dateFrom = request()->get('date_from');
        $dateTo = request()->get('date_to');
        
        $query = Client::withCount('deals')->withSum('deals', 'amount');
        
        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
        }
        
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }
        
        $clients = $query->orderBy($field, $direction)->paginate($perPage);
        
        return view('clients.index', compact('clients', 'field', 'direction'));
    }

    public function exportCsv()
    {
        $clients = Client::withCount('deals')->withSum('deals', 'amount')->get();
        
        $filename = 'clients_' . date('Y-m-d_H-i-s') . '.csv';
        
        $handle = fopen('php://output', 'w');
        
        // Заголовки столбцов
        fputcsv($handle, ['ID', 'Имя', 'Email', 'Телефон', 'Адрес', 'Кол-во сделок', 'Сумма сделок', 'Создан', 'Обновлён']);
        
        foreach ($clients as $client) {
            fputcsv($handle, [
                $client->id,
                $client->name,
                $client->email,
                $client->phone,
                $client->address,
                $client->deals_count,
                $client->deals_sum_amount ?? 0,
                $client->created_at,
                $client->updated_at,
            ]);
        }
        
        fclose($handle);
        
        return response()->stream(
            function () use ($handle) {
                // уже вывели
            },
            200,
            [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]
        );
    }

    public function exportExcel()
    {
        $clients = Client::withCount('deals')->withSum('deals', 'amount')->get();
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Заголовки
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Имя');
        $sheet->setCellValue('C1', 'Email');
        $sheet->setCellValue('D1', 'Телефон');
        $sheet->setCellValue('E1', 'Адрес');
        $sheet->setCellValue('F1', 'Кол-во сделок');
        $sheet->setCellValue('G1', 'Сумма сделок');
        $sheet->setCellValue('H1', 'Создан');
        $sheet->setCellValue('I1', 'Обновлён');
        
        // Данные
        $row = 2;
        foreach ($clients as $client) {
            $sheet->setCellValue('A' . $row, $client->id);
            $sheet->setCellValue('B' . $row, $client->name);
            $sheet->setCellValue('C' . $row, $client->email);
            $sheet->setCellValue('D' . $row, $client->phone);
            $sheet->setCellValue('E' . $row, $client->address);
            $sheet->setCellValue('F' . $row, $client->deals_count);
            $sheet->setCellValue('G' . $row, $client->deals_sum_amount ?? 0);
            $sheet->setCellValue('H' . $row, $client->created_at);
            $sheet->setCellValue('I' . $row, $client->updated_at);
            $row++;
        }
        
        $filename = 'clients_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
