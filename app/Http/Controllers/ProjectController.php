<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Services\ProjectServices;
use App\Validators\ProjectValidator;
use Illuminate\Http\Request;

class ProjectController extends Controller
{

    protected $projectService;

    public function __construct(ProjectServices $projectServices)
    {
        $this->projectService = $projectServices;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = $request->input('filter');
        $projects = $this->projectService->getAllPaginate($filter); // Obtener todos los proyectos

        return view('Proyectos', compact('projects', 'filter'));
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
        // Validar los datos del formulario
        $data = $request->json()->all();
        $validator = ProjectValidator::validateStore($data);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => "Bad request",
                'errors' => $validator->errors()->toArray()
            ], 400);
        }

        $data['project_name'] = $request->input('project_name');
        $data['project_description'] = $request->input('project_description');
        $data['project_status_id'] = $request->input('project_status_id');
        $data['project_start_date'] = $request->input('project_start_date');
        $data['project_estimated_end'] = $request->input('project_estimated_end');
        $responseSave = $this->projectService->Add($data);
        if($responseSave){
            return response()->json([
                'success' => true,
                'message' => "Se creó correctamente el proyecto {$data['project_name']}",
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message' => "Ocurrió un error al crear el proyecto {$data['project_name']}",
        ], 500);


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'project_name' => 'required|string',
            'project_description' => 'required|string',
            'project_status_id' => 'required|integer',
            'project_start_date' => 'required|date',
            'project_estimated_end' => 'required|date',
        ]);

        // Obtener el proyecto a actualizar
        $project = Project::findOrFail($request->input('project_id'));

        // Actualizar los datos del proyecto
        $project->project_name = $request->input('project_name');
        $project->project_description = $request->input('project_description');
        $project->project_status_id = $request->input('project_status_id');
        $project->project_start_date = $request->input('project_start_date');
        $project->project_estimated_end = $request->input('project_estimated_end');

        // Guardar los cambios
        $project->save();

        // Redireccionar a la página de proyectos u otra acción deseada
        return redirect()->route('projects.index')->with('success', '¡Proyecto actualizado exitosamente!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Buscar el proyecto por su ID
        $project = Project::findOrFail($id);

        // Eliminar el proyecto
        $project->delete();

        // Redireccionar a la página de proyectos u otra acción deseada
        return redirect()->route('projects.index')->with('success', '¡Proyecto eliminado exitosamente!');
    }
}
