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
    <p>
        <a href="{{ route('clients.index') }}">Клиенты</a> |
        <a href="{{ route('deals.index') }}">Сделки</a>
    </p>
</body>
</html>