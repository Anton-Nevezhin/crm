<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DealController;
use App\Models\Deal;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $totalDeals = Deal::count();
    $totalAmount = Deal::sum('amount');
    
    $statusCounts = [
        'new' => Deal::where('status', 'new')->count(),
        'in_progress' => Deal::where('status', 'in_progress')->count(),
        'closed' => Deal::where('status', 'closed')->count(),
        'lost' => Deal::where('status', 'lost')->count(),
    ];
    
    return view('welcome', compact('totalDeals', 'totalAmount', 'statusCounts'));
});

Route::resource('clients', ClientController::class);
Route::resource('deals', DealController::class);