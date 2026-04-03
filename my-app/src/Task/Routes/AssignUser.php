<?php

namespace App\Task\Routes;

use App\Auth\Middleware\RequiresLogin;
use Neoan\Routing\Attributes\Patch;
use Neoan\Routing\Interfaces\Routable;
use Neoan\Request\Request;
use App\Task\Models\Task;

#[Patch('/api/tasks/:id/assign', RequiresLogin::class)]
class AssignUser implements Routable
{
    public function __invoke(): Task
    {
        $id = (int) Request::getParameters()["id"];
        $userId = Request::getInputs()["userId"] ?? null;

        $task = Task::get($id);


        $task->userId = $userId;
        $task->store();

        return $task;
    }
}
