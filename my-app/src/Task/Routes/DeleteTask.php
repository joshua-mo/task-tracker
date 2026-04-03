<?php

namespace App\Task\Routes;

use Neoan\Routing\Attributes\Delete;
use Neoan\Routing\Interfaces\Routable;
use Neoan\Request\Request;
use App\Task\Models\Task;
use App\Subtask\Models\Subtask;
use Neoan\Database\Database;

#[Delete('/api/tasks/:id')]
class DeleteTask implements Routable
{
    public function __invoke(): array
    {
        $id = (int) Request::getParameters()["id"];
        try {
            $task = Task::get($id);

            $subtasks = Subtask::retrieve(['taskId' => $id]);
            foreach ($subtasks as $subtask) {
                Database::raw("UPDATE subtask SET deletedAt = datetime('now') WHERE id = {{id}}", ['id' => $subtask->id]);
            }

            Database::raw("UPDATE task SET deletedAt = datetime('now') WHERE id = {{id}}", ['id' => $id]);

            return [
                'success' => true
            ];
        } catch (\Throwable $e) {
            return [
                'error' => 'Task not found or already deleted'
            ];
        }
    }
}
