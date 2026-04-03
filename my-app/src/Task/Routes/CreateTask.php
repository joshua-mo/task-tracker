<?php

namespace App\Task\Routes;

use App\Auth\Middleware\RequiresLogin;
use Neoan\Request\Request;
use App\Task\Models\Task;
use Neoan\Routing\Attributes\Post;
use Neoan\Routing\Interfaces\Routable;
use App\Subtask\Models\Subtask;

#[Post('/api/tasks', RequiresLogin::class)]
class CreateTask implements Routable
{
    public function __invoke(): Task
    {
        $input = Request::getInputs();
        $task = new Task();
        $task->title = $input["title"] ?? "";
        $task->description = $input["description"] ?? "";
        $task->userId = $input["userId"] ?? null;
        $task->isDone = false;

        $task->store();

        foreach (($input["subtasks"] ?? []) as $subtaskTitle) {
            $subtask = new Subtask();
            $subtask->title = $subtaskTitle;
            $subtask->taskId = $task->id;
            $subtask->isDone = false;
            $subtask->store();
        }

        $task = Task::get($task->id);

        return $task;



    }
}
