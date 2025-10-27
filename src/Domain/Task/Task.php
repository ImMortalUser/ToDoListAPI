<?php
declare(strict_types=1);

namespace App\Domain\Task;

class Task
{
    public ?int $id;
    public string $title;
    public ?string $description;
    public string $status;
    public string $createdAt;
    public string $updatedAt;

    public function __construct(?int $id, string $title, ?string $description, string $status, string $createdAt, string $updatedAt)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}