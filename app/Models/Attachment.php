<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = ['project_id', 'user_id', 'message', 'attachments'];


    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
