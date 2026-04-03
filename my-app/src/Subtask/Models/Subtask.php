<?php

namespace App\Subtask\Models;

use Neoan\Model\Attributes\IsPrimaryKey;
use Neoan\Model\Model;
use Neoan\Model\Attributes\IsForeignKey;
use App\Task\Models\Task;
use Neoan\Model\Traits\TimeStamps;

class Subtask extends Model
{
    use TimeStamps;
    #[IsPrimaryKey]
    public int $id;

    #[IsForeignKey(Task::class)]
    public int $taskId;
    public string $title;
    public bool $isDone = false;

}
