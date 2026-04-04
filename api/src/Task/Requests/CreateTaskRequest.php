<?php

namespace App\Task\Requests;

use Neoan\Request\RequestGuard;

class CreateTaskRequest extends RequestGuard
{
    public string $title;
    public ?string $description = null;
    public array $subtasks = [];
}
