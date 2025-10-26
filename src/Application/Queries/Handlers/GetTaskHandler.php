<?php
declare(strict_types=1);

namespace src\Application\Queries\Handlers;

use src\Application\Queries\GetTaskQuery;
use src\Domain\Task\TaskRepositoryInterface;

class GetTaskHandler
{
    private TaskRepositoryInterface $repository;

    public function __construct(TaskRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function handle(GetTaskQuery $query): ?array
    {
        $task = $this->repository->find($query->id);
        return $task ? $task->toArray() : null;
    }
}