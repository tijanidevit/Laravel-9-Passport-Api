<?php

namespace App\Http\Controllers;

use App\Models\UserProject;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class UserProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|bail',
            'image' => 'required|mimetypes:image/jpg,image/jpg,image/jpeg,image/png',
            'description' => 'required|string|bail',
            'type' => 'required|string|bail',
        ]);

        $user = auth()->user();
        $data['views'] = 0;
        $data['long_url'] = Str::slug($data['title']);
        $data['short_url'] = $user->id. rand(00000,99999);

        $project = $user->projects()->create($data);

        if($project){
            return response([
                'status' => true,
                'message' => 'Project added successfully',
                'data' => $project,
            ], 201);
        }
        else{
            return response([
                'status' => false,
                'message' => 'Unable to add project',
            ], 200);
        }

    }

    public function show(UserProject $userProject)
    {
        //
    }

    public function update(Request $request, UserProject $userProject)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserProject  $userProject
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserProject $userProject)
    {
        //
    }
}
