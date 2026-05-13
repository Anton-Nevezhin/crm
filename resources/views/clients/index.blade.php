@extends('layouts.app')

@section('title', 'Клиенты')

@section('content')
    <h1>Список клиентов</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="actions">
        <a href="{{ route('clients.create') }}" class="btn">Добавить клиента</a>
        <a href="{{ route('clients.export.excel') }}" class="btn">Экспорт в Excel</a>
        <a href="{{ route('clients.export.csv') }}" class="btn">Экспорт в CSV</a>
    </div>

    <form method="GET" action="{{ route('clients.search') }}" class="filter-form">
        <input type="text" name="search" placeholder="Поиск..." value="{{ request()->get('search') }}">
        <input type="date" name="date_from" value="{{ request()->get('date_from') }}">
        <input type="date" name="date_to" value="{{ request()->get('date_to') }}">
        <select name="per_page">
            <option value="10" {{ request()->get('per_page') == 10 ? 'selected' : '' }}>10</option>
            <option value="25" {{ request()->get('per_page') == 25 ? 'selected' : '' }}>25</option>
            <option value="50" {{ request()->get('per_page') == 50 ? 'selected' : '' }}>50</option>
            <option value="100" {{ request()->get('per_page') == 100 ? 'selected' : '' }}>100</option>
        </select>
        <input type="number" name="deals_sum_from" placeholder="Сумма от" value="{{ request()->get('deals_sum_from') }}">
        <input type="number" name="deals_sum_to" placeholder="Сумма до" value="{{ request()->get('deals_sum_to') }}">
        <button type="submit">Применить</button>
        <a href="{{ route('clients.index') }}" class="btn">Сбросить</a>
    </form>

<table>
    <thead>
        <tr>
            <th style="width: 50px;"><a href="{{ route('clients.sort', ['id', $direction == 'asc' && $field == 'id' ? 'desc' : 'asc']) . '?' . http_build_query(request()->only(['search', 'date_from', 'date_to'])) }}">ID ↕</a></th>
            <th><a href="{{ route('clients.sort', ['name', $direction == 'asc' && $field == 'name' ? 'desc' : 'asc']) . '?' . http_build_query(request()->only(['search', 'date_from', 'date_to'])) }}">Имя ↕</a></th>
            <th><a href="{{ route('clients.sort', ['email', $direction == 'asc' && $field == 'email' ? 'desc' : 'asc']) . '?' . http_build_query(request()->only(['search', 'date_from', 'date_to'])) }}">Email ↕</a></th>
            <th class="col-sum"><a href="{{ route('clients.sort', ['deals_sum_amount', $direction == 'asc' && $field == 'deals_sum_amount' ? 'desc' : 'asc']) . '?' . http_build_query(request()->only(['search', 'date_from', 'date_to'])) }}">Сумма ↕</a></th>
            <th class="col-contacts"><a href="{{ route('clients.sort', ['contacts_count', $direction == 'asc' && $field == 'contacts_count' ? 'desc' : 'asc']) . '?' . http_build_query(request()->only(['search', 'date_from', 'date_to'])) }}">Контакты ↕</a></th>
            <th style="width: 140px;">Действия</th>
        </tr>
    </thead>
    <tbody>
        @foreach($clients as $client)
        <tr>
            <td>{{ $client->id }}</td>
            <td>{{ $client->name }}</td>
            <td>{{ $client->email }}</td>
            <td>{{ number_format($client->deals_sum_amount ?? 0, 2) }} ₽</td>
            <td>{{ $client->contacts_count }}</td>
            <td class="actions-cell">
                <a href="{{ route('clients.show', $client) }}">Просмотр</a>
                <a href="{{ route('clients.edit', $client) }}">Редактировать</a>
                <button type="submit" form="delete-form-{{ $client->id }}" class="btn-small">Удалить</button>
                <form id="delete-form-{{ $client->id }}" method="POST" action="{{ route('clients.destroy', $client) }}" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@php
    $params = request()->except('page');
    $queryParams = !empty($params) ? '&' . http_build_query($params) : '';
@endphp

@if ($clients->hasPages())
    <div class="pagination-custom">
        @if ($clients->onFirstPage())
            <span>[← Назад]</span>
        @else
            <a href="{{ $clients->previousPageUrl() }}&{{ $queryParams }}">[← Назад]</a>
        @endif

        @php
            $currentPage = $clients->currentPage();
            $lastPage = $clients->lastPage();
            $start = max(1, $currentPage - 2);
            $end = min($lastPage, $currentPage + 2);
        @endphp

        @if ($start > 1)
            <a href="{{ $clients->url(1) }}&{{ $queryParams }}">[1]</a>
            @if ($start > 2) <span>...</span> @endif
        @endif

        @for ($i = $start; $i <= $end; $i++)
            @if ($i == $currentPage)
                <span><strong>[{{ $i }}]</strong></span>
            @else
                <a href="{{ $clients->url($i) }}&{{ $queryParams }}">[{{ $i }}]</a>
            @endif
        @endfor

        @if ($end < $lastPage)
            @if ($end < $lastPage - 1) <span>...</span> @endif
            <a href="{{ $clients->url($lastPage) }}&{{ $queryParams }}">[{{ $lastPage }}]</a>
        @endif

        @if ($clients->hasMorePages())
            <a href="{{ $clients->nextPageUrl() }}&{{ $queryParams }}">[Вперёд →]</a>
        @else
            <span>[Вперёд →]</span>
        @endif
    </div>
@endif

@endsection