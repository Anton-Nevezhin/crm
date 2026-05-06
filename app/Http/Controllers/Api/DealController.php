<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deal;

class DealController extends Controller
{
    // GET /api/deals — список всех сделок
    public function index()
    {
        $deals = Deal::with('client')->get();
        return response()->json($deals);
    }

    // POST /api/deals — создание сделки
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:new,in_progress,closed,lost',
            'description' => 'nullable|string',
        ]);

        $deal = Deal::create($validated);
        return response()->json($deal, 201);
    }

    // GET /api/deals/{id} — просмотр сделки
    public function show($id)
    {
        $deal = Deal::with('client')->findOrFail($id);
        return response()->json($deal);
    }

    // PUT /api/deals/{id} — обновление сделки
    public function update(Request $request, $id)
    {
        $deal = Deal::findOrFail($id);

        $validated = $request->validate([
            'client_id' => 'sometimes|exists:clients,id',
            'name' => 'sometimes|string|max:255',
            'amount' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:new,in_progress,closed,lost',
            'description' => 'nullable|string',
        ]);

        $deal->update($validated);
        return response()->json($deal);
    }

    // DELETE /api/deals/{id} — удаление сделки
    public function destroy($id)
    {
        $deal = Deal::findOrFail($id);
        $deal->delete();
        return response()->json(null, 204);
    }
}
