<!DOCTYPE html>
<html>
<head>
    <title>Сделки</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <h1>Список сделок</h1>

    @if(session('success'))
        <div style="color: green; padding: 10px; margin: 10px 0; border: 1px solid green;">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" action="{{ route('deals.filter') }}" style="margin-bottom: 20px;">
        <input type="text" name="search" placeholder="Поиск по названию..." value="{{ request()->get('search') }}" style="padding: 5px; width: 200px;">
        
        <select name="status" style="padding: 5px;">
            <option value="all">Все статусы</option>
            <option value="new" {{ request()->get('status') == 'new' ? 'selected' : '' }}>🆕 Новые</option>
            <option value="in_progress" {{ request()->get('status') == 'in_progress' ? 'selected' : '' }}>⏳ В работе</option>
            <option value="closed" {{ request()->get('status') == 'closed' ? 'selected' : '' }}>✅ Закрытые</option>
            <option value="lost" {{ request()->get('status') == 'lost' ? 'selected' : '' }}>❌ Потерянные</option>
        </select>
        
        <button type="submit">Применить</button>
        <a href="{{ route('deals.index') }}">Сбросить</a>
    </form>
    
    <a href="{{ route('deals.create') }}">Добавить сделку</a>
    <a href="{{ route('clients.index') }}">Назад к клиентам</a>
    
    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th><a href="{{ route('deals.sort', ['id', $direction == 'asc' && $field == 'id' ? 'desc' : 'asc']) }}">ID ↕</a></th>
                <th>Клиент</th>
                <th><a href="{{ route('deals.sort', ['name', $direction == 'asc' && $field == 'name' ? 'desc' : 'asc']) }}">Название ↕</a></th>
                <th><a href="{{ route('deals.sort', ['amount', $direction == 'asc' && $field == 'amount' ? 'desc' : 'asc']) }}">Сумма ↕</a></th>
                <th><a href="{{ route('deals.sort', ['status', $direction == 'asc' && $field == 'status' ? 'desc' : 'asc']) }}">Статус ↕</a></th>
                <th><a href="{{ route('deals.sort', ['created_at', $direction == 'asc' && $field == 'created_at' ? 'desc' : 'asc']) }}">Дата ↕</a></th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($deals as $deal)
            <tr>
                <td>{{ $deal->id }}</td>
                <td>{{ $deal->client->name ?? '—' }}</td>
                <td>{{ $deal->name }}</td>
                <td>{{ number_format($deal->amount, 2) }} ₽</td>
                <td>
                    @if($deal->status == 'new')
                        🆕 Новая
                    @elseif($deal->status == 'in_progress')
                        ⏳ В работе
                    @elseif($deal->status == 'closed')
                        ✅ Закрыта
                    @else
                        ❌ Потеряна
                    @endif
                </td>
                <td>{{ $deal->created_at }}</td>
                <td>
                    <a href="{{ route('deals.show', $deal) }}">Просмотр</a>
                    <a href="{{ route('deals.edit', $deal) }}">Редактировать</a>
                    <form method="POST" action="{{ route('deals.destroy', $deal) }}" style="display:inline">
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
        {{ $deals->links() }}
    </div>
</body>
</html>