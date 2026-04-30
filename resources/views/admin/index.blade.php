<!DOCTYPE html>
<html>
<head>
    <title>Панель управления</title>
    <meta charset="utf-8">
</head>
<body>
    <h1>Панель управления</h1>

    <p><a href="{{ route('clients.index') }}">Клиенты</a> | <a href="{{ route('deals.index') }}">Сделки</a> | <a href="{{ route('contacts.index') }}">Контакты</a></p>

    <h2>Общая статистика</h2>
    <ul>
        <li>👥 Всего клиентов: {{ $totalClients }}</li>
        <li>💼 Всего сделок: {{ $totalDeals }}</li>
        <li>📞 Всего контактов: {{ $totalContacts }}</li>
    </ul>

    <h2>Последние 5 клиентов</h2>
    <ul>
        @foreach($recentClients as $client)
            <li><a href="{{ route('clients.show', $client) }}">{{ $client->name }}</a> — {{ $client->created_at->diffForHumans() }}</li>
        @endforeach
    </ul>

    <h2>Последние 5 сделок</h2>
    <ul>
        @foreach($recentDeals as $deal)
            <li><a href="{{ route('deals.show', $deal) }}">{{ $deal->name }}</a> ({{ $deal->client->name }}) — {{ $deal->created_at->diffForHumans() }}</li>
        @endforeach
    </ul>

    <h2>Последние 5 контактов</h2>
    <ul>
        @foreach($recentContacts as $contact)
            <li>
                <a href="{{ route('contacts.show', $contact) }}">{{ $contact->type }}</a>
                ({{ $contact->client->name }}) — {{ $contact->contact_date }}
            </li>
        @endforeach
    </ul>
</body>
</html>