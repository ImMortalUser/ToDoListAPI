<?php
declare(strict_types=1);

namespace App\Infrastructure\Web\Controllers;

use App\Application\Commands\Task\CreateTaskCommand;
use App\Application\Commands\Task\UpdateTaskCommand;
use App\Application\Commands\Task\DeleteTaskCommand;
use App\Application\Commands\Task\Handlers\CreateTaskHandler;
use App\Application\Commands\Task\Handlers\UpdateTaskHandler;
use App\Application\Commands\Task\Handlers\DeleteTaskHandler;
use App\Application\Queries\Task\GetTasksQuery;
use App\Application\Queries\Task\GetTaskQuery;
use App\Application\Queries\Task\Handlers\GetTasksHandler;
use App\Application\Queries\Task\Handlers\GetTaskHandler;
use App\Domain\Task\TaskRepositoryInterface;
use App\Shared\Response;
use App\Shared\Validation\TaskValidator;

class TaskController
{
    private TaskRepositoryInterface $repository;

    public function __construct(TaskRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getTaskList(): void
    {
        $handler = new GetTasksHandler($this->repository);
        $data = $handler->handle(new GetTasksQuery());
        Response::json($data);
    }

    public function getTask(): void
    {
        $id = $this->extractIdFromUri();
        $errors = TaskValidator::validateId($id);
        if ($errors) {
            Response::json(['errors' => $errors], 400);
            return;
        }

        $handler = new GetTaskHandler($this->repository);
        $task = $handler->handle(new GetTaskQuery($id));
        if (!$task) {
            Response::json(['error' => 'Task not found'], 404);
            return;
        }
        Response::json($task);
    }

    public function createTask(): void
    {
        $data = $this->getRequestData();
        $errors = TaskValidator::validateCreateTask($data);
        if ($errors) {
            Response::json(['errors' => $errors], 422);
            return;
        }

        $handler = new CreateTaskHandler($this->repository);
        $task = $handler->handle(
            new CreateTaskCommand(
                $data['title'],
                $data['description'] ?? null,
                $data['status'] ?? 'pending'
            )
        );
        Response::json($task->toArray(), 201);
    }

    public function updateTask(): void
    {
        $id = $this->extractIdFromUri();
        $data = $this->getRequestData();

        $errors = TaskValidator::validateUpdateTask($data, $id);
        if ($errors) {
            Response::json(['errors' => $errors], 422);
            return;
        }

        $handler = new UpdateTaskHandler($this->repository);
        $updated = $handler->handle(
            new UpdateTaskCommand(
                $id,
                $data['title'] ?? null,
                $data['description'] ?? null,
                $data['status'] ?? null
            )
        );

        if (!$updated) {
            Response::json(['error' => 'Task not found'], 404);
            return;
        }

        Response::json($updated->toArray());
    }

    public function deleteTask(): void
    {
        $id = $this->extractIdFromUri();
        $errors = TaskValidator::validateId($id);
        if ($errors) {
            Response::json(['errors' => $errors], 400);
            return;
        }

        $handler = new DeleteTaskHandler($this->repository);
        $ok = $handler->handle(new DeleteTaskCommand($id));

        if (!$ok) {
            Response::json(['error' => 'Task not found'], 404);
            return;
        }

        Response::json(['success' => true]);
    }

    private function extractIdFromUri(): ?int
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $segments = explode('/', trim($uri, '/'));
        $id = end($segments);
        return is_numeric($id) ? (int)$id : null;
    }

    private function getRequestData(): array
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        switch ($method) {
            case 'GET':
                return $_GET;

            case 'POST':
                if (stripos($contentType, 'application/json') !== false) {
                    $data = json_decode(file_get_contents('php://input'), true);
                    return is_array($data) ? $data : [];
                }

                if (stripos($contentType, 'application/x-www-form-urlencoded') !== false) {
                    return $_POST;
                }

                return [];

            case 'PUT':
            case 'DELETE':
                $raw = file_get_contents('php://input');
                if (stripos($contentType, 'application/json') !== false) {
                    $data = json_decode($raw, true);
                    return is_array($data) ? $data : [];
                }

                if (stripos($contentType, 'application/x-www-form-urlencoded') !== false) {
                    $data = [];
                    parse_str($raw, $data);
                    return $data;
                }

                return [];

            default:
                return [];
        }
    }
}