<!DOCTYPE html>
<html>
<head>
    <title>Клиенты</title>
    <meta charset="utf-8">
</head>
<body>
    <h1>Список клиентов</h1>

    <a href="{{ route('clients.export.excel') }}">📊 Экспорт в Excel</a>
    <a href="{{ route('clients.export.csv') }}" style="margin-left: 15px;">📥 Экспорт в CSV</a>

    <form method="GET" action="{{ route('clients.search') }}" style="margin-bottom: 20px;">
        <input type="text" name="search" placeholder="Поиск по имени, email или телефону..." value="{{ request()->get('search') }}" style="padding: 5px; width: 300px;">
        <button type="submit">Найти</button>
        <a href="{{ route('clients.index') }}">Сбросить</a>
    </form>
    
    <a href="{{ route('clients.create') }}">Добавить клиента</a>
    
    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Email</th>
                <th>Телефон</th>
                <th>Адрес</th>
                <th>Сделок</th>
                <th>Сумма сделок</th>
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
