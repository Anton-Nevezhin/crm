@extends('layouts.app')

@section('title', 'Новая сделка')

@section('content')
    <h1>Добавление новой сделки</h1>

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
        <form method="POST" action="{{ route('deals.store') }}">
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
                <label>Название сделки:</label><br>
                <input type="text" name="name" required>
            </div>

            <div>
                <label>Сумма (₽):</label><br>
                <input type="number" name="amount" step="0.01" required>
            </div>

            <div>
                <label>Статус:</label><br>
                <select name="status">
                    <option value="new">Новая</option>
                    <option value="in_progress">В работе</option>
                    <option value="closed">Закрыта</option>
                    <option value="lost">Потеряна</option>
                </select>
            </div>

            <div>
                <label>Описание:</label><br>
                <textarea name="description" rows="4" cols="50"></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Сохранить</button>
                <a href="{{ route('deals.index') }}" class="btn btn-secondary">Отмена</a>
            </div>
        </form>
    </div>
@endsection