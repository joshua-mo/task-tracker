<?php

namespace App\Task\Routes;

use App\Auth\Middleware\RequiresLogin;
use Neoan\Routing\Attributes\Post;
use Neoan\Routing\Interfaces\Routable;
use Neoan\Request\Request;
use App\Subtask\Models\Subtask;
use App\Task\Models\Task;

#[Post('/api/tasks/:taskId/subtask', RequiresLogin::class)]
class CreateSubtask implements Routable
{
    public function __invoke(): Task
    {
        $taskId = (int)Request::getParameters()["taskId"];

        $input = Request::getInputs();
        $subtask = new Subtask();
        $subtask->taskId = $taskId;
        $subtask->title = $input["title"];
        $subtask->isDone = false;

        $subtask->store();


        $task = Task::get($taskId);
        $task->isDone = false;
        $task->store();

        return $task;


    }
}
