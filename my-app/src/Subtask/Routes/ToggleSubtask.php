<?php

namespace App\Subtask\Routes;

use Neoan\Request\Request;
use Neoan\Routing\Attributes\Patch;
use Neoan\Routing\Interfaces\Routable;
use App\Subtask\Models\Subtask;
use App\Task\Models\Task;

#[Patch('/api/subtasks/:id')]
class ToggleSubtask implements Routable
{
    public function __invoke(): Task
    {

        $id = (int) Request::getParameters()["id"];



        $subtask = Subtask::get($id);
        $subtask->isDone = !$subtask->isDone;
        $subtask->store();

        $allSubtasks = Subtask::retrieve([
                'taskId' => $subtask->taskId
            ]);

        $allDone = true;

        foreach ($allSubtasks as $sub) {
            if (!$sub->isDone) {
                $allDone = false;
                break;
            }
        }


        $task = Task::get($subtask->taskId);
        $task->isDone = $allDone;
        $task->store();





        return $task;




        // $subtask = Subtask::retrieveOne(["id" => (int)$input["id"]]);

        // $subtask->isDone = !$subtask->isDone;
        // $subtask->store();

        // return $subtask;
    }
}
