<?php
namespace App\Infrastructure;

use App\Domain\Task\{Task, TaskRepositoryInterface, TaskStatus};
use PDO;

class PdoTaskRepository implements TaskRepositoryInterface
{
    public function __construct(private PDO $pdo) {}

    public function add(Task $task): bool
    {
        $now = date('c');
        return $this->pdo->prepare('
            INSERT INTO tasks (title, description, status, created_at, updated_at)
            VALUES (:title, :description, :status, :created, :updated)
        ')->execute([
            ':title' => $task->title,
            ':description' => $task->description,
            ':status' => (string)$task->status,
            ':created' => $now,
            ':updated' => $now,
        ]);
    }

    public function update(Task $task): bool
    {
        return $this->pdo->prepare('
            UPDATE tasks
            SET title = :title, description = :description, status = :status, updated_at = :updated
            WHERE id = :id
        ')->execute([
            ':title' => $task->title,
            ':description' => $task->description,
            ':status' => (string)$task->status,
            ':updated' => date('c'),
            ':id' => $task->id,
        ]);
    }

    public function delete(int $id): bool
    {
        return $this->pdo->prepare('DELETE FROM tasks WHERE id = :id')->execute([':id' => $id]);
    }

    public function find(int $id): ?Task
    {
        $stmt = $this->pdo->prepare('SELECT * FROM tasks WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->map($row) : null;
    }

    public function findAll(): ?array
    {
        $rows = $this->pdo->query('SELECT * FROM tasks ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => $this->map($r), $rows);
    }

    private function map(array $r): Task
    {
        return new Task(
            (int)$r['id'],
            $r['title'],
            $r['description'],
            new TaskStatus($r['status']),
            $r['created_at'],
            $r['updated_at']
        );
    }
}