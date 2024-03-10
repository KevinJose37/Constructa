<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Services\ProjectServices;
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
        $validatedData = $request->validate([
            'project_name' => 'required|string',
            'project_description' => 'required|string',
            'project_status_id' => 'required|integer',
            'project_start_date' => 'required|date',
            'project_estimated_end' => 'required|date',
        ]);

        // Crear un nuevo proyecto
        $project = new Project();
        $project->project_name = $request->input('project_name');
        $project->project_description = $request->input('project_description');
        $project->project_status_id = $request->input('project_status_id');
        $project->project_start_date = $request->input('project_start_date');
        $project->project_estimated_end = $request->input('project_estimated_end');

        // Guardar el proyecto
        $project->save();

        // Redireccionar a la página de proyectos u otra acción deseada
        return redirect()->route('projects.index')->with('success', '¡Proyecto creado exitosamente!');
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
