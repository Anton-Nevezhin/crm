<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DealController;
use App\Models\Deal;
use App\Models\Client;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminController;

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

    // Сводка по сотрудникам (заглушка, потом будет привязано к пользователям)
    $managers = [
        ['name' => 'Менеджер 1', 'deals_count' => 12, 'deals_sum' => 1250000, 'contacts_count' => 34],
        ['name' => 'Менеджер 2', 'deals_count' => 8, 'deals_sum' => 870000, 'contacts_count' => 21],
        ['name' => 'Менеджер 3', 'deals_count' => 15, 'deals_sum' => 2100000, 'contacts_count' => 42],
    ];

    return view('welcome', compact(
        'totalDeals', 'totalAmount', 'statusCounts',
        'totalClients', 'clientsWithDeals', 'clientsWithoutDeals', 'totalDealsSum',
        'dealsByDay', 'maxCount', 'topClients', 'managers'
    ));
});

// Клиенты
Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
Route::get('/clients/sort/{field}/{direction}', [ClientController::class, 'sort'])->name('clients.sort');
Route::get('/clients/search', [ClientController::class, 'search'])->name('clients.search');
Route::resource('clients', ClientController::class)->except(['index']);
Route::get('/clients/export/csv', [ClientController::class, 'exportCsv'])->name('clients.export.csv');
Route::get('/clients/export/excel', [ClientController::class, 'exportExcel'])->name('clients.export.excel');
Route::resource('contacts', ContactController::class);
Route::get('/contacts/export/csv', [ContactController::class, 'exportCsv'])->name('contacts.export.csv');

// Сделки — один маршрут (всё в index)
Route::get('/deals', [DealController::class, 'index'])->name('deals.index');
Route::resource('deals', DealController::class)->except(['index']);

Route::get('/reports/months', [DealController::class, 'monthlyReport'])->name('reports.months');

Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');