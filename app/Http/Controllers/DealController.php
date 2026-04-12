<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\Client;
use Illuminate\Http\Request;

class DealController extends Controller
{
    public function index()
    {
        $deals = Deal::with('client')->get();
        return view('deals.index', compact('deals'));
    }

    public function create()
    {
        $clients = Client::all();
        return view('deals.create', compact('clients'));
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
