<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function register (Request $request)
    {
        $data= $request->validate([
            'name'=>'required|string|max:100',
            'email'=>'required|email|max:150|unique:users,email',
            'password'=>'required|string',

        ]);


       $user=User::create([
        'name'=>$data['name'],
        'email'=>$data['email'],
        'password'=> Hash::make( $data['password']),

       ]);

       $token=$user->createToken('fundaProjectToken')->plainTextToken;

       $response=[
        'user'=>$user,
        'token'=>$token,
        'satus'=>'successful'
       ];

       return response($response,201);


    }


    public function logout()
    {

        auth()->user()->tokens()->delete();
        return response()->json(['message'=>'User Logged Out Successfuly']);


    }

    public function login (Request $request)
    {
          $data=$request->validate([
            'email'=>'required|email|max:150',
            'password'=>'required|string',
          ]);

          $user=User::where('email',$data['email'])->first();

          if (!$user || ! Hash::check($data['password'], $user->password))
          {
            return response()->json(['message'=>'User Not Found'],401);

          }

          else
          {
            $token=$user->createToken('fundaProjectTokenLogin')->plainTextToken;

       $response=[
        'user'=>$user,
        'token'=>$token,
       ];

       return response($response,200);
          }
    }
}
