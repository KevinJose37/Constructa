<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Services\ProjectServices;
use App\Services\ProjectUserServices;

class ShowProjectsUser extends Component
{
    public $user;
    public $open = false;
    public $openSelect = false;
    public $project_select = "-";

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function toggleSelect()
    {
        $this->openSelect = !$this->openSelect;
    }

    public function store(ProjectUserServices $projectUserServices)
    {
        $userId = $this->user->id;
        $projectId = $this->project_select;

        $assignUser = $projectUserServices->Add(["idProject" => $projectId, "idUser" => $userId]);
        $this->project_select = "-";
        if (isset($assignUser['success']) && $assignUser['success'] === false) {
            $this->dispatch('alert', type: 'error', title: 'Ocurrió un error en la asignación', message: $assignUser['message']);
            return;
        }
        $this->dispatch('alert', type: 'success', title: 'Okey', message: 'Se asignó correctamente al proyecto');
    }

    #[On('destroy-project-user')] 
    public function destroy($id, ProjectUserServices $projectUserServices){
        $assignUser = $projectUserServices->DeleteUserByProject($id, $this->user->id );
        if(isset($assignUser['success']) && $assignUser['success'] === false){
            $this->dispatch('alert', type: 'error', title: 'Ocurrió un error en la acción',message: $assignUser['message']);
            return;
        }
        $this->dispatch('alert', type: 'success', title: 'Okey',message: 'Se desasignó correctamente al proyecto');
        
    }

    public function destroyAlertProject($id, $name){
        $this->dispatch('alertConfirmation',
        id: $id,
        type: 'warning',
        title: 'Proyectos',
        message: "¿estás seguro de desasignar al usuario {$this->user->name} del proyecto {$name}?",
        emit: 'destroy-project-user',
    );}

    public function render(ProjectUserServices $projectUserServices, ProjectServices $projectService)
    {
        $projectsUser = $projectUserServices->getProjectsByUserId($this->user->id);
        $projectNotAssign = $projectUserServices->getNotAssignedProjects($this->user->id);

        return view('livewire.show-projects-user', compact('projectsUser', 'projectNotAssign'));
    }
}
