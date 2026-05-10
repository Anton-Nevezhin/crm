@extends('layouts.app')

@section('title', 'Редактирование клиента')

@section('content')
    <h1>Редактирование клиента: {{ $client->name }}</h1>

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
        <form method="POST" action="{{ route('clients.update', $client) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Имя</label>
                <input type="text" id="name" name="name" value="{{ $client->name }}" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ $client->email }}" required>
            </div>

            <div class="form-group">
                <label for="phone">Телефон</label>
                <input type="text" id="phone" name="phone" value="{{ $client->phone }}">
            </div>

            <div class="form-group">
                <label for="address">Адрес</label>
                <textarea id="address" name="address" rows="3">{{ $client->address }}</textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Сохранить</button>
                <a href="{{ route('clients.index') }}" class="btn btn-secondary">Отмена</a>
            </div>
        </form>
    </div>
@endsection