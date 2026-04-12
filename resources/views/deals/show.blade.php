<!DOCTYPE html>
<html>
<head>
    <title>Сделка {{ $deal->name }}</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <h1>Сделка: {{ $deal->name }}</h1>
    
    <p><strong>ID:</strong> {{ $deal->id }}</p>
    <p><strong>Клиент:</strong> {{ $deal->client->name ?? '—' }}</p>
    <p><strong>Название:</strong> {{ $deal->name }}</p>
    <p><strong>Сумма:</strong> {{ number_format($deal->amount, 2) }} ₽</p>
    <p><strong>Статус:</strong> 
        @if($deal->status == 'new') 🆕 Новая
        @elseif($deal->status == 'in_progress') ⏳ В работе
        @elseif($deal->status == 'closed') ✅ Закрыта
        @else ❌ Потеряна
        @endif
    </p>
    <p><strong>Описание:</strong> {{ $deal->description ?? '—' }}</p>
    <p><strong>Создан:</strong> {{ $deal->created_at }}</p>
    <p><strong>Обновлён:</strong> {{ $deal->updated_at }}</p>
    
    <a href="{{ route('deals.index') }}">Назад к списку</a>
    <a href="{{ route('deals.edit', $deal) }}">Редактировать</a>
</body>
</html>