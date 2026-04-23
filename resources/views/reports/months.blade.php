<!DOCTYPE html>
<html>
<head>
    <title>Отчёт по месяцам</title>
    <meta charset="utf-8">
</head>
<body>
    <h1>Отчёт по месяцам (последние 12 месяцев)</h1>
    
    <p><a href="{{ route('deals.index') }}">← Назад к сделкам</a></p>
    
    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>Месяц</th>
                <th>Количество сделок</th>
                <th>Сумма</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
            <tr>
                <td>{{ \Carbon\Carbon::parse($report->month . '-01')->format('F Y') }}</td>
                <td>{{ $report->total_count }}</td>
                <td>{{ number_format($report->total_amount, 2) }} ₽</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <h2>График по месяцам</h2>
    
    <div style="display: flex; gap: 10px; margin-top: 20px; align-items: flex-end;">
        @foreach($reports as $report)
            @php
                $height = ($report->total_count / $maxCount) * 200;
            @endphp
            <div style="text-align: center; flex: 1;">
                <div style="height: {{ $height }}px; background-color: #4CAF50; width: 100%;"></div>
                <div>{{ \Carbon\Carbon::parse($report->month . '-01')->format('M') }}</div>
                <div>{{ $report->total_count }}</div>
            </div>
        @endforeach
    </div>
</body>
</html>