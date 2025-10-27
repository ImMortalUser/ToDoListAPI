<?php
declare(strict_types=1);

namespace App\Shared\Validation;

class TaskValidator
{
    private const ALLOWED_STATUSES = ['pending', 'in_progress', 'done'];

    public static function validateCreateTask(array $data): array
    {
        $errors = [];

        if (!isset($data['title']) || trim($data['title']) === '') {
            $errors['title'] = 'Title is required';
        }

        $errors = array_merge($errors, self::validateCommonFields($data));

        return $errors;
    }

    public static function validateUpdateTask(array $data, ?int $id = null): array
    {
        $errors = self::validateId($id);

        if (!isset($data['title']) && !isset($data['description']) && !isset($data['status'])) {
            $errors['data'] = 'At least one field must be filled to update task';
        }

        $errors = array_merge($errors, self::validateCommonFields($data));

        return $errors;
    }

    public static function validateId(?int $id): array
    {
        $errors = [];
        if ($id === null || $id <= 0) {
            $errors['id'] = 'Invalid task ID';
        }
        return $errors;
    }

    private static function validateCommonFields(array $data): array
    {
        $errors = [];

        if (isset($data['status']) && !in_array($data['status'], self::ALLOWED_STATUSES, true)) {
            $errors['status'] = sprintf(
                'Invalid status. Allowed: %s',
                implode(', ', self::ALLOWED_STATUSES)
            );
        }

        return $errors;
    }
}