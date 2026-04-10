<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\Client;
use Illuminate\Http\Request;

class DealController extends Controller
{
    public function index()
    {
        $deals = Deal::with('client')->paginate(10);
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
        Deal::create($request->all());
        return redirect()->route('deals.index');
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
        $deal->update($request->all());
        return redirect()->route('deals.index');
    }

    public function destroy(Deal $deal)
    {
        $deal->delete();
        return redirect()->route('deals.index');
    }
}