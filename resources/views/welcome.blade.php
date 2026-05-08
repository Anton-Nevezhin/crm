<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM Дашборд</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <h1>Общая статистика</h1>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Всего сделок</h3>
                <div class="number">{{ $totalDeals }}</div>
            </div>
            <div class="stat-card">
                <h3>Общая сумма</h3>
                <div class="number">{{ number_format($totalAmount, 2) }} ₽</div>
            </div>
            <div class="stat-card">
                <h3>Всего клиентов</h3>
                <div class="number">{{ $totalClients }}</div>
            </div>
            <div class="stat-card">
                <h3>Клиентов со сделками</h3>
                <div class="number">{{ $clientsWithDeals }}</div>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Новые сделки</h3>
                <div class="number">{{ $statusCounts['new'] }}</div>
            </div>
            <div class="stat-card">
                <h3>В работе</h3>
                <div class="number">{{ $statusCounts['in_progress'] }}</div>
            </div>
            <div class="stat-card">
                <h3>Закрытые</h3>
                <div class="number">{{ $statusCounts['closed'] }}</div>
            </div>
            <div class="stat-card">
                <h3>Потерянные</h3>
                <div class="number">{{ $statusCounts['lost'] }}</div>
            </div>
        </div>

        <h2>Динамика сделок за последние дни</h2>
        <div class="chart-container">
            @foreach($dealsByDay as $date => $count)
                @php
                    $height = $maxCount > 0 ? ($count / $maxCount) * 150 : 0;
                    $barColor = $count > 0 ? '#4361ee' : '#e2e8f0';
                @endphp
                <div class="chart-bar">
                    <div class="bar" style="height: {{ $height }}px; background-color: {{ $barColor }};"></div>
                    <div class="bar-label">{{ \Carbon\Carbon::parse($date)->format('d.m') }}</div>
                    <div class="bar-value">{{ $count }}</div>
                </div>
            @endforeach
        </div>

        <h2>Топ-5 клиентов по сумме сделок</h2>
        <table class="top-table">
            <thead>
                <tr>
                    <th>Клиент</th>
                    <th>Сумма сделок</th>
                    <th>Количество сделок</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topClients as $client)
                <tr>
                    <td>{{ $client->name }}</td>
                    <td>{{ number_format($client->deals_sum_amount, 2) }} ₽</td>
                    <td>{{ $client->deals_count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <h2>Воронка продаж</h2>
        <table class="funnel-table">
            <thead>
                <tr>
                    <th>Статус</th>
                    <th>Количество</th>
                    <th>Процент</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = $totalDeals;
                    $statusNames = [
                        'new' => 'Новые',
                        'in_progress' => 'В работе',
                        'closed' => 'Закрытые',
                        'lost' => 'Потерянные',
                    ];
                @endphp
                @foreach($statusCounts as $status => $count)
                <tr>
                    <td>{{ $statusNames[$status] }}</td>
                    <td>{{ $count }}</td>
                    <td>{{ $total > 0 ? round(($count / $total) * 100, 1) : 0 }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer-links">
            <a href="{{ route('login') }}" class="btn">Войти в систему</a>
            <a href="{{ route('clients.index') }}" class="btn">Клиенты</a>
            <a href="{{ route('deals.index') }}" class="btn">Сделки</a>
            <a href="{{ route('reports.months') }}" class="btn">Отчёт по месяцам</a>
        </div>
    </div>
</body>
</html>