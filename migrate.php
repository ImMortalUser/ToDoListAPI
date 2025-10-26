<?php
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use src\Infrastructure\Database\Connection;

$dbFile = __DIR__ . '/Storage/database.sqlite';
$pdo = Connection::make($dbFile);

$pdo->exec('CREATE TABLE IF NOT EXISTS tasks (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    description TEXT,
    status TEXT DEFAULT "pending",
    created_at TEXT NOT NULL,
    updated_at TEXT NOT NULL
)');

echo "Migration complete.\n";