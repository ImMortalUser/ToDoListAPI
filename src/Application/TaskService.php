<?php
namespace App\Application;

use App\Domain\Task\{Task, TaskRepositoryInterface, TaskStatus};

class TaskService
{
    public function __construct(private TaskRepositoryInterface $repo) {}

    public function create(string $title, ?string $description, string $status): bool
    {
        $task = new Task(null, $title, $description, new TaskStatus($status), date('c'), date('c'));
        return $this->repo->add($task);
    }

    public function list(): array
    {
        return $this->repo->findAll();
    }

    public function get(int $id): ?Task
    {
        return $this->repo->find($id);
    }

    public function update(int $id, string $title, ?string $description, string $status): bool
    {
        $task = $this->repo->find($id);
        if (!$task) return false;

        $task->title = $title;
        $task->description = $description;
        $task->status = new TaskStatus($status);

        return $this->repo->update($task);
    }

    public function delete(int $id): bool
    {
        return $this->repo->delete($id);
    }
}