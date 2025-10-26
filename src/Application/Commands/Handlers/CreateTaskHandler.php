<?php
declare(strict_types=1);

namespace src\Application\Commands\Handlers;

use src\Application\Commands\CreateTaskCommand;
use src\Domain\Task\Task;
use src\Domain\Task\TaskRepositoryInterface;

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