<?php

namespace App\Http\Controllers;

use App\Services\ProjectUserServices;
use App\Validators\ProjectUserValidator;
use Illuminate\Http\Request;

class ProjectUserController extends Controller
{

    protected $projectUserServices;

    public function __construct(ProjectUserServices $projectUserService) {
        $this->projectUserServices = $projectUserService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {   
        return response()->json($this->projectUserServices->getAll($request), 200);
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
        $data = $request->json()->all();
        $validator = ProjectUserValidator::validateInsert($data);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => "Bad request",
                'errors' => $validator->errors()
            ], 400);
        }

        $userId = $request->input('idUser');
        $projectId = $request->input('idProject');

        $assignUser = $this->projectUserServices->Add(["idProject" => $projectId, "idUser" => $userId]);

        if(isset($assignUser['success']) && $assignUser['success'] === false){
            return response()->json([
                'status' => false,
                'message' => "Ocurrió un error en la asignación",
                'errors' => $assignUser['message']
            ], 500);
        }

        return response()->json([
            'status' => true,
            'message' => "Se asignó correctamente el usuario",
        ], 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $idProject)
    {
        $validator = ProjectUserValidator::validateId(['idProject' => $idProject]);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => "Bad request",
                'errors' => $validator->errors()
            ], 400);
        }

        $userInfo = $this->projectUserServices->getById($idProject);
        if($userInfo === null){
            return response()->json([
                'status' => false,
                'message' => 'El proyecto no existe',
                'errors' => ''
            ], 404);
        }

        return response()->json($this->projectUserServices->getById($idProject));
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
