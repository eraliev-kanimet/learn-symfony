# Добро пожаловать

Для запуска проекта у вас должны быть установлены composer и postgres (моя версия 14). Затем вы должны отредактировать переменную DATABASE_URL в .env на ваши учетные записи в postgres. Затем выполните эти команды.

```
composer install 
```

```
php bin/console doctrine:database:create
```

```
php bin/console doctrine:migrations:migrate
```

```
symfony server:start
```
