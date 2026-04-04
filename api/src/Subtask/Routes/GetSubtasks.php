<?php

namespace App\Subtask\Routes;

use App\Auth\Middleware\RequiresLogin;
use Neoan\Routing\Attributes\Get;
use Neoan\Routing\Interfaces\Routable;
use App\Subtask\Models\Subtask;

#[Get('/api/subtasks', RequiresLogin::class)]
class GetSubtasks implements Routable
{
    public function __invoke(): array
    {
        return Subtask::retrieve(["deletedAt" => null])->toArray();
    }
}
