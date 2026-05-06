<!DOCTYPE html>
<html>
<head>
    <title>Контакты</title>
    <meta charset="utf-8">
</head>
<body>
    <h1>Список контактов</h1>

    <form method="GET" action="{{ route('contacts.index') }}" style="margin-bottom: 20px;">
        <select name="type">
            <option value="">Все типы</option>
            <option value="call" {{ request()->get('type') == 'call' ? 'selected' : '' }}>📞 Звонок</option>
            <option value="meeting" {{ request()->get('type') == 'meeting' ? 'selected' : '' }}>🤝 Встреча</option>
            <option value="email" {{ request()->get('type') == 'email' ? 'selected' : '' }}>📧 Письмо</option>
        </select>

        <input type="date" name="date_from" value="{{ request()->get('date_from') }}" placeholder="Дата от">
        <input type="date" name="date_to" value="{{ request()->get('date_to') }}" placeholder="Дата до">

        <select name="client_id">
            <option value="">Все клиенты</option>
            @foreach($clients as $client)
                <option value="{{ $client->id }}" {{ request()->get('client_id') == $client->id ? 'selected' : '' }}>
                    {{ $client->name }}
                </option>
            @endforeach
        </select>

        <button type="submit">Применить</button>
        <a href="{{ route('contacts.index') }}">Сбросить</a>
    </form>

    @if(session('success'))
        <div style="color: green; padding: 10px; margin: 10px 0; border: 1px solid green;">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('contacts.create') }}">Добавить контакт</a>
    <a href="{{ route('clients.index') }}">Назад к клиентам</a>

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>ID</th>
                <th>Клиент</th>
                <th>Тип</th>
                <th>Дата</th>
                <th>Комментарий</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contacts as $contact)
            <tr>
                <td>{{ $contact->id }}</td>
                <td><a href="{{ route('clients.show', $contact->client) }}">{{ $contact->client->name }}</a></td>
                <td>
                    @if($contact->type == 'call') 📞 Звонок
                    @elseif($contact->type == 'meeting') 🤝 Встреча
                    @else 📧 Письмо
                    @endif
                </td>
                <td>{{ $contact->contact_date }}</td>
                <td>{{ $contact->comment ?? '—' }}</td>
                <td>
                    <a href="{{ route('contacts.show', $contact) }}">Просмотр</a>
                    <a href="{{ route('contacts.edit', $contact) }}">Редактировать</a>
                    <form method="POST" action="{{ route('contacts.destroy', $contact) }}" style="display:inline">
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
        @php
            $params = request()->except('page');
            $queryParams = !empty($params) ? '&' . http_build_query($params) : '';
        @endphp

        @if ($contacts->hasPages())
            <div>
                @if ($contacts->onFirstPage())
                    <span>[← Назад]</span>
                @else
                    <a href="{{ $contacts->previousPageUrl() }}{{ $queryParams }}">[← Назад]</a>
                @endif

                @php
                    $currentPage = $contacts->currentPage();
                    $lastPage = $contacts->lastPage();
                    $start = max(1, $currentPage - 2);
                    $end = min($lastPage, $currentPage + 2);
                @endphp

                @if ($start > 1)
                    <a href="{{ $contacts->url(1) }}{{ $queryParams }}">[1]</a>
                    @if ($start > 2) <span>...</span> @endif
                @endif

                @for ($i = $start; $i <= $end; $i++)
                    @if ($i == $currentPage)
                        <span><strong>[{{ $i }}]</strong></span>
                    @else
                        <a href="{{ $contacts->url($i) }}{{ $queryParams }}">[{{ $i }}]</a>
                    @endif
                @endfor

                @if ($end < $lastPage)
                    @if ($end < $lastPage - 1) <span>...</span> @endif
                    <a href="{{ $contacts->url($lastPage) }}{{ $queryParams }}">[{{ $lastPage }}]</a>
                @endif

                @if ($contacts->hasMorePages())
                    <a href="{{ $contacts->nextPageUrl() }}{{ $queryParams }}">[Вперёд →]</a>
                @else
                    <span>[Вперёд →]</span>
                @endif
            </div>
        @endif
    </div>
</body>
</html>