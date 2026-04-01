<?php

namespace App\Task\Routes;

use Neoan\Routing\Attributes\Patch;
use Neoan\Routing\Interfaces\Routable;
use Neoan\Request\Request;
use App\Task\Models\Task;

#[Patch('/api/tasks/:id')]
class UpdateTask implements Routable
{
    public function __invoke(): array
    {
        $id = (int) Request::getParameters()["id"];
        $task = Task::get($id);
        $data = Request::getInputs();
        foreach ($data as $key => $value) {
            $task->$key = $value;
        }
        return $task -> store()->toArray();
    }
}
