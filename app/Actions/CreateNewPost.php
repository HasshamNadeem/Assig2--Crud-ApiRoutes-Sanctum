<?php

namespace App\Actions;

use App\Models\Post;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsController;

class CreateNewPost
{
    use AsAction;
    use AsController;

    public function handle(Request $request)
    {
        $request->validate(
            [
                'title' => ['required', 'string', 'max:50'],
                'body' => ['required', 'string', 'max:255'],
                'product_id' => ['required', 'integer'],
            ]);

        return Post::create([
            'title' => $request['title'],
            'body' => $request['body'],
            'product_id' => $request['product_id'],
            'user_id' => auth()->user()->id,
        ]);
    }
}
