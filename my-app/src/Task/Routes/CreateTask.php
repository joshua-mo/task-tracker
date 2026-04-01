<?php

namespace App\Task\Routes;

use Neoan\Request\Request;
use App\Task\Models\Task;
use Neoan\Routing\Attributes\Post;
use Neoan\Routing\Interfaces\Routable;

#[Post('/api/tasks')]
class CreateTask implements Routable
{
    public function __invoke(): Task
    {
        return Task::retrieveOneOrCreate(
            (array) Request::getInputs()
        )->store();
    }
}
