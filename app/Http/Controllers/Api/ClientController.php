<?php

namespace App\Http\Controllers\Api;

use App\Models\Client;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        return response()->json(Client::withCount('deals')->withSum('deals', 'amount')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);
        $client = Client::create($validated);
        return response()->json($client, 201);
    }

    public function show($id)
    {
        $client = Client::withCount('deals')->withSum('deals', 'amount')->findOrFail($id);
        return response()->json($client);
    }

    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:clients,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);
        $client->update($validated);
        return response()->json($client);
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();
        return response()->json(null, 204);
    }
}
