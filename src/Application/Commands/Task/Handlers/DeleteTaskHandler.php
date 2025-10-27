<?php
declare(strict_types=1);

namespace App\Application\Commands\Task\Handlers;

use App\Application\Commands\Task\DeleteTaskCommand;
use App\Domain\Task\TaskRepositoryInterface;

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
