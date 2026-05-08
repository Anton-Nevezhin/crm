@extends('layouts.app')

@section('title', 'Админ-панель')

@section('content')
    <h1>Админ-панель</h1>
    <p>Общая сводка по CRM.</p>

    <div class="stats-grid">
        <div class="stat-card">
            <h3>Клиенты</h3>
            <div class="number">{{ $totalClients }}</div>
        </div>
        <div class="stat-card">
            <h3>Сделки</h3>
            <div class="number">{{ $totalDeals }}</div>
        </div>
        <div class="stat-card">
            <h3>Контакты</h3>
            <div class="number">{{ $totalContacts }}</div>
        </div>
    </div>

    <div style="display: flex; gap: 40px; flex-wrap: wrap; margin-top: 30px;">
        <div style="flex: 1;">
            <h2>Последние клиенты</h2>
            <ul class="recent-list">
                @foreach($recentClients as $client)
                    <li><a href="{{ route('clients.show', $client) }}">{{ $client->name }}</a> — {{ $client->created_at->diffForHumans() }}</li>
                @endforeach
            </ul>
        </div>
        <div style="flex: 1;">
            <h2>Последние сделки</h2>
            <ul class="recent-list">
                @foreach($recentDeals as $deal)
                    <li><a href="{{ route('deals.show', $deal) }}">{{ $deal->name }}</a> ({{ $deal->client->name }}) — {{ $deal->created_at->diffForHumans() }}</li>
                @endforeach
            </ul>
        </div>
        <div style="flex: 1;">
            <h2>Последние контакты</h2>
            <ul class="recent-list">
                @foreach($recentContacts as $contact)
                    <li><a href="{{ route('contacts.show', $contact) }}">{{ $contact->type }}</a> ({{ $contact->client->name }}) — {{ $contact->contact_date }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection