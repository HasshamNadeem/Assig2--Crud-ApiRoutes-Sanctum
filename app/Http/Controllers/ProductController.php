<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function store (Request $request)
    {
        $request->validate([
            'name'=>['required', 'max: 200'],
             'description'=>['required', 'max: 200'],
            'price'=>['required', 'max: 200'],
             'qty'=>['required', 'max: 200'],
        ]);

        Product::create($request->all());

        return response()->json(['message'=>'Product Added Successfully!'],200);

    }

    public function index ()
    {
        $products=Product::all();
        return response()->json(['products'=> $products]);
    }

    public function show($id)
    {
          $product=Product::find($id);
          if ($product)
          {
          return response()->json(['product'=>$product]);
          }
          else
          {
            return response()->json(['message'=>'Product Not Found'],404);
          }
    }


    public function update(Request $request, $id)
    {
        request()->validate([
            'name'=>['required', 'max: 200'],
            'description'=>['required', 'max: 200'],
            'price'=>['required', 'max: 200'],
            'qty'=>['required', 'max: 200'],
        ]);

        $product=Product::find($id);

        if($product)
        {
             $product->update($request->all());

            // $product->name=$request->name;
            // $product->description=$request->description;
            // $product->price=$request->price;
            // $product->qty=$request->qty;
            // $product->update();

            return response()->json(['message'=>'Product Update Successfuly'],200);

        }

        else
        {
            return response()->json(['message'=>'Product Not Found'],404);
        }
    }

    public function destroy($id)
    {
        $product=Product::find($id);

        if ($product)
        {
            $product->delete();
            return response()->json(['message'=>'Product deleted successfully'],200);

        }
        else
        {
            return response()->json(['message'=>'Product not found '],404);
        }
    }
}
