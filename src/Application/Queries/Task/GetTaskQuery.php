<?php
declare(strict_types=1);

namespace App\Application\Queries\Task;

class GetTaskQuery
{
    public int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }
}
