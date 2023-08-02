<?php

namespace App\Actions;

use App\Models\Post;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsController;

class DeletePost
{
    use AsAction;
    use AsController;

    public function handle(Post $post)
    {
        $post->delete();

        return response()->json(['message' => 'Post deleted successfully'], 200);
    }
}
