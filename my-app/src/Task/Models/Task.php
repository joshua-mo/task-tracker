<?php

namespace App\Task\Models;

use Neoan\Model\Model;
use Neoan\Model\Attributes\IsPrimaryKey;
use Neoan\Model\Traits\TimeStamps;

class Task extends Model
{
    #[IsPrimaryKey]
    public int $id;

    public string $title;
    public string $description;
    public int $user_id;

    use TimeStamps;
}
