<?php
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use App\Domain\Task\Task;
use App\Infrastructure\Database\Connection;
use App\Infrastructure\Repository\SQLiteTaskRepository;

$dbFile = __DIR__ . '/Storage/database.sqlite';
$pdo = Connection::make($dbFile);

$repo = new SQLiteTaskRepository($pdo);

$repo->save(new Task(1, 'task 1', 'description 1', 'in_progress', date("Y-m-d"), date("Y-m-d")));
$repo->save(new Task(2, 'task 2', 'description 2', 'pending', date("Y-m-d"), date("Y-m-d")));
$repo->save(new Task(3, 'task 3', null, 'pending', date("Y-m-d"), date("Y-m-d")));
$repo->save(new Task(4, 'task 4', 'description 4', 'done', date("Y-m-d"), date("Y-m-d")));

echo "Database fill done\n";