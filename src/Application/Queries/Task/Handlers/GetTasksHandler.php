<?php
declare(strict_types=1);

namespace App\Application\Queries\Task\Handlers;

use App\Application\Queries\Task\GetTasksQuery;
use App\Domain\Task\TaskRepositoryInterface;

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