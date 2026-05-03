<!DOCTYPE html>
<html>
<head>
    <title>Дашборд</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <h1>Дашборд</h1>
    <p>Вы вошли в систему!</p>
    <p><a href="{{ route('clients.index') }}">Клиенты</a> | <a href="{{ route('deals.index') }}">Сделки</a> | <a href="{{ route('contacts.index') }}">Контакты</a> | <a href="{{ route('admin.index') }}">Админка</a></p>
    
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Выйти</button>
    </form>
</body>
</html>