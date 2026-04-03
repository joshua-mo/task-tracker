<?php

namespace App\Task\Routes;

use Neoan\Routing\Attributes\Get;
use Neoan\Routing\Interfaces\Routable;
use App\Task\Models\Task;

#[Get("/api/tasks")]

class GetTasks implements Routable
{
    public function __invoke(): array
    {


        return Task::retrieve(["deletedAt" => null])->toArray();
    }
}
