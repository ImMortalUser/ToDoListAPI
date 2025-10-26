<?php
declare(strict_types=1);

namespace src\Shared;

class Validator
{
    public static function validateTask(array $data): array
    {
        $errors = [];
        if (!isset($data['title']) || trim($data['title']) === '') {
            $errors['title'] = 'Title is required';
        }
        if (isset($data['status']) && !in_array($data['status'], ['pending', 'in_progress', 'done'])) {
            $errors['status'] = 'Invalid status';
        }
        return $errors;
    }
}