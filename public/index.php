<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use src\Config\Router;
use src\Controllers\TaskController;
use src\Infrastructure\Database\Connection;
use src\Infrastructure\Repository\SQLiteTaskRepository;

$dbFile = __DIR__ . '/../Storage/database.sqlite';
$pdo = Connection::make($dbFile);
$repository = new SQLiteTaskRepository($pdo);
$controller = new TaskController($repository);

$router = new Router();

$router->addRoute('GET', '/tasks', fn() => $controller->index());
$router->addRoute('GET', '/tasks/{id}', fn($params) => $controller->show((int)$params['id']));
$router->addRoute('POST', '/tasks', fn() => $controller->create());
$router->addRoute('PUT', '/tasks/{id}', fn($params) => $controller->update((int)$params['id']));
$router->addRoute('DELETE', '/tasks/{id}', fn($params) => $controller->delete((int)$params['id']));

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$response = $router->dispatch($method, $uri);

if ($response !== null) {
    if (!headers_sent()) {
        header('Content-Type: application/json');
    }
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}