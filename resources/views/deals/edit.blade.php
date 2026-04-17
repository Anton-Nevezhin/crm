<!DOCTYPE html>
<html>
<head>
    <title>Редактирование сделки</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <h1>Редактирование сделки: {{ $deal->name }}</h1>
    
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

    <form method="POST" action="{{ route('deals.update', $deal) }}">
        @csrf
        @method('PUT')
        
        <div>
            <label>Клиент:</label><br>
            <select name="client_id" required>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ $deal->client_id == $client->id ? 'selected' : '' }}>
                        {{ $client->name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label>Название сделки:</label><br>
            <input type="text" name="name" value="{{ $deal->name }}" required>
        </div>
        
        <div>
            <label>Сумма (₽):</label><br>
            <input type="number" name="amount" step="0.01" value="{{ $deal->amount }}" required>
        </div>
        
        <div>
            <label>Статус:</label><br>
            <select name="status">
                <option value="new" {{ $deal->status == 'new' ? 'selected' : '' }}>🆕 Новая</option>
                <option value="in_progress" {{ $deal->status == 'in_progress' ? 'selected' : '' }}>⏳ В работе</option>
                <option value="closed" {{ $deal->status == 'closed' ? 'selected' : '' }}>✅ Закрыта</option>
                <option value="lost" {{ $deal->status == 'lost' ? 'selected' : '' }}>❌ Потеряна</option>
            </select>
        </div>
        
        <div>
            <label>Описание:</label><br>
            <textarea name="description" rows="4" cols="50">{{ $deal->description }}</textarea>
        </div>
        
        <button type="submit">Сохранить изменения</button>
        <a href="{{ route('deals.index') }}">Отмена</a>
    </form>
</body>
</html>