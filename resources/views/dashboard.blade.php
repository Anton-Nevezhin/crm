@extends('layouts.app')

@section('title', 'Дашборд')

@section('content')
    <h1>Привет, {{ Auth::user()->name }}!</h1>
    <p>Вот что происходит в твоей CRM за сегодня.</p>

    <div class="stats-grid">
        <div class="stat-card">
            <h3>Всего клиентов</h3>
            <div class="number">{{ \App\Models\Client::count() }}</div>
        </div>
        <div class="stat-card">
            <h3>Активных сделок</h3>
            <div class="number">{{ \App\Models\Deal::where('status', 'in_progress')->count() }}</div>
        </div>
        <div class="stat-card">
            <h3>Контактов сегодня</h3>
            <div class="number">{{ \App\Models\Contact::whereDate('contact_date', now())->count() }}</div>
        </div>
        <div class="stat-card">
            <h3>Общая сумма сделок</h3>
            <div class="number">{{ number_format(\App\Models\Deal::sum('amount'), 0) }} ₽</div>
        </div>
    </div>

    <div style="margin-top: 30px;">
        <a href="{{ route('clients.index') }}" class="btn">Перейти к клиентам</a>
        <a href="{{ route('deals.index') }}" class="btn">Перейти к сделкам</a>
        <a href="{{ route('contacts.index') }}" class="btn">Перейти к контактам</a>
    </div>
@endsection