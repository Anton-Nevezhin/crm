<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Deal;
use App\Models\Contact;

class AdminController extends Controller
{
    public function index()
    {
        $totalClients = Client::count();
        $totalDeals = Deal::count();
        $totalContacts = Contact::count();
        
        $recentClients = Client::orderBy('created_at', 'desc')->limit(5)->get();
        $recentDeals = Deal::with('client')->orderBy('created_at', 'desc')->limit(5)->get();
        $recentContacts = Contact::with('client')->orderBy('created_at', 'desc')->limit(5)->get();
        
        return view('admin.index', compact('totalClients', 'totalDeals', 'totalContacts', 'recentClients', 'recentDeals', 'recentContacts'));
    }
}
