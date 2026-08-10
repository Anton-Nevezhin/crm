# Установка и настройка CRM

## Требования

- PHP 8.1 или выше
- MySQL 5.7 или выше
- Composer
- Node.js (опционально, для фронтенда)

## 1. Клонирование репозитория

```bash
git clone https://github.com/Anton-Nevezhin/crm.git
cd crm
```

## 2. Установка зависимостей

```bash
composer install
```

## 3. Настройка окружения

Создайте файл .env в корне проекта. Скопируйте в него этот минимальный набор:

```bash
APP_NAME="CRM"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost
```
Сгенерируйте ключ приложения:

```bash
php artisan key:generate
```

## 4. Настройка базы данных

Создайте базу данных MySQL и укажите её в .env:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=crm
DB_USERNAME=root
DB_PASSWORD=
```

Затем выполните миграции:

```bash
php artisan migrate --seed
```

## 5. Создание первого пользователя

Зарегистрируйтесь через форму регистрации на сайте. После регистрации вы получите доступ ко всем разделам CRM.

Для быстрого входа можно создать пользователя через консоль:

```bash
php artisan tinker --execute="User::create(['name' => 'Администратор', 'email' => 'admin@crm.ru', 'password' => bcrypt('password')]);"
```

Email: admin@crm.ru

Пароль: password

## 6. Запуск сервера

```bash
php artisan serve
```

Сайт будет доступен по адресу: http://127.0.0.1:8000

## 7. Тестирование

```bash
php artisan test
```

Ожидаемый результат: 29 тестов (70 ассертов) — всё зелёное.

## 8. Возможные проблемы и решения

### Ошибка подключения к базе данных

Проверьте, что MySQL запущен.
Проверьте правильность DB_DATABASE, DB_USERNAME, DB_PASSWORD в .env.

### Не работает API

Убедитесь, что сервер запущен.
Проверьте маршруты API в routes/api.php.

### Структура базы данных

Описание таблиц и связей приведено в основном README.