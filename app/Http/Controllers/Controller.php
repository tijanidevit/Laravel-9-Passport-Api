<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $user_count = 0;
    private $stage_name = '';

    protected function generateUserSlug($stage_name)
    {
        if ($this->stage_name == '') {
            $this->stage_name = $stage_name;
        }

        $new_stage_name = Str::slug($stage_name);
        
        $this->user_count = User::where('stage_name',$new_stage_name)->count();
        if ($this->user_count) {
            // $new_stage_name = $this->stage_name.$this->user_count+1;
            // return $this->generateUserSlug(Str::slug($new_stage_name));
            return $new_stage_name.rand(0000,9999);
        }
        else{
            return $new_stage_name;
        }

    }
}
