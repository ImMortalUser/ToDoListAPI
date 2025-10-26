<?php
declare(strict_types=1);

namespace src\Infrastructure\Database;

use PDO;

class Connection
{
    public static function make(string $dbFile): PDO
    {
        if (!file_exists(dirname($dbFile))) {
            mkdir(dirname($dbFile), 0777, true);
        }
        $dsn = 'sqlite:' . $dbFile;
        $pdo = new PDO($dsn);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    }
}