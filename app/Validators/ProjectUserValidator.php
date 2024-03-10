<?php

namespace App\Validators;

use Illuminate\Support\Facades\Validator;


class ProjectUserValidator{
    public static function validateInsert($data){
        return Validator::make($data, [
            'idProject' => 'required|integer',
            'idUser' => 'required|integer'
        ]);
    }

    public static function validateId($data){
        return Validator::make($data, [
            'idProject' => 'required|integer',
        ]);
    }
}