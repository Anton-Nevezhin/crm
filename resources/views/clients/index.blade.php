<!DOCTYPE html>
<html>
<head>
    <title>Клиенты</title>
    <meta charset="utf-8">
</head>
<body>
    <h1>Список клиентов</h1>
    
    <a href="{{ route('clients.create') }}">Добавить клиента</a>
    
    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Email</th>
                <th>Телефон</th>
                <th>Адрес</th>
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
</body>
</html>
