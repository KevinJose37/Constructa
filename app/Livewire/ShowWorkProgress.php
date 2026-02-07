<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\WorkProgressChapter;
use Illuminate\Http\Request;

class ShowWorkProgress extends Component
{
    #[Layout('layouts.app')]
    #[Title('Avance de obra')]
    public $chapters = [];
    public $chapterName = '';
    public $projectId;

    // Inicializar con el project_id y cargar capítulos guardados
    public function mount($id)
    {
        $this->projectId = $id; // Asigna el ID del proyecto
    
        $this->chapters = WorkProgressChapter::where('project_id', $this->projectId)
            ->orderBy('chapter_number')
            ->get()
            ->map(function ($chapter) {
                return [
                    'chapter_number' => $chapter->chapter_number,
                    'chapter_name' => $chapter->chapter_name,
                ];
            })
            
            ->toArray();
            
    }


    public function createChapter()
    {
        $this->validate([
            'chapterName' => 'required|string|max:255',
        ]);

        $chapterNumber = count($this->chapters) + 1;

        // Guardar el capítulo en la base de datos
        $chapter = WorkProgressChapter::create([
            'project_id' => $this->projectId,
            'chapter_name' => $this->chapterName,
            'chapter_number' => $chapterNumber,
        ]);

        // Añadir el capítulo creado a la lista local
        $this->chapters[] = [
            'chapter_number' => $chapter->chapter_number,
            'chapter_name' => $chapter->chapter_name,
        ];

        $this->chapterName = ''; 
    }

    public function render()
    {
        return view('livewire.show-work-progress');
    }
}