<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="remember-wrapper">
            <label class="remember-checkbox">
                <input type="checkbox" name="remember"> Запомнить меня
            </label>
            <a href="{{ route('password.request') }}">Забыли пароль?</a>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-block">Войти</button>
        </div>

        <div class="guest-links">
            <a href="{{ route('register') }}">Нет аккаунта? Зарегистрироваться</a>
        </div>
    </form>
</x-guest-layout>
