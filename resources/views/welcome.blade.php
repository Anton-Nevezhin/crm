<!DOCTYPE html>
<html>
<head>
    <title>CRM Статистика</title>
    <meta charset="utf-8">
</head>
<body>
    <h1>CRM Дашборд</h1>
    
    <p><strong>Всего сделок:</strong> {{ $totalDeals }}</p>
    <p><strong>Общая сумма:</strong> {{ number_format($totalAmount, 2) }} ₽</p>
    
    <h2>По статусам</h2>
    <ul>
        <li>🆕 Новые: {{ $statusCounts['new'] }}</li>
        <li>⏳ В работе: {{ $statusCounts['in_progress'] }}</li>
        <li>✅ Закрытые: {{ $statusCounts['closed'] }}</li>
        <li>❌ Потерянные: {{ $statusCounts['lost'] }}</li>
    </ul>
    
    <p>
        <a href="{{ route('clients.index') }}">Клиенты</a> |
        <a href="{{ route('deals.index') }}">Сделки</a>
    </p>
</body>
</html>