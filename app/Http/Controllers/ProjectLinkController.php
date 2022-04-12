<?php

namespace App\Http\Controllers;

use App\Models\ProjectLink;
use Illuminate\Http\Request;

class ProjectLinkController extends Controller
{
    public function store(Request $request, $project_id)
    {
        $data = $request->validate([
            'title' => 'required|string|bail',
            'link' => 'required|url|bail',
        ]);
        
        $user = auth()->user();
        $project = $user->projects()->where('id', $project_id)->first();

        if (!$project) {
            return response([
                'status' => false,
                'message' => 'Project not found',
            ], 200);
        }

        $data['image'] = url('storage/project_links/images').'/'. $this->save_favicon_image($data['link'], 'public/project_links/images/');

        $project_link = $project->project_links()->create($data);
        return response([
            'status' => false,
            'message' => 'Project link added successfully',
            'data' => $project_link
        ], 200);
    }

    public function update(Request $request, $project_id, $project_link_id)
    {
        $user = auth()->user();
        $project = $user->projects()->where('id', $project_id)->first();

        if (!$project) {
            return response([
                'status' => false,
                'message' => 'Project not found',
            ], 200);
        }

        $projectLink = $project->project_links()->where('id' , $project_link_id)->first();

        if (!$projectLink) {
            return response([
                'status' => false,
                'message' => 'Project Link not found',
            ], 200);
        }

        $data = $request->validate([
            'title' => 'required|string|bail',
            'link' => 'required|url|bail',
        ]);

        $projectLink->title = $data['title'];
        $projectLink->link = $data['link'];
        $projectLink->image = url('storage/project_links/images').'/'. $this->save_favicon_image($data['link'], 'public/project_links/images/');

        $projectLink->save();

        return response([
            'status' => true,
            'message' => 'Project link updated successfully',
            'data' => $projectLink
        ], 200);
    }

    public function destroy($project_id, $project_link_id)
    {
        $user = auth()->user();
        $project = $user->projects()->where('id', $project_id)->first();

        if (!$project) {
            return response([
                'status' => false,
                'message' => 'Project not found',
            ], 200);
        }

        $projectLink = $project->project_links()->where('id' , $project_link_id)->first();

        if (!$projectLink) {
            return response([
                'status' => false,
                'message' => 'Project Link not found',
            ], 200);
        }

        $projectLink->delete();
        return response([
            'status' => true,
            'message' => 'Project link deleted successfully',
        ], 200);
    }
}
