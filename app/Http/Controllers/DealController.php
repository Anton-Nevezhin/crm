<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\Client;
use Illuminate\Http\Request;

class DealController extends Controller
{
    public function index()
    {
        $deals = Deal::with('client')->orderBy('id', 'asc')->paginate(10);
        $field = 'id';
        $direction = 'asc';
        return view('deals.index', compact('deals', 'field', 'direction'));
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
        return redirect()->route('deals.index');
    }

    public function filter(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        
        $query = Deal::with('client');
        
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }
        
        if ($status && $status != 'all') {
            $query->where('status', $status);
        }
        
        $deals = $query->paginate(10);
        
        return view('deals.index', compact('deals'));
    }

    public function sort($field, $direction)
    {
        $allowedFields = ['id', 'name', 'amount', 'status', 'created_at'];
        $allowedDirections = ['asc', 'desc'];
        
        if (!in_array($field, $allowedFields)) {
            $field = 'id';
        }
        
        if (!in_array($direction, $allowedDirections)) {
            $direction = 'asc';
        }
        
        $deals = Deal::with('client')
            ->orderBy($field, $direction)
            ->paginate(10);
        
        return view('deals.index', compact('deals', 'field', 'direction'));
    }
}