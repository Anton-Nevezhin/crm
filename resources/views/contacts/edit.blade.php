@extends('layouts.app')

@section('title', 'Редактирование контакта')

@section('content')
    <h1>Редактирование контакта: {{ $contact->name }}</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
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
                <option value="call" {{ $contact->type == 'call' ? 'selected' : '' }}>Звонок</option>
                <option value="meeting" {{ $contact->type == 'meeting' ? 'selected' : '' }}>Встреча</option>
                <option value="email" {{ $contact->type == 'email' ? 'selected' : '' }}>Письмо</option>
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

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Сохранить</button>
                <a href="{{ route('contacts.index') }}" class="btn btn-secondary">Отмена</a>
            </div>
        </form>
    </div>
@endsection