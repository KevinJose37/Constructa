<?php
namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class PaginationServices{

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function filter(Builder $query)
    {
        $perPage = $this->request->query('per_page', 10);
        $page = $this->request->query('page', 1);

        return $query->paginate($perPage, ['*'], 'page', $page);
    }
}