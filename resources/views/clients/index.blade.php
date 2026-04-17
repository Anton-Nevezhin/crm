<!DOCTYPE html>
<html>
<head>
    <title>Клиенты</title>
    <meta charset="utf-8">
</head>
<body>
    <h1>Список клиентов</h1>

    @if(session('success'))
        <div style="color: green; padding: 10px; margin: 10px 0; border: 1px solid green;">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('clients.export.excel') }}">📊 Экспорт в Excel</a>
    <a href="{{ route('clients.export.csv') }}" style="margin-left: 15px;">📥 Экспорт в CSV</a>

    <form method="GET" action="{{ route('clients.search') }}" style="margin-bottom: 20px;">
        <input type="text" name="search" placeholder="Поиск по имени, email или телефону..." value="{{ request()->get('search') }}" style="padding: 5px; width: 300px;">
        
        <input type="date" name="date_from" value="{{ request()->get('date_from') }}" placeholder="Дата от">
        <input type="date" name="date_to" value="{{ request()->get('date_to') }}" placeholder="Дата до">
        
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
        {{ $clients->links() }}
    </div>
</body>
</html>
