<?php

namespace App\Http\Controllers;

use App\Mail\UserRegistrationMail;
use Mail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use Laravel\Passport\HasApiTokens;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $data = $request->validate([
                'fullname' => 'string|bail',
                'stage_name' => 'string|bail',
                'email' => 'string|email|unique:users|bail',
                'password' => 'string|bail|min:8',
            ]);
            
            $data['password'] = Hash::make($data['password']);
            $data['email'] = $data['email'];
            $data['verification_code'] = rand(111111,999999);
            $subject = config('app.name') ." Verification Code";
    
            
    
            $user = User:: create($data);
    
            $token = $user->createToken("token")->accessToken;           
            Mail::to($data['email'])->send(new UserRegistrationMail($data['stage_name'], $subject, $data['verification_code']));
            return response([
                'status' => true,
                'message' => 'Registration completed',
                'data' => [
                    'user' => $user,
                    'token' => $token,
                ]
            ], 201);
        } catch (\Exception $ex) {
            return response([
                'status' => false,
                'message' => $ex->getMessage(),
            ], 200);
        }
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

        if ($user->verification_code != '') {
            return response([
                'status' => false,
                'message' => 'Login Successful! Email account not yet verified',
            ], 200);
        }
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
