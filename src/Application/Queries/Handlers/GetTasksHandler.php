<?php
declare(strict_types=1);

namespace src\Application\Queries\Handlers;

use src\Application\Queries\GetTasksQuery;
use src\Domain\Task\TaskRepositoryInterface;

class GetTasksHandler
{
    private TaskRepositoryInterface $repository;

    public function __construct(TaskRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function handle(GetTasksQuery $query): array
    {
        return array_map(fn($task) => $task->toArray(), $this->repository->findAll());
    }
}