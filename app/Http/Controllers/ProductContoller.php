<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductContoller extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Product::class, 'product');
    }

    public function index()
    {
        return  ProductResource::collection(Product::paginate());
    }

    public function show(Product $product)
    {
        return $product;
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:200', 'unique:products,name'],
            'description' => ['required', 'max: 200'],
            'price' => ['required', 'max: 200', 'integer'],
        ]);

        $product = new Product;

        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->save();

        return response()->json(['message' => 'Product Added Successfully!'], 201);
    }

    public function update(Request $request, Product $product)
    {
        request()->validate([
            'name' => ['required', 'max: 200'],
            'description' => ['required', 'max: 200'],
            'price' => ['required', 'max: 200', 'integer'],
        ]);

        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->save();

        return response()->json(['message' => 'Product Update Successfully'], 200);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}
