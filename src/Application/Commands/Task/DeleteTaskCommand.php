<?php
declare(strict_types=1);

namespace App\Application\Commands\Task;

class DeleteTaskCommand
{
    public int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }
}