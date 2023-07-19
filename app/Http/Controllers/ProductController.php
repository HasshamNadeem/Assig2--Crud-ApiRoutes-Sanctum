<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max: 200'],
            'description' => ['required', 'max: 200'],
            'price' => ['required', 'max: 200'],
        ]);

        $product = new Product;
        $product->user_id = auth()->user()->id;
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->save();

        return response()->json(['message' => 'Product Added Successfully!'], 201);
    }

    public function index()
    {
     return response()->json(['products' => auth()->user()->products]);
    }

    public function show(Product $key)
    {
        //authorizing using gate , returning 403 for not owned products
        if ($this->authorize('is-owner', $key)) {
            return $key;
        }
    }

    public function update(Request $request, Product $id)
    {
        //authorizing using policy, returning 403 for not owned products
        if ($this->authorize('update', $id)) {
            request()->validate([
                'name' => ['required', 'max: 200'],
                'description' => ['required', 'max: 200'],
                'price' => ['required', 'max: 200'],
            ]);

            //If I change this variable to name to product, the binding fails

            $id->user_id = auth()->user()->id;
            $id->name = $request->input('name');
            $id->description = $request->input('description');
            $id->price = $request->input('price');
            $id->save();

            return response()->json(['message' => 'Product Update Successfuly'], 200);
        }
    }

    public function destroy(Product $id)
    {
        //authorizing using policy, returning 403 for not owned products

        if ($this->authorize('is-owner', $id)) {
            $id->delete();

            return response()->json(['message' => 'Product deleted successfully'], 200);
        }
    }
}
