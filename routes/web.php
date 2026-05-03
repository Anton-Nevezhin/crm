<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminController;
use App\Models\Client;
use App\Models\Deal;

// Главная страница (без аутентификации)
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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// Маршруты для аутентификации (Breeze)
require __DIR__.'/auth.php';

// Защищённые маршруты (только после входа)
Route::middleware('auth')->group(function () {
    Route::resource('clients', ClientController::class);
    Route::resource('deals', DealController::class);
    Route::resource('contacts', ContactController::class);
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/reports/months', [DealController::class, 'monthlyReport'])->name('reports.months');
    Route::get('/clients/export/excel', [ClientController::class, 'exportExcel'])->name('clients.export.excel');
    Route::get('/clients/export/csv', [ClientController::class, 'exportCsv'])->name('clients.export.csv');
    Route::get('/clients/search', [ClientController::class, 'search'])->name('clients.search');
    Route::get('/clients/sort/{field}/{direction}', [ClientController::class, 'sort'])->name('clients.sort');
});