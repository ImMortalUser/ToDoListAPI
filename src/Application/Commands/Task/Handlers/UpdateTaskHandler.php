<?php
declare(strict_types=1);

namespace App\Application\Commands\Task\Handlers;

use App\Application\Commands\Task\UpdateTaskCommand;
use App\Domain\Task\TaskRepositoryInterface;
use App\Domain\Task\Task;

class UpdateTaskHandler
{
    private TaskRepositoryInterface $repository;

    public function __construct(TaskRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function handle(UpdateTaskCommand $command): ?Task
    {
        $task = $this->repository->find($command->id);
        if (!$task) {
            return null;
        }
        $task->title = $command->title ?? $task->title;
        $task->description = $command->description ?? $task->description;
        $task->status = $command->status ?? $task->status;
        $task->updatedAt = date('c');
        return $this->repository->update($task);
    }
}