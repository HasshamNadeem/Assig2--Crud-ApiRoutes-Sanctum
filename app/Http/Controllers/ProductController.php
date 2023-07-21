<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Product::class, 'product');
    }

    public function store(Request $request)
    {
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

        $product->users()->attach(auth()->user()->id);

        return response()->json(['message' => 'Product Added Successfully!'], 201);
    }

    public function index()
    {
        return response()->json(['products' => auth()->user()->products()->paginate(6)], 200);
    }

    public function show(Product $product)
    {
        return $product;
    }

    public function update(Request $request, Product $product)
    {
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
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}
