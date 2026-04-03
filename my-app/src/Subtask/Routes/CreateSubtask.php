<?php

namespace App\Subtask\Routes;

use Neoan\Routing\Attributes\Post;
use Neoan\Routing\Interfaces\Routable;
use Neoan\Request\Request;
use App\Subtask\Models\Subtask;
use App\Task\Models\Task;

#[Post('/api/subtasks')]
class CreateSubtask implements Routable
{
    public function __invoke(): Task
    {
        $input = Request::getInputs();
        $subtask = new Subtask();
        $subtask->taskId = $input["taskId"];
        $subtask->title = $input["title"];
        $subtask->isDone = false;

        $subtask->store();

        $task = Task::get($input["taskId"]);

        return $task;


    }
}
