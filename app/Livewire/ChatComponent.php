<?php

namespace App\Livewire;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use App\Models\Project;
use App\Models\Chat;
use App\Models\Attachment;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;

class ChatComponent extends Component
{
    public $selectedProjectId;
    public $projectId;
    public $newMessage;
    public $messages;

    use WithFileUploads;
    public $attachments = [];

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
            if (trim($this->newMessage) === '') {
                // Agrega un mensaje de error y no procedas a guardar
                return; // Salir del método
            }

            if ($this->selectedProjectId) {
                $message = new Chat();
                $message->project_id = $this->selectedProjectId;
                $message->user_id = Auth::id();
                $message->message = $this->newMessage;
                $message->save();
            } else {
                // Manejar el caso en el que no se ha seleccionado un proyecto
                $this->addError('error', 'No se ha seleccionado un proyecto para guardar el mensaje.');
            }
            $this->newMessage = '';

        } catch (\Exception $e) {
            // Manejar la excepción
            $this->addError('error', 'Error al guardar el mensaje: ' . $e->getMessage());
        }
    }

    public function saveAttachments()
    {
        try {
            if ($this->selectedProjectId) {
                foreach ($this->attachments as $attachment) {
                    $originalName = $attachment->getClientOriginalName();
                    $attachmentPath = $attachment->storeAs('attachments', $originalName);
    
                    $messageText = '¡He subido el adjunto "' . $originalName . '"!';
    
                    $chat = Chat::create([
                        'project_id' => $this->selectedProjectId,
                        'user_id' => Auth::id(),
                        'message' => $messageText, // Utiliza el mensaje personalizado aquí
                        
                        'attachments' => $attachmentPath,
                    ]);
                }
    
                $this->attachments = [];
                $this->loadMessages();

            } else {
                $this->addError('error', 'No se ha seleccionado un proyecto para guardar los adjuntos.');
            }
        } catch (\Exception $e) {
            $this->addError('error', 'Error al guardar los adjuntos: ' . $e->getMessage());
        }
    }
    



public function deleteAttachment($messageId)
{
    try {
        // Buscar el mensaje por ID
        $message = Chat::findOrFail($messageId);

        // Verificar si hay un archivo adjunto y eliminarlo
        if ($message->attachments) {
            Storage::disk('public')->delete($message->attachments);
        }

        // Eliminar el registro del mensaje de la base de datos
        $message->delete();

        // Actualizar la lista de mensajes para reflejar el cambio
        $this->loadMessages();

        session()->flash('message', 'Mensaje eliminado con éxito.');
    } catch (\Exception $e) {
        // Manejar cualquier excepción que ocurra
        $this->addError('error', 'Error al eliminar el mensaje: ' . $e->getMessage());
    }
}
}
