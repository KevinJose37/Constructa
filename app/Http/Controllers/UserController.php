<?php

namespace App\Http\Controllers;

use App\Services\UserServices;
use App\Validators\UserValidator;
use Illuminate\Http\Request;

class UserController extends Controller
{

    protected $userService;

    public function __construct(UserServices $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return response()->json($this->userService->getAll($request), 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $validator = UserValidator::validateId(['id' => $id]);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => "Bad request",
                'errors' => $validator->errors()
            ], 400);
        }

        $userInfo = $this->userService->getById($id);
        if($userInfo === null){
            return response()->json([
                'status' => false,
                'message' => 'Not found',
                'errors' => ''
            ], 404);
        }

        return response()->json($this->userService->getById($id));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
