<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\Client;
use Illuminate\Http\Request;

class DealController extends Controller
{
    public function index(Request $request)
    {
        // Получаем все параметры из запроса
        $search = $request->get('search');
        $status = $request->get('status');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $amountFrom = $request->get('amount_from');
        $amountTo = $request->get('amount_to');
        $sortField = $request->get('sort_field', 'id');
        $sortDir = $request->get('sort_dir', 'asc');
        $perPage = $request->get('per_page', 10);
        
        // Разрешённые значения для безопасности
        $allowedFields = ['id', 'name', 'amount', 'status', 'created_at'];
        $allowedDirs = ['asc', 'desc'];
        $allowedPerPage = [10, 25, 50, 100];
        
        // Валидация сортировки
        if (!in_array($sortField, $allowedFields)) {
            $sortField = 'id';
        }
        
        if (!in_array($sortDir, $allowedDirs)) {
            $sortDir = 'asc';
        }
        
        // Валидация количества записей на странице
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }
        
        // Создаём запрос
        $query = Deal::with('client');
        
        // Применяем фильтры
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }
        
        if ($status && $status != 'all') {
            $query->where('status', $status);
        }
        
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }
        
        if ($amountFrom) {
            $query->where('amount', '>=', $amountFrom);
        }
        
        if ($amountTo) {
            $query->where('amount', '<=', $amountTo);
        }
        
        // Сортировка и пагинация
        $deals = $query->orderBy($sortField, $sortDir)->paginate($perPage);
        
        return view('deals.index', compact('deals'));
    }

    public function create(Request $request)
    {
        $clients = Client::all();
        $selectedClient = $request->query('client_id');
        return view('deals.create', compact('clients', 'selectedClient'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:new,in_progress,closed,lost',
            'description' => 'nullable|string',
        ]);
        
        Deal::create($validated);
        return redirect()->route('deals.index')->with('success', 'Сделка создана');
    }

    public function show(Deal $deal)
    {
        return view('deals.show', compact('deal'));
    }

    public function edit(Deal $deal)
    {
        $clients = Client::all();
        return view('deals.edit', compact('deal', 'clients'));
    }

    public function update(Request $request, Deal $deal)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:new,in_progress,closed,lost',
            'description' => 'nullable|string',
        ]);
        
        $deal->update($validated);
        return redirect()->route('deals.index')->with('success', 'Сделка обновлена');
    }

    public function destroy(Deal $deal)
    {
        $deal->delete();
        return redirect()->route('deals.index')->with('success', 'Сделка удалена');
    }

    public function monthlyReport()
    {
        // Группируем сделки по месяцам (последние 12 месяцев)
        $reports = Deal::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month')
            ->selectRaw('count(*) as total_count')
            ->selectRaw('sum(amount) as total_amount')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();
        
        $maxCount = $reports->max('total_count') ?: 1;
        
        return view('reports.months', compact('reports', 'maxCount'));
    }
}