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
        $input = Request::getInputs();

        $task = Task::get($id);

        $userId = $input["userId"] ?? null;

        $task->userId = $userId === null || $userId === ''
            ? null
            : (int) $userId;


        $task->userId = $input["userId"] ?? null;
        $task->store();

        return $task;
    }
}
