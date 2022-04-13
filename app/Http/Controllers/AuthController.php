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
        $data = $request->validate([
            'fullname' => 'required|string|bail',
            'stage_name' => 'required|string|bail',
            'email' => 'required|string|email|unique:users|bail',
            'password' => 'required|string|bail|min:8',
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
    }
    
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|bail',
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
                'message' => 'Email account not yet verified',
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
    
    public function verify_code(Request $request)
    {
        try {
            $data = $request->validate([
                'verification_code' => 'required|string',
            ]);

            $verification_code = $data['verification_code'];
            
            $user = auth()->user();
    
            if ($user->verification_code == '') {
                return response([
                    'status' => true,
                    'message' => 'User account already verified',
                ], 200);
            }

            if ($user->verification_code != $verification_code) {
                return response([
                    'status' => false,
                    'message' => 'Invalid verification code',
                ], 200);
            }

            $user->verification_code = '';
            $user->save();
            return response([
                'status' => true,
                'message' => 'User email verified successfully',
                'data' => [
                    'user' => $user
                ]
            ], 200);
        } catch (\Exception $ex) {
            return response([
                'status' => false,
                'message' => $ex->getMessage(),
            ], 200);
        }        
    }
    
    public function request_new_code(Request $request)
    {
        try {        
            $user = auth()->user();    
            if ($user->verification_code == '') {
                return response([
                    'status' => true,
                    'message' => 'User account already verified',
                ], 200);
            }

            $user->verification_code = rand(111111,999999);
            $subject = config('app.name') ." Request For New Verification Code";
            
            Mail::to($data['email'])->send(new UserRegistrationMail($user['stage_name'], $subject, $user['verification_code']));
            $user->save();
            return response([
                'status' => true,
                'message' => 'New verification code sent successfully',
                'data' => [
                    'user' => $user
                ]
            ], 200);
        } catch (\Exception $ex) {
            return response([
                'status' => false,
                'message' => $ex->getMessage(),
            ], 200);
        }        
    }
}
