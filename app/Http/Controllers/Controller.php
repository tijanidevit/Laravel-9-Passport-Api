<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;
use Storage;
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

    protected function uploadImage($file, $request, $path)
    {
        if($request->hasFile($file)){
            $image = $request->file($file);
            $fileName   = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs($path,$fileName);
            return $fileName;
          }
    }

    protected function save_favicon_image($url, $path) {
        $save_file_path = $path;    
        $domain = parse_url($url)['host'];
        $name = substr($url, strrpos($url, '/') + 1).'.png';

        $filepath = $save_file_path . $name;
    
        if (!file_exists ($filepath)) {
            $image = file_get_contents ('https://www.google.com/s2/favicons?domain=' . $domain);
            Storage::put($filepath, $image);
        }
        return $name;
    }

    public function tc()
    {
        try {
            
        } catch (\Exception $ex) {
            return response([
                'status' => false,
                'message' => $ex->message,
            ], 200);
        }
    }
}
