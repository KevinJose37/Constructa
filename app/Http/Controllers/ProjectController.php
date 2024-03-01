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
        //
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
