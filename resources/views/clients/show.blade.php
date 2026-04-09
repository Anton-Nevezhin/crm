<!DOCTYPE html>
<html>
<head>
    <title>Клиент {{ $client->name }}</title>
    <meta charset="utf-8">
</head>
<body>
    <h1>Клиент: {{ $client->name }}</h1>
    
    <p><strong>ID:</strong> {{ $client->id }}</p>
    <p><strong>Email:</strong> {{ $client->email }}</p>
    <p><strong>Телефон:</strong> {{ $client->phone ?? 'не указан' }}</p>
    <p><strong>Адрес:</strong> {{ $client->address ?? 'не указан' }}</p>
    <p><strong>Создан:</strong> {{ $client->created_at }}</p>
    <p><strong>Обновлён:</strong> {{ $client->updated_at }}</p>
    
    <a href="{{ route('clients.index') }}">Назад к списку</a>
    <a href="{{ route('clients.edit', $client) }}">Редактировать</a>
</body>
</html>
