<?php
declare(strict_types=1);

namespace App\Application\Commands\Task;

class CreateTaskCommand
{
    public string $title;
    public ?string $description;
    public string $status;

    public function __construct(string $title, ?string $description, string $status)
    {
        $this->title = $title;
        $this->description = $description;
        $this->status = $status;
    }
}