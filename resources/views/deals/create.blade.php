<!DOCTYPE html>
<html>
<head>
    <title>Новая сделка</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <h1>Добавление новой сделки</h1>
    
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

    <form method="POST" action="{{ route('deals.store') }}">
        @csrf
        
        <div>
            <label>Клиент:</label><br>
            <select name="client_id" required>
                <option value="">-- Выберите клиента --</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ $selectedClient == $client->id ? 'selected' : '' }}>
                        {{ $client->name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label>Название сделки:</label><br>
            <input type="text" name="name" required>
        </div>
        
        <div>
            <label>Сумма (₽):</label><br>
            <input type="number" name="amount" step="0.01" required>
        </div>
        
        <div>
            <label>Статус:</label><br>
            <select name="status">
                <option value="new">🆕 Новая</option>
                <option value="in_progress">⏳ В работе</option>
                <option value="closed">✅ Закрыта</option>
                <option value="lost">❌ Потеряна</option>
            </select>
        </div>
        
        <div>
            <label>Описание:</label><br>
            <textarea name="description" rows="4" cols="50"></textarea>
        </div>
        
        <button type="submit">Создать сделку</button>
        <a href="{{ route('deals.index') }}">Отмена</a>
    </form>
</body>
</html>