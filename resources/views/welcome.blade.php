<!DOCTYPE html>
<html>
<head>
    <title>CRM Статистика</title>
    <meta charset="utf-8">
</head>
<body>
    <h1>CRM Дашборд</h1>
    
    <h2>Статистика по сделкам</h2>
    <p><strong>Всего сделок:</strong> {{ $totalDeals }}</p>
    <p><strong>Общая сумма:</strong> {{ number_format($totalAmount, 2) }} ₽</p>
    
    <h2>По статусам</h2>
    <ul>
        <li>🆕 Новые: {{ $statusCounts['new'] }}</li>
        <li>⏳ В работе: {{ $statusCounts['in_progress'] }}</li>
        <li>✅ Закрытые: {{ $statusCounts['closed'] }}</li>
        <li>❌ Потерянные: {{ $statusCounts['lost'] }}</li>
    </ul>
    
    <h2>Статистика по клиентам</h2>
    <ul>
        <li>👥 Всего клиентов: {{ $totalClients }}</li>
        <li>📋 Клиентов со сделками: {{ $clientsWithDeals }}</li>
        <li>📭 Клиентов без сделок: {{ $clientsWithoutDeals }}</li>
        <li>💰 Общая сумма всех сделок по всем клиентам: {{ number_format($totalDealsSum, 2) }} ₽</li>
    </ul>

    <h2>Динамика сделок за последние 7 дней</h2>

    <div style="display: flex; align-items: flex-end; gap: 10px; margin-top: 20px; min-height: 200px;">
        @foreach($dealsByDay as $date => $count)
            @php
                $height = $maxCount > 0 ? ($count / $maxCount) * 150 : 0;
                $barColor = $count > 0 ? '#4CAF50' : '#ddd';
            @endphp
            <div style="text-align: center; flex: 1;">
                <div style="height: {{ $height }}px; width: 100%; background-color: {{ $barColor }}; border-radius: 4px 4px 0 0;"></div>
                <div style="font-size: 12px; margin-top: 5px;">
                    {{ \Carbon\Carbon::parse($date)->format('d.m') }}
                </div>
                <div style="font-size: 11px; color: #666;">{{ $count }}</div>
            </div>
        @endforeach
    </div>

    <h2>Топ-5 клиентов по сумме сделок</h2>

    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>Клиент</th>
                <th>Сумма сделок</th>
                <th>Количество сделок</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topClients as $client)
            <tr>
                <td><a href="{{ route('clients.show', $client) }}">{{ $client->name }}</a></td>
                <td>{{ number_format($client->deals_sum_amount, 2) }} ₽</td>
                <td>{{ $client->deals_count }}</td>
                <td><a href="{{ route('clients.show', $client) }}">Просмотр</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p>
        <a href="{{ route('clients.index') }}">Клиенты</a> |
        <a href="{{ route('deals.index') }}">Сделки</a>
        <a href="{{ route('reports.months') }}">📊 Отчёт по месяцам</a>
    </p>
</body>
</html>