<?php
namespace App\Presentation;

use App\Application\TaskService;
use App\Domain\Task\TaskStatus;

class TaskController
{
    public function __construct(private TaskService $service) {}

    public function getTaskList(): void
    {
        $tasks = $this->service->list();
        Response::json(array_map(fn($t) => $t->toArray(), $tasks));
    }

    public function addTask(): void
    {
        $data = Request::all();

        try {
            $this->validate($data);
            $result = $this->service->create($data['title'], $data['description'] ?? null, $data['status'] ?? 'todo');
            $this->formResponse($result, null);
        } catch (\InvalidArgumentException $e) {
            Response::json(['error' => $e->getMessage()], 422);
        }
    }

    public function getTask(int $id): void
    {
        $task = $this->service->get($id);
        $task ? Response::json($task->toArray()) : Response::json(['error' => 'Not found'], 404);
    }

    public function updateTask(int $id): void
    {
        $data = Request::all();

        try {
            $this->validate($data, true);
            $task = $this->service->update($id, $data['title'] ?? '', $data['description'] ?? null, $data['status'] ?? 'todo');
            $task ? Response::json($task->toArray()) : Response::json(['error' => 'Not found'], 404);
        } catch (\InvalidArgumentException $e) {
            Response::json(['error' => $e->getMessage()], 422);
        }
    }

    public function deleteTask(int $id): void
    {
        $this->service->delete($id)
            ? Response::json(['success' => true])
            : Response::json(['error' => 'Not found'], 404);
    }

    private function validate(array $data, bool $partial = false): void
    {
        if (!$partial && (empty($data['title']) || trim($data['title']) === '')) {
            throw new \InvalidArgumentException('Title is required');
        }

        if (isset($data['status'])) {
            new TaskStatus($data['status']); // проверит допустимость
        }
    }

    private function formResponse(bool $status, ?string $data): string {
        $arr = ['status' => $status, 'data' => $data];
        return json_encode($arr);
    }
}