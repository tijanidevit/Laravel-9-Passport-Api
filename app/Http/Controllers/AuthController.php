<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use Laravel\Passport\HasApiTokens;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'fullname' => 'string|bail',
            'stage_name' => 'string|bail',
            'email' => 'string|email|unique:users|bail',
            'password' => 'string|bail|min:8',
        ]);
        
        $data['password'] = Hash::make($data['password']);
        $data['verification_code'] = rand(000000,111111);

        $user = User:: create($data);

        $token = $user->createToken("token")->accessToken;

        

        return response([
            'status' => true,
            'message' => 'Registration completed',
            'data' => [
                'user' => $user,
                'token' => $token,
            ]
        ], 201);
    }
    
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'string|email',
            'password' => 'string|bail',
        ]);
        
        $is_email_existing = User::where('email', $data['email'])->count();

        if(!$is_email_existing){
            return response([
                'status' => true,
                'message' => 'Email Address Not Recognised',
            ], 401);
        }

        if (!Auth::attempt($data)) {
            return response([
                'status' => true,
                'message' => 'Invalid Credentials',
            ], 401); 
        }

        $user = auth()->user();
        $token = $user->createToken("token")->accessToken;
        return response([
            'status' => true,
            'message' => 'User Login Successful',
            'data' => [
                'user' => $user,
                'token' => $token,
            ]
        ], 200);

        
    }
}
