<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserProject extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::created(function ($user_project) {
            $user_project->long_link = $user_project->generateSlug($user_project->title, $user_project->user_id);
            $user_project->save();
        });
    }
    private function generateSlug($title,$user_id)
    {
        if (static::whereLongLink($slug = Str::slug($title))->exists()) {
            $max = static::whereTitle($title)->whereUserId($user_id)->latest('id')->skip(1)->value('long_link');
            if (isset($max[-1]) && is_numeric($max[-1])) {
                return preg_replace_callback('/(\d+)$/', function($mathces) {
                    return $mathces[1] + 1;
                }, $max);
            }
            return "{$slug}-2";
        }
        return $slug;
    } 
}
