<!DOCTYPE html>
<html>
<head>
    <title>Сделки</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <h1>Список сделок</h1>

    @if($errors->any())
        <div style="color: red; border: 1px solid red; padding: 10px; margin: 10px 0;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div style="color: green; padding: 10px; margin: 10px 0; border: 1px solid green;">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" action="{{ route('deals.index') }}" style="margin-bottom: 20px;">
        <input type="text" name="search" placeholder="Поиск по названию..." value="{{ old('search', request()->get('search')) }}">

        <select name="per_page">
            <option value="10" {{ old('per_page', request()->get('per_page')) == 10 ? 'selected' : '' }}>10</option>
            <option value="25" {{ old('per_page', request()->get('per_page')) == 25 ? 'selected' : '' }}>25</option>
            <option value="50" {{ old('per_page', request()->get('per_page')) == 50 ? 'selected' : '' }}>50</option>
            <option value="100" {{ old('per_page', request()->get('per_page')) == 100 ? 'selected' : '' }}>100</option>
        </select>
        
        <select name="status">
            <option value="all" {{ (old('status', request()->get('status')) == 'all') ? 'selected' : '' }}>Все статусы</option>
            <option value="new" {{ (old('status', request()->get('status')) == 'new') ? 'selected' : '' }}>🆕 Новые</option>
            <option value="in_progress" {{ (old('status', request()->get('status')) == 'in_progress') ? 'selected' : '' }}>⏳ В работе</option>
            <option value="closed" {{ (old('status', request()->get('status')) == 'closed') ? 'selected' : '' }}>✅ Закрытые</option>
            <option value="lost" {{ (old('status', request()->get('status')) == 'lost') ? 'selected' : '' }}>❌ Потерянные</option>
        </select>

        <input type="date" name="date_from" value="{{ old('date_from', request()->get('date_from')) }}" placeholder="Дата от">
        <input type="date" name="date_to" value="{{ old('date_to', request()->get('date_to')) }}" placeholder="Дата до">

        <input type="number" name="amount_from" placeholder="Сумма от" value="{{ old('amount_from', request()->get('amount_from')) }}">
        <input type="number" name="amount_to" placeholder="Сумма до" value="{{ old('amount_to', request()->get('amount_to')) }}">
        
        <input type="hidden" name="sort_field" value="{{ request()->get('sort_field', 'id') }}">
        <input type="hidden" name="sort_dir" value="{{ request()->get('sort_dir', 'asc') }}">
        
        <button type="submit">Применить</button>
        <a href="{{ route('deals.index') }}">Сбросить</a>
    </form>
    
    <a href="{{ route('deals.create') }}">Добавить сделку</a>
    <a href="{{ route('clients.index') }}">Назад к клиентам</a>
    
    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th><a href="{{ route('deals.index', array_merge(request()->all(), ['sort_field' => 'id', 'sort_dir' => (request()->get('sort_field') == 'id' && request()->get('sort_dir') == 'asc') ? 'desc' : 'asc'])) }}">ID ↕</a></th>
                <th>Клиент</th>
                <th><a href="{{ route('deals.index', array_merge(request()->all(), ['sort_field' => 'name', 'sort_dir' => (request()->get('sort_field') == 'name' && request()->get('sort_dir') == 'asc') ? 'desc' : 'asc'])) }}">Название ↕</a></th>
                <th><a href="{{ route('deals.index', array_merge(request()->all(), ['sort_field' => 'amount', 'sort_dir' => (request()->get('sort_field') == 'amount' && request()->get('sort_dir') == 'asc') ? 'desc' : 'asc'])) }}">Сумма ↕</a></th>
                <th><a href="{{ route('deals.index', array_merge(request()->all(), ['sort_field' => 'status', 'sort_dir' => (request()->get('sort_field') == 'status' && request()->get('sort_dir') == 'asc') ? 'desc' : 'asc'])) }}">Статус ↕</a></th>
                <th><a href="{{ route('deals.index', array_merge(request()->all(), ['sort_field' => 'created_at', 'sort_dir' => (request()->get('sort_field') == 'created_at' && request()->get('sort_dir') == 'asc') ? 'desc' : 'asc'])) }}">Дата ↕</a></th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($deals as $deal)
            <tr>
                <td>{{ $deal->id }}</td>
                <td>{{ $deal->client->name ?? '—' }}</td>
                <td>{{ $deal->name }}</td>
                <td>{{ number_format($deal->amount, 2) }} ₽</td>
                <td>
                    @if($deal->status == 'new')
                        🆕 Новая
                    @elseif($deal->status == 'in_progress')
                        ⏳ В работе
                    @elseif($deal->status == 'closed')
                        ✅ Закрыта
                    @else
                        ❌ Потеряна
                    @endif
                </td>
                <td>{{ $deal->created_at }}</td>
                <td>
                    <a href="{{ route('deals.show', $deal) }}">Просмотр</a>
                    <a href="{{ route('deals.edit', $deal) }}">Редактировать</a>
                    <form method="POST" action="{{ route('deals.destroy', $deal) }}" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Точно удалить?')">Удалить</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        @if ($deals->hasPages())
            <div>
                <!-- Назад -->
                @if ($deals->onFirstPage())
                    <span>[← Назад]</span>
                @else
                    <a href="{{ $deals->previousPageUrl() }}">[← Назад]</a>
                @endif
                
                <!-- Цифры -->
                @php
                    $currentPage = $deals->currentPage();
                    $lastPage = $deals->lastPage();
                    $start = max(1, $currentPage - 2);
                    $end = min($lastPage, $currentPage + 2);
                @endphp
                
                @if ($start > 1)
                    <a href="{{ $deals->url(1) }}">[1]</a>
                    @if ($start > 2)
                        <span>...</span>
                    @endif
                @endif
                
                @for ($i = $start; $i <= $end; $i++)
                    @if ($i == $currentPage)
                        <span><strong>[{{ $i }}]</strong></span>
                    @else
                        <a href="{{ $deals->url($i) }}">[{{ $i }}]</a>
                    @endif
                @endfor
                
                @if ($end < $lastPage)
                    @if ($end < $lastPage - 1)
                        <span>...</span>
                    @endif
                    <a href="{{ $deals->url($lastPage) }}">[{{ $lastPage }}]</a>
                @endif
                
                <!-- Вперёд -->
                @if ($deals->hasMorePages())
                    <a href="{{ $deals->nextPageUrl() }}">[Вперёд →]</a>
                @else
                    <span>[Вперёд →]</span>
                @endif
            </div>
        @endif
    </div>
</body>
</html>