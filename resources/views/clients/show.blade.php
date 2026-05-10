@extends('layouts.app')

@section('title', 'Клиент ' . $client->name)

@section('content')
    <h1>Клиент: {{ $client->name }}</h1>

    <div class="card">
        <p><strong>ID:</strong> {{ $client->id }}</p>
        <p><strong>Email:</strong> {{ $client->email }}</p>
        <p><strong>Телефон:</strong> {{ $client->phone ?? 'не указан' }}</p>
        <p><strong>Адрес:</strong> {{ $client->address ?? 'не указан' }}</p>
        <p><strong>Создан:</strong> {{ $client->created_at }}</p>
        <p><strong>Обновлён:</strong> {{ $client->updated_at }}</p>
    </div>

    <!-- Сделки клиента -->
    <h2>Сделки клиента</h2>

    @if($client->deals->count() > 0)
        <table class="data-table">
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
                    <td>{{ $deal->status_name }}</td>
                    <td><a href="{{ route('deals.show', $deal) }}">Просмотр</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>У клиента нет сделок</p>
    @endif

    <a href="{{ route('deals.create', ['client_id' => $client->id]) }}" class="btn btn-primary">Добавить сделку</a>

    <!-- Контакты клиента -->
    <h2>Контакты клиента</h2>

    @if($client->contacts->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th>Тип</th>
                    <th>Дата</th>
                    <th>Комментарий</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($client->contacts as $contact)
                <tr>
                    <td>
                        @if($contact->type == 'call') Звонок
                        @elseif($contact->type == 'meeting') Встреча
                        @else Письмо
                        @endif
                    </td>
                    <td>{{ $contact->contact_date }}</td>
                    <td>{{ $contact->comment ?? '—' }}</td>
                    <td><a href="{{ route('contacts.show', $contact) }}">Просмотр</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Нет контактов</p>
    @endif

    <a href="{{ route('contacts.create', ['client_id' => $client->id]) }}" class="btn btn-primary">Добавить контакт</a>

    <div class="form-actions" style="margin-top: 30px;">
        <a href="{{ route('clients.edit', $client) }}" class="btn btn-primary">Редактировать</a>
        <a href="{{ route('clients.index') }}" class="btn btn-secondary">Назад к списку</a>
    </div>
@endsection