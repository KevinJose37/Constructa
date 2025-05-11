<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealProject extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'chapter_number', 'chapter_name'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function items()
    {
        return $this->hasMany(RealProjectInfo::class, 'real_project_id');
    }
}