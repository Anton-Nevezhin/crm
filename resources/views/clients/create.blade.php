@extends('layouts.app')

@section('title', 'Новый клиент')

@section('content')
    <h1>Добавление нового клиента</h1>

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
        <form method="POST" action="{{ route('clients.store') }}">
            @csrf

            <div class="form-group">
                <label for="name">Имя</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="phone">Телефон</label>
                <input type="text" id="phone" name="phone">
            </div>

            <div class="form-group">
                <label for="address">Адрес</label>
                <textarea id="address" name="address" rows="3"></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Сохранить</button>
                <a href="{{ route('clients.index') }}" class="btn btn-secondary">Отмена</a>
            </div>
        </form>
    </div>
@endsection