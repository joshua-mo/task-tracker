<?php

namespace App\User\Models;

use App\Auth\Error\Unauthenticated;
use Neoan\Database\Database;
use Neoan\Model\Attributes\IsPrimaryKey;
use Neoan\Model\Attributes\IsUnique;
use Neoan\Model\Attributes\Transform;
use Neoan\Model\Model;
use Neoan\Model\Traits\TimeStamps;
use Neoan\Model\Transformers\Hash;
use Neoan\Model\Attributes\HasMany;
use Neoan\Model\Collection;
use App\Task\Models\Task;

class User extends Model
{
    use TimeStamps;
    #[IsPrimaryKey]
    public int $id;

    #[IsUnique]
    public string $email;

    #[Transform(Hash::class)]
    public string $password;

    #[HasMany(Task::class, ['userId' => 'id'])]
    public Collection $tasks;

    public static function login($email, $password): ?User
    {
        $res = Database::easy('user.id user.password', ['email' => $email]);
        if (empty($res) || !password_verify($password, $res[0]['password'])) {
            new Unauthenticated();
        }
        return self::get($res[0]['id']);
    }
}
