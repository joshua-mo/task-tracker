<?php

namespace App\Task\Routes;

use Neoan\Routing\Attributes\Delete;
use Neoan\Routing\Interfaces\Routable;
use App\Task\Models\Task;

#[Delete('/api/tasks/all')]
class DeleteAllTasks implements Routable
{
    public function __invoke(): array
    {
        try {
            $tasks = Task::retrieve();

            foreach ($tasks as $task) {
                $task->delete(true);
            }


            return [
                'success' => true
            ];
        } catch (\Throwable $e) {
            return [
                'error' => 'Could not delete tasks'
            ];
        }
    }
}
