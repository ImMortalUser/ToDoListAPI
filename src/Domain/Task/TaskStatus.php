<?php
namespace App\Domain\Task;

class TaskStatus
{
    private const ALLOWED = ['todo', 'in_progress', 'done'];

    public function __construct(private string $value)
    {
        if (!in_array($value, self::ALLOWED)) {
            throw new \InvalidArgumentException('Invalid status: ' . $value);
        }
    }

    public function __toString(): string { return $this->value; }
}
