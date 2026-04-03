<?php

namespace App\Subtask\Routes;

use Neoan\Routing\Attributes\Get;
use Neoan\Routing\Interfaces\Routable;
use App\Subtask\Models\Subtask;

#[Get('/api/subtasks')]
class GetSubtasks implements Routable
{
    public function __invoke(): array
    {
        return Subtask::retrieve(["deletedAt" => null])->toArray();
    }
}
