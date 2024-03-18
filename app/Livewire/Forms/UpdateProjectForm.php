<?php

namespace App\Livewire\Forms;

use Illuminate\Validation\Rule as ValidationRule;
use Livewire\Form;
use Livewire\Attributes\Validate;

class UpdateProjectForm extends Form
{
    public $open = false;

    #[Validate('required', message: 'Ingrese un valor para el nombre del proyecto')]
    #[Validate('min:3', message: 'El nombre es demasiado corto')]
    public $name_project = "";

    #[Validate('required', message: 'Ingrese un valor para la descripción del proyecto')]
    #[Validate('min:20', message: 'La descripción es demasiado corta')]
    public $description_project = "";

    #[Validate('required', message: 'Seleccione un estado')]
    public $status_project = "";

    #[Validate('required', message: 'La fecha es obligatoria')]
    #[Validate('date', message: 'Ingrese una fecha de inicio válida')]
    public $date_start_project = "";

    #[Validate('required', message: 'La fecha es obligatoria')]
    #[Validate('date', message: 'Ingrese una fecha de finalización válida')]
    #[Validate('after:date_start_project', message: 'La fecha de finalización debe ser superior a la fecha de inicio')]
    public $date_end_project = "";


    public function rules()
    {
        return [
            'status_project' => ValidationRule::exists('project_statuses', 'id')
        ];
    }
}
