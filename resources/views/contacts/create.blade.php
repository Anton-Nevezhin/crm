<!DOCTYPE html>
<html>
<head>
    <title>Новый контакт</title>
    <meta charset="utf-8">
</head>
<body>
    <h1>Добавление нового контакта</h1>

    <form method="POST" action="{{ route('contacts.store') }}">
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
            <label>Тип контакта:</label><br>
            <select name="type" required>
                <option value="call">📞 Звонок</option>
                <option value="meeting">🤝 Встреча</option>
                <option value="email">📧 Письмо</option>
            </select>
        </div>

        <div>
            <label>Дата контакта:</label><br>
            <input type="date" name="contact_date" required>
        </div>

        <div>
            <label>Комментарий:</label><br>
            <textarea name="comment" rows="4" cols="50"></textarea>
        </div>

        <button type="submit">Сохранить</button>
        <a href="{{ route('contacts.index') }}">Отмена</a>
    </form>
</body>
</html>