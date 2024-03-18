<?php
namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class PaginationServices{

    protected $request;

    public function filter($query)
    {
        return $query->paginate(10);
    }
}