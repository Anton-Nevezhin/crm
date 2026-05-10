@extends('layouts.app')

@section('title', 'Контакт ' . $contact->name)

@section('content')
    <h1>Просмотр контакта {{ $contact->name }}</h1>

    <div class="card">
        <p><strong>ID:</strong> {{ $contact->id }}</p>
        <p><strong>Клиент:</strong> <a href="{{ route('clients.show', $contact->client) }}">{{ $contact->client->name }}</a></p>
        <p><strong>Тип:</strong> 
            @if($contact->type == 'call') Звонок
            @elseif($contact->type == 'meeting') Встреча
            @else Письмо
            @endif
        </p>
        <p><strong>Дата:</strong> {{ $contact->contact_date }}</p>
        <p><strong>Комментарий:</strong> {{ $contact->comment ?? '—' }}</p>
        <p><strong>Создан:</strong> {{ $contact->created_at }}</p>
        <p><strong>Обновлён:</strong> {{ $contact->updated_at }}</p>
    </div>

    <div class="form-actions" style="margin-top: 30px;">
        <a href="{{ route('contacts.edit', $contact) }}" class="btn btn-primary">Редактировать</a>
        <a href="{{ route('contacts.index') }}" class="btn btn-secondary">Назад к списку</a>
    </div>
@endsection