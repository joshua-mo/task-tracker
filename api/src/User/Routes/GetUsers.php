<?php

namespace App\User\Routes;

use Neoan\Routing\Attributes\Get;
use Neoan\Routing\Interfaces\Routable;
use App\User\Models\User;

#[Get('/api/users')]
class GetUsers implements Routable
{
    public function __invoke(): array
    {
        $users = [];

        foreach (User::retrieve() as $user) {
            $users[] = [
                'id' => $user->id,
                'email' => $user->email,
            ];
        }

        return $users;
    }
}
