<?php

namespace App\Task\Routes;

use Neoan\Routing\Attributes\Delete;
use Neoan\Routing\Interfaces\Routable;
use Neoan\Request\Request;
use App\Task\Models\Task;

#[Delete('/api/tasks/:id')]
class DeleteTask implements Routable
{
    public function __invoke(): array
    {
        $id = (int) Request::getParameters()["id"];
        try {
            $task = Task::get($id);
            $task->delete(true);

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
