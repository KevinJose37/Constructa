<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealProjectInfo extends Model
{
    use HasFactory;

    protected $table = 'real_project_infos';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = ['real_project_id', 'item_number', 'description', 'total', 'umbral_fisico', 'umbral_financiero'];


    public function chapter()
    {
        return $this->belongsTo(RealProject::class, 'real_project_id');
    }

    public function realProject()
    {
        return $this->belongsTo(RealProject::class, 'real_project_id');
    }
}