<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Chat;
use Illuminate\Support\Facades\Auth;

class ChatProjectController extends Controller
{
    public function show()
{
    try {
        $projects = Project::all();
        return view('ChatProjects', compact('projects'));
    } catch (\Exception $e) {
        // Manejo de errores
    }
}



}
