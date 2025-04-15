<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

class Dashboard extends Component
{
    #[Layout('layouts.app')]
    #[Title('Dashboard')]

    public function render()
    {
        return view('livewire.dashboard');
    }
}
