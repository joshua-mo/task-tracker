<?php

namespace App\Task\Models;

use Neoan\Model\Model;
use Neoan\Model\Attributes\IsPrimaryKey;
use Neoan\Model\Traits\TimeStamps;
use Neoan\Model\Collection;
use Neoan\Model\Attributes\HasMany;
use App\Subtask\Models\Subtask;
use Neoan\Model\Attributes\IsForeignKey;
use App\User\Models\User;

class Task extends Model
{
    use TimeStamps;

    #[IsPrimaryKey]
    public int $id;
    public bool $isDone = false;
    public string $title;
    public ?string $description = null;
    #[IsForeignKey(User::class)]
    public ?int $userId = null;
    #[HasMany(Subtask::class, ['taskId' => 'id'])]
    public Collection $subtasks;

    // public int $numberOfSubtasks;
}
