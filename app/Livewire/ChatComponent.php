<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use App\Models\Chat;
use Illuminate\Support\Facades\Auth;

class ChatComponent extends Component
{
    public $selectedProjectId;
    public $projectId;
    public $newMessage;
    public $messages;

    public function render()
    {
        $this->messages = $this->getMessagesByProject();
        $projects = Project::all();
        return view('livewire.chat-component', compact('projects'));
    }

    public function getMessagesByProject()
{
    if ($this->selectedProjectId) {
        return Chat::with('user')
            ->where('project_id', $this->selectedProjectId)
            ->get();
    }
    return null;
}


    public function loadMessages()
{
    $this->messages = $this->getMessagesByProject();
}


    public function saveMessage()
{
    $message = new Chat();
    $message->project_id = $this->selectedProjectId;
    $message->user_id = Auth::id();
    $message->message = $this->newMessage;
    $message->save();

    $this->newMessage = '';
}

}
