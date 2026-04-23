<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DealController;
use App\Models\Deal;
use App\Models\Client;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    // Статистика по сделкам
    $totalDeals = Deal::count();
    $totalAmount = Deal::sum('amount');
    
    $statusCounts = [
        'new' => Deal::where('status', 'new')->count(),
        'in_progress' => Deal::where('status', 'in_progress')->count(),
        'closed' => Deal::where('status', 'closed')->count(),
        'lost' => Deal::where('status', 'lost')->count(),
    ];
    
    // Статистика по клиентам
    $totalClients = Client::count();
    $clientsWithDeals = Client::has('deals')->count();
    $clientsWithoutDeals = Client::doesntHave('deals')->count();
    $totalDealsSum = Client::withSum('deals', 'amount')->get()->sum('deals_sum_amount');
    
    // График: сделки по дням (последние 7 дней)
    $endDate = now();
    $startDate = now()->subDays(6);
    
    $dealsByDay = [];
    $currentDate = clone $startDate;
    
    while ($currentDate <= $endDate) {
        $dateStr = $currentDate->format('Y-m-d');
        $count = Deal::whereDate('created_at', $dateStr)->count();
        $dealsByDay[$dateStr] = $count;
        $currentDate->addDay();
    }
    
    $maxCount = max($dealsByDay);

    // Топ-5 клиентов по сумме сделок
    $topClients = Client::withCount('deals')
    ->withSum('deals', 'amount')
    ->having('deals_sum_amount', '>', 0)
    ->orderBy('deals_sum_amount', 'desc')
    ->limit(5)
    ->get();

    return view('welcome', compact(
        'totalDeals', 'totalAmount', 'statusCounts',
        'totalClients', 'clientsWithDeals', 'clientsWithoutDeals', 'totalDealsSum',
        'dealsByDay', 'maxCount', 'topClients'
    ));
});

// Клиенты
Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
Route::get('/clients/sort/{field}/{direction}', [ClientController::class, 'sort'])->name('clients.sort');
Route::get('/clients/search', [ClientController::class, 'search'])->name('clients.search');
Route::resource('clients', ClientController::class)->except(['index']);
Route::get('/clients/export/csv', [ClientController::class, 'exportCsv'])->name('clients.export.csv');
Route::get('/clients/export/excel', [ClientController::class, 'exportExcel'])->name('clients.export.excel');

// Сделки — один маршрут (всё в index)
Route::get('/deals', [DealController::class, 'index'])->name('deals.index');
Route::resource('deals', DealController::class)->except(['index']);