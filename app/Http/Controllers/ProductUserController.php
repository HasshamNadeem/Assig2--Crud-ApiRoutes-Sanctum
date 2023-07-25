<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductUser;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ProductUserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Product::class, 'product');
    }

    public function store(Request $request, User $user)
    {
        if (auth()->user()->id == $user->id) {
            $request->validate([
                'name' => ['required', 'max:200', 'unique:products,name'],
                'description' => ['required', 'max: 200'],
                'price' => ['required', 'max: 200'],
            ]);

            $product = new Product;

            $product->name = $request->input('name');
            $product->description = $request->input('description');
            $product->price = $request->input('price');
            $product->save();

            $product->users()->attach($user->id, [
                'created_at' => now(),  // Update created_at timestamp
                'updated_at' => now(),  // Update updated_at timestamp
            ]);

            return response()->json(['message' => 'Product Added Successfully!'], 201);
        } else {
            return response()->json(['message' => "You are trying to add in an other user's products"], 403);
        }
    }

    public function index(User $user)
    {
        if (auth()->user()->id == $user->id) {
            return ProductUser::collection($user->products()->paginate(5));
        } else {
            return response()->json(['message' => 'You are trying to view products of another user'], 403);
        }
    }

    public function show(User $user, Product $product)
    {
        if (auth()->user()->id == $user->id) {
            return $product;
        } else {
            return response()->json(['message' => 'You are trying to see the product of another user'], 403);
        }
    }

    public function update(Request $request, User $user, Product $product)
    {
        if (auth()->user()->id == $user->id) {
            request()->validate([
                'name' => ['required', 'max: 200'],
                'description' => ['required', 'max: 200'],
                'price' => ['required', 'max: 200'],
            ]);

            $product->name = $request->input('name');
            $product->description = $request->input('description');
            $product->price = $request->input('price');
            $product->save();

            return response()->json(['message' => 'Product Update Successfuly'], 200);
        } else {
            return response()->json(['message' => 'You are trying to update products of another user'], 403);
        }
    }

    public function destroy(User $user, Product $product)
    {
        if (auth()->user()->id == $user->id) {
            $product->delete();

            return response()->json(['message' => 'Product deleted successfully'], 200);
        } else {
            return response()->json(['message' => 'You are trying to delete product of another user'], 403);
        }
    }
}
