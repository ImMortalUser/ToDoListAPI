<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Config\Router\Router;
use App\Presentation\TaskController;
use App\Shared\Database;
use App\Infrastructure\PdoTaskRepository;
use App\Application\TaskService;

$container = new class {
    private array $services = [];
    public function set(string $key, $service): void { $this->services[$key] = $service; }
    public function get(string $key) { return $this->services[$key]; }
};

$db = new Database(__DIR__ . '/../data/database.sqlite');
$repository = new PdoTaskRepository($db->getPdo());
$service = new TaskService($repository);
$controller = new TaskController($service);

$container->set(TaskController::class, $controller);

$router = new Router();

(require __DIR__ . '/../src/Config/routes.php')($router, $container);

$method = $_SERVER['REQUEST_METHOD'];
$uri = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

$response = $router->dispatch($method, $uri);

if (is_array($response)) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} elseif ($response !== null) {
    echo $response;
}