<?php
declare(strict_types=1);

namespace App\Application\Commands\Task\Handlers;

use App\Application\Commands\Task\CreateTaskCommand;
use App\Domain\Task\Task;
use App\Domain\Task\TaskRepositoryInterface;

class CreateTaskHandler
{
    private TaskRepositoryInterface $repository;

    public function __construct(TaskRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function handle(CreateTaskCommand $command): Task
    {
        $task = new Task(
            null,
            $command->title,
            $command->description,
            $command->status,
            date('c'),
            date('c')
        );
        return $this->repository->save($task);
    }
}