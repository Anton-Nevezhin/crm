<!DOCTYPE html>
<html>
<head>
    <title>Редактирование контакта</title>
    <meta charset="utf-8">
</head>
<body>
    <h1>Редактирование контакта</h1>

    <form method="POST" action="{{ route('contacts.update', $contact) }}">
        @csrf
        @method('PUT')

        <div>
            <label>Клиент:</label><br>
            <select name="client_id" required>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ $contact->client_id == $client->id ? 'selected' : '' }}>
                        {{ $client->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Тип контакта:</label><br>
            <select name="type" required>
                <option value="call" {{ $contact->type == 'call' ? 'selected' : '' }}>📞 Звонок</option>
                <option value="meeting" {{ $contact->type == 'meeting' ? 'selected' : '' }}>🤝 Встреча</option>
                <option value="email" {{ $contact->type == 'email' ? 'selected' : '' }}>📧 Письмо</option>
            </select>
        </div>

        <div>
            <label>Дата контакта:</label><br>
            <input type="date" name="contact_date" value="{{ $contact->contact_date }}" required>
        </div>

        <div>
            <label>Комментарий:</label><br>
            <textarea name="comment" rows="4" cols="50">{{ $contact->comment }}</textarea>
        </div>

        <button type="submit">Сохранить</button>
        <a href="{{ route('contacts.index') }}">Отмена</a>
    </form>
</body>
</html>