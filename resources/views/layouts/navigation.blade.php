<nav style="padding: 10px; background: #f0f0f0; margin-bottom: 20px;">
    <a href="{{ route('dashboard') }}" style="margin-right: 15px;">📊 Дашборд</a>
    <a href="{{ route('clients.index') }}" style="margin-right: 15px;">👥 Клиенты</a>
    <a href="{{ route('deals.index') }}" style="margin-right: 15px;">💼 Сделки</a>
    <a href="{{ route('contacts.index') }}" style="margin-right: 15px;">📞 Контакты</a>
    <a href="{{ route('admin.index') }}" style="margin-right: 15px;">⚙️ Админка</a>
    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
        @csrf
        <button type="submit" style="background: none; border: none; color: red; cursor: pointer;">🚪 Выйти</button>
    </form>
</nav>