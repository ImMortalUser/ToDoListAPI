<?php
declare(strict_types=1);

namespace App\Application\Queries\Task\Handlers;

use App\Application\Queries\Task\GetTaskQuery;
use App\Domain\Task\TaskRepositoryInterface;

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