<?php
declare(strict_types=1);

namespace src\Domain\Task;

interface TaskRepositoryInterface
{
    public function save(Task $task): Task;
    public function update(Task $task): Task;
    public function delete(int $id): bool;
    public function find(int $id): ?Task;
    public function findAll(): array;
}