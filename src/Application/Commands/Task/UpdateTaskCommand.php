<?php
declare(strict_types=1);

namespace App\Application\Commands\Task;

class UpdateTaskCommand
{
    public int $id;
    public ?string $title;
    public ?string $description;
    public ?string $status;

    public function __construct(int $id, ?string $title, ?string $description, ?string $status)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->status = $status;
    }
}