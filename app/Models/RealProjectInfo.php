<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealProjectInfo extends Model
{
    use HasFactory;

    protected $fillable = ['real_project_id', 'item_number', 'description', 'total'];

    public function chapter()
    {
        return $this->belongsTo(RealProject::class, 'real_project_id');
    }
}