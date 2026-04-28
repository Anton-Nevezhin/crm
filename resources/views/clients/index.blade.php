<!DOCTYPE html>
<html>
<head>
    <title>Клиенты</title>
    <meta charset="utf-8">
</head>
<body>
    <h1>Список клиентов</h1>

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

    <a href="{{ route('clients.export.excel') }}">📊 Экспорт в Excel</a>
    <a href="{{ route('clients.export.csv') }}" style="margin-left: 15px;">📥 Экспорт в CSV</a>

    <form method="GET" action="{{ route('clients.search') }}" style="margin-bottom: 20px;">
        <input type="text" name="search" placeholder="Поиск по имени, email или телефону..." value="{{ old('search', request()->get('search')) }}" style="padding: 5px; width: 300px;">
        
        <input type="date" name="date_from" value="{{ old('date_from', request()->get('date_from')) }}" placeholder="Дата от">
        <input type="date" name="date_to" value="{{ old('date_to', request()->get('date_to')) }}" placeholder="Дата до">
        
        <select name="per_page">
            <option value="10" {{ (old('per_page', request()->get('per_page')) == 10) ? 'selected' : '' }}>10</option>
            <option value="25" {{ (old('per_page', request()->get('per_page')) == 25) ? 'selected' : '' }}>25</option>
            <option value="50" {{ (old('per_page', request()->get('per_page')) == 50) ? 'selected' : '' }}>50</option>
            <option value="100" {{ (old('per_page', request()->get('per_page')) == 100) ? 'selected' : '' }}>100</option>
        </select>
        
        <input type="number" name="deals_sum_from" placeholder="Сумма сделок от" value="{{ old('deals_sum_from', request()->get('deals_sum_from')) }}">
        <input type="number" name="deals_sum_to" placeholder="Сумма сделок до" value="{{ old('deals_sum_to', request()->get('deals_sum_to')) }}">

        <button type="submit">Найти</button>
        <a href="{{ route('clients.index') }}">Сбросить</a>
        
        <input type="hidden" name="sort_field" value="{{ $field ?? 'id' }}">
        <input type="hidden" name="sort_dir" value="{{ $direction ?? 'asc' }}">
    </form>
    
    <a href="{{ route('clients.create') }}">Добавить клиента</a>
    
    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th><a href="{{ route('clients.sort', ['id', $direction == 'asc' && $field == 'id' ? 'desc' : 'asc']) . '?' . http_build_query(request()->only(['search', 'date_from', 'date_to'])) }}">ID ↕</a></th>
                <th><a href="{{ route('clients.sort', ['name', $direction == 'asc' && $field == 'name' ? 'desc' : 'asc']) . '?' . http_build_query(request()->only(['search', 'date_from', 'date_to'])) }}">Имя ↕</a></th>
                <th><a href="{{ route('clients.sort', ['email', $direction == 'asc' && $field == 'email' ? 'desc' : 'asc']) . '?' . http_build_query(request()->only(['search', 'date_from', 'date_to'])) }}">Email ↕</a></th>
                <th>Телефон</th>
                <th>Адрес</th>
                <th>Сделок</th>
                <th><a href="{{ route('clients.sort', ['deals_sum_amount', $direction == 'asc' && $field == 'deals_sum_amount' ? 'desc' : 'asc']) . '?' . http_build_query(request()->only(['search', 'date_from', 'date_to'])) }}">Сумма сделок ↕</a></th>
                <th><a href="{{ route('clients.sort', ['created_at', $direction == 'asc' && $field == 'created_at' ? 'desc' : 'asc']) . '?' . http_build_query(request()->only(['search', 'date_from', 'date_to'])) }}">Дата создания ↕</a></th>
                <th>Контакты</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clients as $client)
            <tr>
                <td>{{ $client->id }}</td>
                <td>{{ $client->name }}</td>
                <td>{{ $client->email }}</td>
                <td>{{ $client->phone ?? '—' }}</td>
                <td>{{ $client->address ?? '—' }}</td>
                <td>{{ $client->deals_count }}</td>
                <td>{{ number_format($client->deals_sum_amount ?? 0, 2) }} ₽</td>
                <td>{{ $client->created_at }}</td>
                <td>{{ $client->contacts_count }}</td>
                <td>
                    <a href="{{ route('clients.show', $client) }}">Просмотр</a>
                    <a href="{{ route('clients.edit', $client) }}">Редактировать</a>
                    <form method="POST" action="{{ route('clients.destroy', $client) }}" style="display:inline">
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
        @if ($clients->hasPages())
            <div>
                <!-- Назад -->
                @if ($clients->onFirstPage())
                    <span>[← Назад]</span>
                @else
                    <a href="{{ $clients->previousPageUrl() }}">[← Назад]</a>
                @endif
                
                <!-- Цифры -->
                @php
                    $currentPage = $clients->currentPage();
                    $lastPage = $clients->lastPage();
                    $start = max(1, $currentPage - 2);
                    $end = min($lastPage, $currentPage + 2);
                @endphp
                
                @if ($start > 1)
                    <a href="{{ $clients->url(1) }}">[1]</a>
                    @if ($start > 2)
                        <span>...</span>
                    @endif
                @endif
                
                @for ($i = $start; $i <= $end; $i++)
                    @if ($i == $currentPage)
                        <span><strong>[{{ $i }}]</strong></span>
                    @else
                        <a href="{{ $clients->url($i) }}">[{{ $i }}]</a>
                    @endif
                @endfor
                
                @if ($end < $lastPage)
                    @if ($end < $lastPage - 1)
                        <span>...</span>
                    @endif
                    <a href="{{ $clients->url($lastPage) }}">[{{ $lastPage }}]</a>
                @endif
                
                <!-- Вперёд -->
                @if ($clients->hasMorePages())
                    <a href="{{ $clients->nextPageUrl() }}">[Вперёд →]</a>
                @else
                    <span>[Вперёд →]</span>
                @endif
            </div>
        @endif
    </div>
</body>
</html>
