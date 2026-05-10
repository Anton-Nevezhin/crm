@extends('layouts.app')

@section('title', 'Новый контакт')

@section('content')
    <h1>Добавление нового контакта</h1>

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
                <option value="call">Звонок</option>
                <option value="meeting">Встреча</option>
                <option value="email">Письмо</option>
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

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Сохранить</button>
                <a href="{{ route('contacts.index') }}" class="btn btn-secondary">Отмена</a>
            </div>
        </form>
    </div>
@endsection