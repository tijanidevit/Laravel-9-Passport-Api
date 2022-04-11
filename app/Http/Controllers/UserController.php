<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($userSlug)
    {
        try {
            $user = User::where('slug',$userSlug)->first();

            if (!$user) {
                return response([
                    'status' => false,
                    'message' => 'User not found'
                ], 200);
            }

            $socials = $user->load('socials.social');
            $projects = $user->load('projects')->orderBy('id', 'desc');
            return response([
                'status' => true,
                'message' => 'User fetched successfully',
                'data' => [
                    'user' => $user,
                ]
            ], 200);
        } catch (\Exception $ex) {
            return response([
                'status' => false,
                'message' => $ex->getMessage()
            ], 200);
        }
    }
    
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
