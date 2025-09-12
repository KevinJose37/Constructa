<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\Project;

class Dashboard extends Component
{
    #[Layout('layouts.app')]
    #[Title('Dashboard')]

    public $selectedProjectId = '';
    public $projects = [];

    public function mount()
    {
        $this->projects = Project::all();
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
