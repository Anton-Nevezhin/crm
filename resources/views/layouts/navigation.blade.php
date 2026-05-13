<nav class="navbar">
    <div class="container">
        <div class="logo">CRM</div>
        <div class="nav-links">
            @auth
                <a href="{{ route('dashboard') }}">Дашборд</a>
                <a href="{{ route('clients.index') }}">Клиенты</a>
                <a href="{{ route('deals.index') }}">Сделки</a>
                <a href="{{ route('contacts.index') }}">Контакты</a>
                <a href="{{ route('reports.months') }}">По месяцам</a>
                <a href="{{ route('admin.index') }}">Админка</a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-logout">Выйти</button>
                </form>
            @endauth
        </div>
    </div>
</nav>