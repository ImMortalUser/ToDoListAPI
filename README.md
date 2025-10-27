ToDo List API
- DDD, MVC, CQRS. БД - SQLite. Чистый PHP + composer

- `composer update` - создание vendor
- `php -e migrate.php` - создание бд
- `php -e fillDB.php` - заполнение фикстурами
- `php -S localhost:8000 -t public` - запуск сервера

- Сервер доступен по адресу: http://localhost:8000
- Список задач - http://localhost:8000/tasks

- Данные для GET запросов не требуются, но передаются через query
- Данные для не GET запросов передаются через x-www-form-urlencoded.
