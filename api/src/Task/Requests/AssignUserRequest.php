<?php

namespace App\Task\Requests;

use Neoan\Request\RequestGuard;

class AssignUserRequest extends RequestGuard
{
    public ?int $userId = null;
}

// ?int wird im RequestGuard zu 0 gecastet, deshalb ohne Guard
