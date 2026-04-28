<!DOCTYPE html>
<html>
<head>
    <title>Контакт</title>
    <meta charset="utf-8">
</head>
<body>
    <h1>Просмотр контакта</h1>

    <p><strong>ID:</strong> {{ $contact->id }}</p>
    <p><strong>Клиент:</strong> <a href="{{ route('clients.show', $contact->client) }}">{{ $contact->client->name }}</a></p>
    <p><strong>Тип:</strong> 
        @if($contact->type == 'call') 📞 Звонок
        @elseif($contact->type == 'meeting') 🤝 Встреча
        @else 📧 Письмо
        @endif
    </p>
    <p><strong>Дата:</strong> {{ $contact->contact_date }}</p>
    <p><strong>Комментарий:</strong> {{ $contact->comment ?? '—' }}</p>
    <p><strong>Создан:</strong> {{ $contact->created_at }}</p>
    <p><strong>Обновлён:</strong> {{ $contact->updated_at }}</p>

    <a href="{{ route('contacts.index') }}">Назад к списку</a>
    <a href="{{ route('contacts.edit', $contact) }}">Редактировать</a>
</body>
</html>