<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Http\Request;
use App\Services\ProjectServices;
use App\Services\ProjectUserServices;
use Illuminate\Support\Facades\Log;

class ShowProjects extends Component
{

    public $search = "";

    #[On('projectRefresh')]
    public function render(ProjectServices $projectServices)
    {
        $projects = $projectServices->getAllPaginate($this->search);
        return view('livewire.show-projects',  compact('projects'));
    }

    #[On('destroy-project')] 
    public function destroy($id, ProjectUserServices $projectUserServices){
        $deleteProject = $projectUserServices->Delete($id);
        if($deleteProject === true){
            $this->dispatch('projectRefresh')->to(ShowProjects::class);
            $this->dispatch('alert', type: 'success', title: 'Proyectos',message: "Se eliminó correctamente el proyecto");
            return;
        }

        $message = $deleteProject['message'];
        $this->dispatch('alert', type: 'error', title: 'Proyectos',message: $message);
    }

    public function viewUsersProject($id){
        Log::info("aaaa");
        $this->dispatch('loadForm', $id)->to(ViewUsersProject::class);
    }

    public function destroyAlert($id, $name){
        $this->dispatch('alertConfirmation',
        id: $id,
        type: 'warning',
        title: 'Proyectos',
        message: "¿estás seguro de eliminar el proyecto {$name}?",
        emit: 'destroy-project',
    );}
}
