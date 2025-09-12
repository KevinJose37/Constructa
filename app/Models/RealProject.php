<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealProject extends Model
{
    use HasFactory;

    protected $table = 'real_projects';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'project_id',
        'id_capitulo',
        'chapter_number',
        'chapter_name'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function items()
    {
        return $this->hasMany(RealProjectInfo::class, 'real_project_id');
    }

    public function details()
    {
        return $this->hasMany(RealProjectInfo::class, 'real_project_id');
    }
    public function workProgressChapters()
    {
        return $this->hasMany(\App\Models\WorkProgressChapter::class, 'id_capitulo', 'id_capitulo');
    }

}