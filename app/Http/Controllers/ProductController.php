<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function store(Request $request, Product $product)
    {

        $request->validate([
             'name'=>['required', 'max: 200'],
             'description'=>['required', 'max: 200'],
             'price'=>['required', 'max: 200'],
        ]);


        $product->user_id=auth()->user()->id;
        $product->name=$request->input('name');
        $product->description=$request->input('description');
        $product->price=$request->input('price');
        $product->save();

        return response()->json(['message'=>'Product Added Successfully!'],200);

    }



 public function index(Product $product)
 {
    $user = User::find(auth()->user()->id);
    $products = $user->products;
    return response()->json(['products'=> $products]);
 }

    public function show (Product $id)
    {
        if ($id->user_id==auth()->user()->id)
        {
             return $id;
        }
        else
        {
            return response()->json(['message'=>'You have no product with this ID.'],404);
        }
    }


    public function update(Request $request, Product $id)
    {

        if ($id->user_id==auth()->user()->id)
        {
        request()->validate([
            'name'=>['required', 'max: 200'],
            'description'=>['required', 'max: 200'],
            'price'=>['required', 'max: 200'],
        ]);



        $id->user_id=auth()->user()->id;
        $id->name=$request->input('name');
        $id->description=$request->input('description');
        $id->price=$request->input('price');
        $id->save();


        return response()->json(['message'=>'Product Update Successfuly'],200);
      }
      else
      {
          return response()->json(['message'=>'You have no product with this ID.'],404);
      }



    }



    public function destroy(Product $id)
    {
        if ($id->user_id==auth()->user()->id)
        {
        $id->delete();
        return response()->json(['message'=>'Product deleted successfully'],200);
        }
        else
        {
            return response()->json(['message'=>'You have no product with this ID.'],404);
        }
    }

}
    