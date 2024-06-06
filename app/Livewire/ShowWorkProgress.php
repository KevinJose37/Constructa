<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

class ShowWorkProgress extends Component
{

    #[Layout('layouts.app')]
    #[Title('Avance de obra')]
    public function render()
    {
        return view('livewire.show-work-progress');
    }
}
