<?php
declare(strict_types=1);

namespace src\Application\Commands\Handlers;

use src\Application\Commands\DeleteTaskCommand;
use src\Domain\Task\TaskRepositoryInterface;

class DeleteTaskHandler
{
    private TaskRepositoryInterface $repository;

    public function __construct(TaskRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function handle(DeleteTaskCommand $command): bool
    {
        return $this->repository->delete($command->id);
    }
}
