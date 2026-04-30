<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Client;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $clientId = $request->get('client_id');

        if ($clientId) {
            $query->where('client_id', $clientId);
        }
    
        $clients = Client::orderBy('name')->get();

        $query = Contact::with('client');
    
        if ($type) {
            $query->where('type', $type);
        }
    
        if ($dateFrom) {
            $query->whereDate('contact_date', '>=', $dateFrom);
        }
    
        if ($dateTo) {
            $query->whereDate('contact_date', '<=', $dateTo);
        }
    
        $contacts = $query->orderBy('contact_date', 'desc')->paginate(10);
    
        return view('contacts.index', compact('contacts', 'clients'));
    }

    public function create(Request $request)
    {
        $clients = Client::all();
        $selectedClient = $request->query('client_id');
        return view('contacts.create', compact('clients', 'selectedClient'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'type' => 'required|in:call,meeting,email',
            'contact_date' => 'required|date',
            'comment' => 'nullable|string',
        ]);
        
        Contact::create($validated);
        return redirect()->route('contacts.index')->with('success', 'Контакт добавлен');
    }

    public function show(Contact $contact)
    {
        return view('contacts.show', compact('contact'));
    }

    public function edit(Contact $contact)
    {
        $clients = Client::all();
        return view('contacts.edit', compact('contact', 'clients'));
    }

    public function update(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'type' => 'required|in:call,meeting,email',
            'contact_date' => 'required|date',
            'comment' => 'nullable|string',
        ]);
        
        $contact->update($validated);
        return redirect()->route('contacts.index')->with('success', 'Контакт обновлён');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect()->route('contacts.index')->with('success', 'Контакт удалён');
    }

    public function exportCsv()
    {
        $contacts = Contact::with('client')->get();
        
        $filename = 'contacts_' . date('Y-m-d_H-i-s') . '.csv';
        
        header('Content-Type: text/csv; charset=windows-1251');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $handle = fopen('php://output', 'w');
        
        // Заголовки (перекодируем в windows-1251)
        $headers = ['ID', 'Клиент', 'Тип', 'Дата контакта', 'Комментарий', 'Создан', 'Обновлён'];
        foreach ($headers as &$header) {
            $header = iconv('UTF-8', 'windows-1251//IGNORE', $header);
        }
        fputcsv($handle, $headers, ';'); // разделитель точка с запятой
        
        // Данные
        foreach ($contacts as $contact) {
            $typeText = match($contact->type) {
                'call' => 'Звонок',
                'meeting' => 'Встреча',
                'email' => 'Письмо',
                default => $contact->type,
            };
            
            $row = [
                $contact->id,
                iconv('UTF-8', 'windows-1251//IGNORE', $contact->client->name),
                iconv('UTF-8', 'windows-1251//IGNORE', $typeText),
                $contact->contact_date,
                iconv('UTF-8', 'windows-1251//IGNORE', $contact->comment ?? ''),
                $contact->created_at,
                $contact->updated_at,
            ];
            fputcsv($handle, $row, ';');
        }
        
        fclose($handle);
        exit;
    }
}