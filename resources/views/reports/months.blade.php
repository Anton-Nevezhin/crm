@extends('layouts.app')

@section('title', 'Отчёт по месяцам')

@section('content')
    <h1>Отчёт по месяцам (последние 12 месяцев)</h1>

    <div class="stats-grid">
        <div class="stat-card">
            <h3>Всего сделок</h3>
            <div class="number">{{ $reports->sum('total_count') }}</div>
        </div>
        <div class="stat-card">
            <h3>Общая сумма</h3>
            <div class="number">{{ number_format($reports->sum('total_amount'), 2) }} ₽</div>
        </div>
    </div>

    <div class="chart-container">
        @foreach($reports as $report)
            <div class="chart-bar">
                @php
                    $height = ($report->total_count / $maxCount) * 150;
                @endphp
                <div class="bar" style="height: {{ $height }}px; background-color: var(--accent);"></div>
                <div class="bar-label">{{ \Carbon\Carbon::parse($report->month . '-01')->format('M Y') }}</div>
                <div class="bar-value">{{ $report->total_count }}</div>
            </div>
        @endforeach
    </div>

    <table>
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
                <td>{{ \Carbon\Carbon::parse($report->month . '-01')->translatedFormat('F Y') }}</td>
                <td>{{ $report->total_count }}</td>
                <td>{{ number_format($report->total_amount, 2) }} ₽</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer-links">
        <a href="{{ route('dashboard') }}" class="btn">На главную</a>
        <a href="{{ route('deals.index') }}" class="btn">Сделки</a>
    </div>
@endsection