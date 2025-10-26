<?php
declare(strict_types=1);

namespace src\Controllers;

use src\Application\Commands\CreateTaskCommand;
use src\Application\Commands\UpdateTaskCommand;
use src\Application\Commands\DeleteTaskCommand;
use src\Application\Commands\Handlers\CreateTaskHandler;
use src\Application\Commands\Handlers\UpdateTaskHandler;
use src\Application\Commands\Handlers\DeleteTaskHandler;
use src\Application\Queries\GetTasksQuery;
use src\Application\Queries\GetTaskQuery;
use src\Application\Queries\Handlers\GetTasksHandler;
use src\Application\Queries\Handlers\GetTaskHandler;
use src\Domain\Task\TaskRepositoryInterface;
use src\Shared\Response;
use src\Shared\Validator;

class TaskController
{
    private TaskRepositoryInterface $repository;

    public function __construct(TaskRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(): void
    {
        $handler = new GetTasksHandler($this->repository);
        $data = $handler->handle(new GetTasksQuery());
        Response::json($data);
    }

    public function show(int $id): void
    {
        $handler = new GetTaskHandler($this->repository);
        $task = $handler->handle(new GetTaskQuery($id));
        if (!$task) {
            Response::json(['error' => 'Task not found'], 404);
            return;
        }
        Response::json($task);
    }

    public function create(): void
    {
        $data = $this->getRequestData();
        $errors = Validator::validateTask($data);
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

    public function update(int $id): void
    {
        $data = $this->getRequestData();
        $errors = Validator::validateTask($data);
        if ($errors) {
            Response::json(['errors' => $errors], 422);
            return;
        }

        $handler = new UpdateTaskHandler($this->repository);
        $updated = $handler->handle(
            new UpdateTaskCommand(
                $id,
                $data['title'],
                $data['description'] ?? null,
                $data['status'] ?? 'pending'
            )
        );

        if (!$updated) {
            Response::json(['error' => 'Task not found'], 404);
            return;
        }

        Response::json($updated->toArray());
    }

    public function delete(int $id): void
    {
        $handler = new DeleteTaskHandler($this->repository);
        $ok = $handler->handle(new DeleteTaskCommand($id));

        if (!$ok) {
            Response::json(['error' => 'Task not found'], 404);
            return;
        }

        Response::json(['success' => true]);
    }

    private function getRequestData(): array
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        if (stripos($contentType, 'application/json') !== false) {
            $data = json_decode(file_get_contents('php://input'), true);
            return is_array($data) ? $data : [];
        }

        if (stripos($contentType, 'application/x-www-form-urlencoded') !== false) {
            return $_POST;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return $_GET;
        }

        return [];
    }
}