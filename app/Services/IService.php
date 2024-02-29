<?php

namespace App\Services;

use Illuminate\Http\Request;

interface IService{
    
    public function getAll(Request $request);
    public function getById(int $id);
    public function Add(Request $request);
    public function Update(int $id, Request $request);
    public function Delete(int $id, Request $request);
}