<?php

namespace App\Http\Controllers;

use App\Models\UserProject;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class UserProjectController extends Controller
{
    public function index_for_artist()
    {
        $user_id = auth()->user()->id;

        $projects = UserProject::where('user_id', $user_id)->orderBy('id', 'desc')->get()->load('project_links');
        if (!$projects) {
            return response([
                'status' => false,
                'message' => 'You have not created any yet projects',
            ], 200);
        }

        return response([
            'status' => true,
            'message' => 'Projects fetched successfully',
            'data' => $projects
        ], 200);
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
        $data['short_link'] = $user->id. rand(00000,99999);
        $data['long_link'] = $data['title'];
        $data['image'] = url('/storage/projects/images').'/'. $this->uploadImage('image', $request,  'public/projects/images/');

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

    public function show_for_artist($userProject)
    {
        $user_id = auth()->user()->id;

        $userProject = UserProject::find($userProject);
        if (!$userProject) {
            return response([
                'status' => false,
                'message' => 'Project not found',
            ], 200);
        }

        if ($user_id != $userProject->user_id) {
            return response([
                'status' => false,
                'message' => 'Unathorized access',
            ], 403);
        }

        try {
            $user = auth()->user();
            $project = $userProject;
            if (!$project) {
                return response([
                    'status' => false,
                    'message' => 'Project not found',
                ], 200);
            }

            $project->load('project_links');
            return response([
                'status' => false,
                'message' => 'Project fetch successfully',
                'data' => $project
            ], 200);
        } catch (\Exception $ex) {
            return response([
                'status' => false,
                'message' => $ex->message,
            ], 200);
        }
        
    }

    public function update(Request $request, $userProject)
    {
        $user_id = auth()->user()->id;

        $userProject = UserProject::find($userProject);
        if (!$userProject) {
            return response([
                'status' => false,
                'message' => 'Project not found',
            ], 200);
        }

        if ($user_id != $userProject->user_id) {
            return response([
                'status' => false,
                'message' => 'Unathorized access',
            ], 403);
        }
        
        $data = $request->validate([
            'title' => 'required|string|bail',
            'description' => 'required|string|bail',
            'type' => 'required|string|bail',
        ]);
        
        $userProject->title = $data['title'];
        $userProject->description = $data['description'];
        $userProject->type = $data['type'];

        $userProject->save();
        // $userProject->load('user');

        return response([
            'status' => true,
            'message' => 'Project updated successfully',
            'data' => $userProject,
        ], 200);
    }

    public function destroy($userProject)
    {
        $user_id = auth()->user()->id;
        $userProject = UserProject::find($userProject);
        if (!$userProject) {
            return response([
                'status' => false,
                'message' => 'Project not found',
            ], 200);
        }

        if ($user_id != $userProject->user_id) {
            return response([
                'status' => false,
                'message' => 'Unathorized access',
            ], 403);
        }
        $userProject->project_links()->delete();
        $userProject->delete();

        return response([
            'status' => true,
            'message' => 'Project deleted successfully',
        ], 200);
    }
}
