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

Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
Route::get('/clients/search', [ClientController::class, 'search'])->name('clients.search');
Route::resource('clients', ClientController::class)->except(['index']);
Route::get('/deals', [DealController::class, 'index'])->name('deals.index');
Route::get('/deals/filter', [DealController::class, 'filter'])->name('deals.filter');
Route::resource('deals', DealController::class)->except(['index']);
Route::get('/clients/export/csv', [ClientController::class, 'exportCsv'])->name('clients.export.csv');
Route::get('/clients/export/excel', [ClientController::class, 'exportExcel'])->name('clients.export.excel');