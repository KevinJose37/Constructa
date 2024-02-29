<?php

namespace App\Validators;

use Illuminate\Support\Facades\Validator;


class UserValidator{
    public static function validateId($data){
        return Validator::make($data, [
            'id' => 'required|integer',
        ]);
    }
}