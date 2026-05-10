@extends('layouts.app')

@section('title', 'Сделка ' . $deal->name)

@section('content')
    <h1>Сделка: {{ $deal->name }}</h1>

    <div class="card">
        <p><strong>ID:</strong> {{ $deal->id }}</p>
        <p><strong>Клиент:</strong> {{ $deal->client->name }}</p>
        <p><strong>Название:</strong> {{ $deal->name }}</p>
        <p><strong>Сумма:</strong> {{ number_format($deal->amount, 2) }} ₽</p>
        <p><strong>Статус:</strong> 
            @if($deal->status == 'new') Новая
            @elseif($deal->status == 'in_progress') В работе
            @elseif($deal->status == 'closed') Закрыта
            @else Потеряна
            @endif
        </p>
        <p><strong>Описание:</strong> {{ $deal->description ?? '—' }}</p>
        <p><strong>Создан:</strong> {{ $deal->created_at }}</p>
        <p><strong>Обновлён:</strong> {{ $deal->updated_at }}</p>
    </div>

    <div class="form-actions" style="margin-top: 30px;">
        <a href="{{ route('deals.edit', $deal) }}" class="btn btn-primary">Редактировать</a>
        <a href="{{ route('deals.index') }}" class="btn btn-secondary">Назад к списку</a>
    </div>
@endsection