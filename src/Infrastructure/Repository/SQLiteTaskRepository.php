<?php
declare(strict_types=1);

namespace src\Infrastructure\Repository;

use src\Domain\Task\Task;
use src\Domain\Task\TaskRepositoryInterface;
use PDO;

class SQLiteTaskRepository implements TaskRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(Task $task): Task
    {
        $now = (new \DateTime())->format('c');
        $stmt = $this->pdo->prepare('INSERT INTO tasks (title, description, status, created_at, updated_at) VALUES (:title, :description, :status, :created_at, :updated_at)');
        $stmt->execute([
            ':title' => $task->title,
            ':description' => $task->description,
            ':status' => $task->status,
            ':created_at' => $now,
            ':updated_at' => $now,
        ]);
        $id = (int)$this->pdo->lastInsertId();
        return $this->find($id);
    }

    public function update(Task $task): Task
    {
        $now = (new \DateTime())->format('c');
        $stmt = $this->pdo->prepare('UPDATE tasks SET title = :title, description = :description, status = :status, updated_at = :updated_at WHERE id = :id');
        $stmt->execute([
            ':title' => $task->title,
            ':description' => $task->description,
            ':status' => $task->status,
            ':updated_at' => $now,
            ':id' => $task->id,
        ]);
        return $this->find($task->id);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM tasks WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }

    public function find(int $id): ?Task
    {
        $stmt = $this->pdo->prepare('SELECT * FROM tasks WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        if (!$row) return null;
        return new Task((int)$row['id'], $row['title'], $row['description'], $row['status'], $row['created_at'], $row['updated_at']);
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM tasks ORDER BY id DESC');
        $rows = $stmt->fetchAll();
        $result = [];
        foreach ($rows as $row) {
            $result[] = new Task((int)$row['id'], $row['title'], $row['description'], $row['status'], $row['created_at'], $row['updated_at']);
        }
        return $result;
    }
}