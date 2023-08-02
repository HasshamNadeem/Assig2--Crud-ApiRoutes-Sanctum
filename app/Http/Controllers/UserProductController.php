<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserProductResource;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class UserProductController extends Controller
{
    public function index(User $user)
    {
        $this->authorize('index', $user);

        return    UserProductResource::collection($user->products()->paginate(5));
    }

    public function store(Request $request, User $user)
    {
        request()->validate([
            'id' => ['required', 'integer'],
        ]);

        $this->authorize('store', $user);

        $user->products()->attach($request->input('id'));

        return response()->json(['message' => 'Product attached successfully'], 200);
    }

    public function destroy(User $user, Product $product)
    {
        // this delete method is used from product policy
        $this->authorize('delete', $product);

        $user->products()->detach($product->id);

        return response()->json(['message' => 'Product detached successfully'], 200);
    }
}
