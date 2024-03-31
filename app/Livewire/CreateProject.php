<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\ProjectServices;
use App\Livewire\Forms\projectCreateForm;

class CreateProject extends Component
{
    public projectCreateForm $form;

    public function save(ProjectServices $projectServices){
        $this->form->validate();
        $data['project_name'] = $this->form->name_project;
        $data['project_description'] = $this->form->description_project;
        $data['project_status_id'] = $this->form->status_project;
        $data['project_start_date'] = $this->form->date_start_project;
        $data['project_estimated_end'] = $this->form->date_end_project;
        $responseSave = $projectServices->Add($data);
        if(!is_array($responseSave) && !isset($responseSave['success'])){
            $this->dispatch('projectRefresh')->to(ShowProjects::class);
            $this->dispatch('alert', type: 'success', title: 'Proyectos',message: "Se creó correctamente el proyecto {$this->form->name_project}");
            $this->form->reset();
            return;
        }
        $this->dispatch('alert', type: 'error', title: 'Proyectos',message: "Ocurrió un error al crear el proyecto {$this->form->name_project}");
    }

    public function render(ProjectServices $projectServices)
    {
        $projectstatus = $projectServices->getStatusProjects();
        return view('livewire.create-project',  compact('projectstatus'));
    }


}
