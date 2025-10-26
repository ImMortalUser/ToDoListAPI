<?php
declare(strict_types=1);

namespace src\Application\Commands\Handlers;

use src\Application\Commands\UpdateTaskCommand;
use src\Domain\Task\TaskRepositoryInterface;
use src\Domain\Task\Task;

class UpdateTaskHandler
{
    private TaskRepositoryInterface $repository;

    public function __construct(TaskRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function handle(UpdateTaskCommand $command): ?Task
    {
        $existing = $this->repository->find($command->id);
        if (!$existing) {
            return null;
        }
        $existing->title = $command->title;
        $existing->description = $command->description;
        $existing->status = $command->status;
        $existing->updatedAt = date('c');
        return $this->repository->update($existing);
    }
}