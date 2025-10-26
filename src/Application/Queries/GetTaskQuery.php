<?php
declare(strict_types=1);

namespace src\Application\Queries;

class GetTaskQuery
{
    public int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }
}
