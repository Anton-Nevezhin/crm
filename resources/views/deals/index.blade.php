@extends('layouts.app')

@section('title', 'Сделки')

@section('content')
    <h1>Список сделок</h1>

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
        <a href="{{ route('deals.create') }}" class="btn">➕ Добавить сделку</a>
        <a href="{{ route('deals.export.excel') }}" class="btn">📊 Экспорт в Excel</a>
    </div>

    <form method="GET" action="{{ route('deals.index') }}" class="filter-form">
        <input type="text" name="search" placeholder="Поиск по названию..." value="{{ request()->get('search') }}">
        
        <select name="per_page">
            <option value="10" {{ request()->get('per_page') == 10 ? 'selected' : '' }}>10</option>
            <option value="25" {{ request()->get('per_page') == 25 ? 'selected' : '' }}>25</option>
            <option value="50" {{ request()->get('per_page') == 50 ? 'selected' : '' }}>50</option>
            <option value="100" {{ request()->get('per_page') == 100 ? 'selected' : '' }}>100</option>
        </select>
        
        <select name="status">
            <option value="all">Все статусы</option>
            <option value="new">Новые</option>
            <option value="in_progress">В работе</option>
            <option value="closed">Закрытые</option>
            <option value="lost">Потерянные</option>
        </select>
        
        <input type="date" name="date_from" value="{{ request()->get('date_from') }}" placeholder="Дата от">
        <input type="date" name="date_to" value="{{ request()->get('date_to') }}" placeholder="Дата до">
        <input type="number" name="amount_from" placeholder="Сумма от" value="{{ request()->get('amount_from') }}">
        <input type="number" name="amount_to" placeholder="Сумма до" value="{{ request()->get('amount_to') }}">
        
        <button type="submit">Применить</button>
        <a href="{{ route('deals.index') }}" class="btn">Сбросить</a>
    </form>

    <table>
        <thead>
            <tr>
                <th style="width: 50px;"><a href="{{ route('deals.index', array_merge(request()->all(), ['sort_field' => 'id', 'sort_dir' => (request()->get('sort_field') == 'id' && request()->get('sort_dir') == 'asc') ? 'desc' : 'asc'])) }}">ID ↕</a></th>
                <th>Клиент</th>
                <th><a href="{{ route('deals.index', array_merge(request()->all(), ['sort_field' => 'name', 'sort_dir' => (request()->get('sort_field') == 'name' && request()->get('sort_dir') == 'asc') ? 'desc' : 'asc'])) }}">Название ↕</a></th>
                <th><a href="{{ route('deals.index', array_merge(request()->all(), ['sort_field' => 'amount', 'sort_dir' => (request()->get('sort_field') == 'amount' && request()->get('sort_dir') == 'asc') ? 'desc' : 'asc'])) }}">Сумма ↕</a></th>
                <th><a href="{{ route('deals.index', array_merge(request()->all(), ['sort_field' => 'status', 'sort_dir' => (request()->get('sort_field') == 'status' && request()->get('sort_dir') == 'asc') ? 'desc' : 'asc'])) }}">Статус ↕</a></th>
                <th><a href="{{ route('deals.index', array_merge(request()->all(), ['sort_field' => 'created_at', 'sort_dir' => (request()->get('sort_field') == 'created_at' && request()->get('sort_dir') == 'asc') ? 'desc' : 'asc'])) }}">Дата ↕</a></th>
                <th style="width: 140px;">Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($deals as $deal)
            <tr>
                <td>{{ $deal->id }}</td>
                <td>{{ $deal->client->name ?? '—' }}</td>
                <td>{{ $deal->name }}</td>
                <td>{{ number_format($deal->amount, 2) }} ₽</td>
                <td>{{ $deal->status_name }}</td>
                <td>{{ $deal->created_at->format('d.m.Y') }}</td>
                <td class="actions-cell">
                    <a href="{{ route('deals.show', $deal) }}">Просмотр</a>
                    <a href="{{ route('deals.edit', $deal) }}">Редактировать</a>
                    <button type="submit" form="delete-form-{{ $deal->id }}" class="btn-small">Удалить</button>
                    <form id="delete-form-{{ $deal->id }}" method="POST" action="{{ route('deals.destroy', $deal) }}" style="display: none;">
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

    @if ($deals->hasPages())
        <div class="pagination-custom">
            @if ($deals->onFirstPage())
                <span>[← Назад]</span>
            @else
                <a href="{{ $deals->previousPageUrl() }}&{{ $queryParams }}">[← Назад]</a>
            @endif

            @php
                $currentPage = $deals->currentPage();
                $lastPage = $deals->lastPage();
                $start = max(1, $currentPage - 2);
                $end = min($lastPage, $currentPage + 2);
            @endphp

            @if ($start > 1)
                <a href="{{ $deals->url(1) }}&{{ $queryParams }}">[1]</a>
                @if ($start > 2) <span>...</span> @endif
            @endif

            @for ($i = $start; $i <= $end; $i++)
                @if ($i == $currentPage)
                    <span><strong>[{{ $i }}]</strong></span>
                @else
                    <a href="{{ $deals->url($i) }}&{{ $queryParams }}">[{{ $i }}]</a>
                @endif
            @endfor

            @if ($end < $lastPage)
                @if ($end < $lastPage - 1) <span>...</span> @endif
                <a href="{{ $deals->url($lastPage) }}&{{ $queryParams }}">[{{ $lastPage }}]</a>
            @endif

            @if ($deals->hasMorePages())
                <a href="{{ $deals->nextPageUrl() }}&{{ $queryParams }}">[Вперёд →]</a>
            @else
                <span>[Вперёд →]</span>
            @endif
        </div>
    @endif

@endsection