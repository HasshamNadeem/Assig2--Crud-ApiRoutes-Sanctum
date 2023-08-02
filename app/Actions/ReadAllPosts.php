<?php

namespace App\Actions;

use App\Models\Post;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsController;

class ReadAllPosts
{
    use AsAction;
    use AsController;

    public function handle()
    {
        return Post::all();
    }
}
