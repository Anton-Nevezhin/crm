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

    <h2>Сделки клиента</h2>

    @if($client->deals->count() > 0)
        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Сумма</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($client->deals as $deal)
                <tr>
                    <td>{{ $deal->name }}</td>
                    <td>{{ number_format($deal->amount, 2) }} ₽</td>
                    <td>
                        @if($deal->status == 'new') 🆕 Новая
                        @elseif($deal->status == 'in_progress') ⏳ В работе
                        @elseif($deal->status == 'closed') ✅ Закрыта
                        @else ❌ Потеряна
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('deals.show', $deal) }}">Просмотр</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>У клиента нет сделок</p>
    @endif

    <a href="{{ route('deals.create', ['client_id' => $client->id]) }}">+ Добавить сделку</a>
    
    <a href="{{ route('clients.index') }}">Назад к списку</a>
    <a href="{{ route('clients.edit', $client) }}">Редактировать</a>
</body>
</html>
