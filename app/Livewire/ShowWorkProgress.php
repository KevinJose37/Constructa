<?php
namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\Chapter;

class ShowWorkProgress extends Component
{
    #[Layout('layouts.app')]
    #[Title('Avance de obra')]
    public $chapters = [];
    public $projectId;

    public function mount($id)
{
    $this->projectId = $id;

    // Cargar todos los capítulos con sus avances y detalles
    $this->chapters = Chapter::with('workProgressChapter.details')
        ->where('id_presupuesto', $id)
        ->orderBy('numero_capitulo') // si tienes este campo
        ->get();
}

    public function render()
    {
        return view('livewire.show-work-progress');
    }
}
