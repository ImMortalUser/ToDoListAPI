<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Infrastructure\Web\Controllers\TaskController;
use App\Infrastructure\Database\Connection;
use App\Infrastructure\Repository\SQLiteTaskRepository;
use App\Infrastructure\Web\Routing\Router;

$dbFile = __DIR__ . '/../Storage/database.sqlite';
$pdo = Connection::make($dbFile);
$repository = new SQLiteTaskRepository($pdo);
$controller = new TaskController($repository);

$router = new Router();

$router->addRoute('GET', '/tasks', fn() => $controller->getTaskList());
$router->addRoute('GET', '/tasks/{id}', fn() => $controller->getTask());
$router->addRoute('POST', '/tasks', fn() => $controller->createTask());
$router->addRoute('PUT', '/tasks/{id}', fn() => $controller->updateTask());
$router->addRoute('DELETE', '/tasks/{id}', fn() => $controller->deleteTask());

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$response = $router->dispatch($method, $uri);

if ($response !== null) {
    if (!headers_sent()) {
        header('Content-Type: application/json');
    }
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}