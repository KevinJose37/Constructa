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

public function saveMessage(Request $request)
    {
        $request->validate([
            'selectedProjectId' => 'required',
            'newMessage' => 'required',
        ]);

        $message = new Chat();
        $message->project_id = $request->input('selectedProjectId');
        $message->user_id = Auth::id();
        $message->message = $request->input('newMessage');
        $message->save();

        return redirect()->route('chatprojects')->with('success', 'Mensaje guardado exitosamente.');
    }

}
