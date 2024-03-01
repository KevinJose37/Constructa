<?php

namespace App\Services;

use Illuminate\Http\Request;

interface IService{
    public function getAll();
    public function getAllPaginate($filter = "");
    public function getById(int $id);
    public function Add(array $data);
    public function Update(int $id, array $data);
    public function Delete(int $id);
}