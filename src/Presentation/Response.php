<?php
namespace App\Presentation;

class Response
{
    public static function json(bool $success, array $data, int $status = 200): void
    {
        http_response_code($status);
        $data['success'] = $success;
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
}