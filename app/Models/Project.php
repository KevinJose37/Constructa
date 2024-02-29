<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "projects";

    protected $fillable = [
        'project_name',
        'project_description',
        'project_status_id',
        'project_start_date',
        'project_estimated_end',
    ];

    public function projectStatus()
    {
        return $this->belongsTo(ProjectStatus::class, 'rol_id');
    }
}
