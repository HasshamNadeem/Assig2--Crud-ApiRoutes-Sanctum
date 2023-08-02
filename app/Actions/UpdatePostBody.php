<?php

namespace App\Actions;

use App\Models\Post;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsController;

class UpdatePostBody
{
    use AsAction;
    use AsController;

    public function handle(Request $request, Post $post)
    {
        $request->validate(
            [
                'body' => ['required', 'string', 'max:255'],
            ]);

        $post->body = $request['body'];
        $post->save();

        return $post;
    }
}
