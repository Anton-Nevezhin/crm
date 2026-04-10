<!DOCTYPE html>
<html>
<head>
    <title>Сделки</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <h1>Список сделок</h1>
    
    <a href="{{ route('deals.create') }}">Добавить сделку</a>
    <a href="{{ route('clients.index') }}">Назад к клиентам</a>
    
    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>ID</th>
                <th>Клиент</th>
                <th>Название</th>
                <th>Сумма</th>
                <th>Статус</th>
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