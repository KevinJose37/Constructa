<?php

namespace App\Livewire;

use App\Livewire\Forms\UpdateProjectForm;
use App\Models\Project;
use Livewire\Component;
use App\Services\ProjectServices;

class UpdateProject extends Component
{
    public Project $projectUpdate;
    public UpdateProjectForm $formup;


    public function mount(Project $project){
        $this->projectUpdate = $project;
        $this->formup->name_project = $project->project_name;
        $this->formup->description_project = $project->project_description;
        $this->formup->status_project = $project->project_status_id;
        $this->formup->date_start_project = $project->project_start_date;
        $this->formup->date_end_project = $project->project_estimated_end;
    }

    public function edit(ProjectServices $projectServices){
        $this->formup->validate();
        $data['project_name'] = $this->formup->name_project;
        $data['project_description'] = $this->formup->description_project;
        $data['project_status_id'] = $this->formup->status_project;
        $data['project_start_date'] = $this->formup->date_start_project;
        $data['project_estimated_end'] = $this->formup->date_end_project;
        $responseSave = $projectServices->Update($this->projectUpdate->id, $data);
        if($responseSave){
            $this->formup->reset();
            $this->mount($projectServices->getById($this->projectUpdate->id));
            $this->dispatch('projectRefresh')->to(ShowProjects::class);
            $this->dispatch('alert', type: 'success', title: 'Proyectos',message: "Se editó correctamente el proyecto {$this->formup->name_project}");
            return;
        }


        $this->dispatch('alert', type: 'error', title: 'Proyectos', message: "Ocurrió un error al editar el proyecto {$this->formup->name_project}");
        
    }

    public function render(ProjectServices $projectServices)
    {
        $projectstatus = $projectServices->getStatusProjects();
        return view('livewire.update-project',  compact('projectstatus'));
    }
}
