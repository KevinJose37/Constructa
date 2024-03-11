<?php

namespace App\Livewire;

use App\Models\Project;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Livewire\Forms\ViewUsersProjectForm;
use App\Services\ProjectUserServices;

class ViewUsersProject extends Component
{

    public $project;
    public $openSelect = false;
    public ViewUsersProjectForm $formUsers;

    public function mount(Project $project){
        $this->project = $project;
    }

    public function toggleSelect(){
        $this->openSelect = !$this->openSelect;
    }
    
    public function store(ProjectUserServices $projectUserServices){
        $userId = $this->formUsers->user_select;
        $projectId = $this->project->id;

        $assignUser = $projectUserServices->Add(["idProject" => $projectId, "idUser" => $userId]);
        $this->formUsers->user_select = "-";
        if(isset($assignUser['success']) && $assignUser['success'] === false){
            $this->dispatch('alert', type: 'error', title: 'Ocurrió un error en la asignación',message: $assignUser['message']);
            return;
        }
        $this->dispatch('alert', type: 'success', title: 'Okey',message: 'Se asignó correctamente al proyecto');
    }

    public function render(ProjectUserServices $projectUserServices)
    {
        $userInfo = $projectUserServices->getById($this->project->id);
        if($userInfo === null){
            $this->form->open = false;
            $this->dispatch('alert', type: 'error', title: 'Proyectos',message: "El proyecto {$this->project->name_project} no existe");
            return;
        }
        
        $usersNotAssigned = $projectUserServices->getNotAssignedUsers($this->project->id);

        return view('livewire.view-users-project', compact('usersNotAssigned'));
        // return view('livewire.view-users-project');
    }
}
