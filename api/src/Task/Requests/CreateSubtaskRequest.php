<?php

namespace App\Task\Requests;

use Neoan\Request\RequestGuard;

class CreateSubtaskRequest extends RequestGuard
{
    public string $title;
}
