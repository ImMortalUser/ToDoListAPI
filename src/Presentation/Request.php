<?php
namespace App\Presentation;

class Request
{
    public static function all(): array
    {
        if (!empty($_POST)) {
            return $_POST;
        }

        $input = file_get_contents('php://input');
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        if (str_contains($contentType, 'application/x-www-form-urlencoded')) {
            parse_str($input, $data);
            return $data;
        }

        return [];
    }
}