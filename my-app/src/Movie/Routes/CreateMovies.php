<?php

namespace App\Movie\Routes;

use Neoan\Routing\Attributes\Post;
use Neoan\Routing\Interfaces\Routable;

#[Post('/api/movie')]
class CreateMovies implements Routable
{
    public function __invoke(): array
    {
        return ['name' => 'CreateMovies'];
    }
}