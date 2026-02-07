<?php

namespace App\Livewire\Forms;

use App\Models\Project;
use Livewire\Form;
use Livewire\Attributes\On;
use App\Services\ProjectServices;
use Livewire\Attributes\Validate;

class ViewUsersProjectForm extends Form
{
    
    public $open = false;
    public $user_select = "-";

}
