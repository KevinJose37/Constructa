<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use App\Models\Chat;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;


class ChatComponent extends Component
{
    public $selectedProjectId;
    public $projectId;
    public $newMessage;
    public $messages;
    public $attachment;


    public function render()
    {
        try {
            $this->messages = $this->getMessagesByProject();
            $projects = Project::all();
            return view('livewire.chat-component', compact('projects'));
        } catch (\Exception $e) {
            // Manejar la excepción
            $this->addError('error', 'Error al cargar los mensajes: ' . $e->getMessage());
            return view('livewire.chat-component');
        }
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
        try {
            
            $this->messages = $this->getMessagesByProject();

        } catch (\Exception $e) {
            // Manejar la excepción
            $this->addError('error', 'Error al cargar los mensajes: ' . $e->getMessage());
        }
    }
    public function saveMessage()
    {
        try {
            if ($this->selectedProjectId) {
                $message = new Chat();
                $message->project_id = $this->selectedProjectId;
                $message->user_id = Auth::id();
                $message->message = $this->newMessage;
                $message->save();

                $this->newMessage = '';
                $this->loadMessages(); 

            } else {
                // Manejar el caso en el que no se ha seleccionado un proyecto
                $this->addError('error', 'No se ha seleccionado un proyecto para guardar el mensaje.');
            }
        } catch (\Exception $e) {
            // Manejar la excepción
            $this->addError('error', 'Error al guardar el mensaje: ' . $e->getMessage());
        }
    }
}
