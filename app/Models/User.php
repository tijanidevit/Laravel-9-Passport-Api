<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
// use Laravel\Sanctum\HasApiTokens;
// use Laravel\Passport\HasApiTokens;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fullname',
        'stage_name',
        'email',
        'password',
        'verification_code'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_code',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    protected static function boot()
    {
        parent::boot();
        static::created(function ($user) {
            $user->slug = $user->generateSlug($user->stage_name);
            $user->save();
        });
    }
    private function generateSlug($stage_name)
    {
        if (static::whereSlug($slug = Str::slug($stage_name))->exists()) {
            $max = static::whereStageName($stage_name)->latest('id')->skip(1)->value('slug');
            if (isset($max[-1]) && is_numeric($max[-1])) {
                return preg_replace_callback('/(\d+)$/', function($mathces) {
                    return $mathces[1] + 1;
                }, $max);
            }
            return "{$slug}-2";
        }
        return $slug;
    } 

    public function socials()
    {
        return $this->hasMany(UserSocial::class);
    }

    public function projects()
    {
        return $this->hasMany(UserProject::class);
    }
}
