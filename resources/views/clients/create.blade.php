<!DOCTYPE html>
<html>
<head>
    <title>Новый клиент</title>
    <meta charset="utf-8">
</head>
<body>
    <h1>Добавление нового клиента</h1>
    
    <form method="POST" action="{{ route('clients.store') }}">
        @csrf
        
        <div>
            <label>Имя:</label><br>
            <input type="text" name="name" required>
        </div>
        
        <div>
            <label>Email:</label><br>
            <input type="email" name="email" required>
        </div>
        
        <div>
            <label>Телефон:</label><br>
            <input type="text" name="phone">
        </div>
        
        <div>
            <label>Адрес:</label><br>
            <textarea name="address"></textarea>
        </div>
        
        <button type="submit">Создать</button>
        <a href="{{ route('clients.index') }}">Отмена</a>
    </form>
</body>
</html>
