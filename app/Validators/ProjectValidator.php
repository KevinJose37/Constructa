<?php

namespace App\Validators;

use Illuminate\Support\Facades\Validator;


class ProjectValidator{
    public static function validateStore($data){
        return Validator::make($data, [
            'project_name' => 'required|string',
            'project_description' => 'required|string',
            'project_status_id' => 'required|integer',
            'project_start_date' => 'required|date',
            'project_estimated_end' => 'required|date',
        ]);
    }
}