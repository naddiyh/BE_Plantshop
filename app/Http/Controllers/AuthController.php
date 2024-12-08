<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessTokenResult;

// class AuthController extends Controller
// {
//     public function register(Request $request)
//     {
//         $validator = Validator::make($request->all(), [
//             'name' => 'required|string|max:255',
//             'email' => 'required|string|email|max:255|unique:users',
//             'password' => 'required|string|min:8',
//         ]);

//         if ($validator->fails()) {
//             return response()->json(['errors' => $validator->errors()], 422);
//         }

//         $user = User::create([
//             'name' => $request->name,
//             'email' => $request->email,
//             'password' => Hash::make($request->password),
//         ]);

//         return response()->json(['user' => $user], 201);
//     }

//     public function login(Request $request)
//     {
//         $credentials = $request->only('email', 'password');

//         if (Auth::attempt($credentials)) {
//              /** @var \App\Models\User $user **/
//             $user = Auth::user();
//             $token = $user->createToken('plantshop')->plainTextToken;

//             return response()->json(['token' => $token], 200);
//         }

//         return response()->json(['message' => 'Unauthorized'], 401);
//     }

//     public function index(Request $request)
//     {
//         return response()->json($request->user()); // Mengembalikan informasi user yang terautentikasi
//     }
// }




namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
   
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);
    
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);
    
        $token = $user->createToken('plantshop')->plainTextToken;
    
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
    

   
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
    
        if (!Auth::attempt($validated)) {
            return response()->json(['message' => 'Invalid email or password'], 401);
        }
       /** @var \App\Models\User $user **/
        $user = Auth::user();
        
        $token = $user->createToken('plantshop')->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

//     public function login(Request $request)
// {
//     if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
//         return response()->json(['user' => Auth::user()]);
//     }

//     return response()->json(['message' => 'Invalid credentials'], 401);
// }

// public function user()
// {
//     return response()->json(Auth::user());
// }

public function logout(Request $request)
{
    // Revoke the current token
    $request->user()->tokens->each(function ($token) {
        $token->delete();
    });

    return response()->json(['message' => 'Successfully logged out']);
}}

