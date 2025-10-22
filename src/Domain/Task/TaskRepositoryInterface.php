<?php
namespace App\Domain\Task;

interface TaskRepositoryInterface
{
    public function add(Task $task): bool;
    public function update(Task $task): bool;
    public function delete(int $id): bool;
    public function find(int $id): ?Task;
    public function findAll(): ?array;
}