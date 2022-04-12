<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectLink extends Model
{
    use HasFactory;

    protected $guarded = [];
    

    public function project_links()
    {
        return $this->hasMany(ProjectLink::class);
    }
}
