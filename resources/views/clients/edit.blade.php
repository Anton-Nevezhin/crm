<!DOCTYPE html>
<html>
<head>
    <title>Редактирование клиента</title>
    <meta charset="utf-8">
</head>
<body>
    <h1>Редактирование клиента: {{ $client->name }}</h1>
    
    @if(session('success'))
        <div style="color: green; padding: 10px; margin: 10px 0; border: 1px solid green;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="color: red; padding: 10px; margin: 10px 0; border: 1px solid red;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('clients.update', $client) }}">
        @csrf
        @method('PUT')
        
        <div>
            <label>Имя:</label><br>
            <input type="text" name="name" value="{{ $client->name }}" required>
        </div>
        
        <div>
            <label>Email:</label><br>
            <input type="email" name="email" value="{{ $client->email }}" required>
        </div>
        
        <div>
            <label>Телефон:</label><br>
            <input type="text" name="phone" value="{{ $client->phone }}">
        </div>
        
        <div>
            <label>Адрес:</label><br>
            <textarea name="address">{{ $client->address }}</textarea>
        </div>
        
        <button type="submit">Сохранить</button>
        <a href="{{ route('clients.index') }}">Отмена</a>
    </form>
</body>
</html>
