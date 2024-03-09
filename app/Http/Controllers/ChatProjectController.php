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
        $projects = Project::all();
        return view('ChatProjects', compact('projects'));
    }

    public function getMessagesByProject(Request $request)
    {
        try {
            $projectId = $request->input('project_id');
            $messages = Chat::where('project_id', $projectId)->get();
            return view('ChatProjects', ['messages' => $messages]);
        } catch (\Exception $e) {
            dd('Error obteniendo mensajes por proyecto: ' . $e->getMessage());
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
