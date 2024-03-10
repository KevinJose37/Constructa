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


public function getMessagesByProject(Request $request)
{
    try {
        $projectId = $request->input('project_id');
        
        // Cargar mensajes con usuarios relacionados
        $messages = Chat::with('user')
            ->where('project_id', $projectId)
            ->get();

        return view('partials.messages', ['messages' => $messages]);
    } catch (\Exception $e) {
        // Manejo de errores
        return response()->json(['error' => 'Error obteniendo mensajes por proyecto: ' . $e->getMessage()], 500);
    }
}

    
    

    public function saveMessageInProject(Request $request)
    {
        try {
            $request->validate([
                'project_id' => 'required|exists:projects,id',
                'message' => 'required|string',
            ]);

            $message = new Chat();
            $message->project_id = $request->project_id;
            $message->user_id = auth()->user()->id;
            $message->message = $request->message;
            $message->save();

            return redirect()->route('chatprojects')->with('success', 'Mensaje guardado exitosamente.');
        } catch (\Exception $e) {
            dd('Error al guardar el mensaje: ' . $e->getMessage());
        }
    }
}
