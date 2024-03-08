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
    $projectId = $request->input('project_id');
    $messages = Chat::where('project_id', $projectId)->get();
    return view('ChatProjects', ['messages' => $messages]);
}
    

}
