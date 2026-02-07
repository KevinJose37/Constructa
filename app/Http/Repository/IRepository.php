<?php
namespace App\Http\Repository;

interface IRepository{
    public function getAll();

    public function FindById($id);

    public function Create(array $data);

    public function Update($id, array $data);

    public function Delete($id);
}