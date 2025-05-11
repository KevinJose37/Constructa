<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\RealProjectInfo;
use App\Models\Project;
use App\Models\RealProject;
use App\Models\Chapter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\WithFileUploads;


#[Layout('layouts.app')] 
#[Title('Proyecto Real')]
class ProyectoReal extends Component
{
    use WithFileUploads;

    public Project $project;

    // Campos del capítulo
    public $chapter_number;
    public $chapter_name;

    // Lista de ítems
    public $items = [];

    public function mount(int $id)
    {
        Log::info("ID recibido en mount:", ['id' => $id]);
        $this->project = Project::findOrFail($id);

        // Iniciar con un ítem vacío
        $this->addItem();
    }

    public function addItem()
    {
        $this->items[] = [
            'item_number' => '',
            'description' => '',
            'total' => '',
        ];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }
    public function saveChapter()
{
    // Validar campos
    $this->validate([
        'chapter_number' => 'required|string',
        'chapter_name' => 'required|string',
        'items' => 'required|array|min:1',
        'items.*.item_number' => 'required|string',
        'items.*.description' => 'required|string',
        'items.*.total' => 'required|numeric',
    ]);

    // Crear capítulo
    $chapter = RealProject::create([
        'project_id' => $this->project->id,
        'chapter_number' => $this->chapter_number,
        'chapter_name' => $this->chapter_name,
    ]);

    // Guardar cada ítem
    foreach ($this->items as $item) {
        $chapter->items()->create([
            'item_number' => $item['item_number'],
            'description' => $item['description'],
            'total' => $item['total'],
        ]);
    }

    // Reiniciar campos
    $this->reset(['chapter_number', 'chapter_name', 'items']);
    $this->addItem();

    // Emitir evento para actualizar tabla
    $this->dispatch('chapterSaved');

    // Cerrar el modal
    $this->dispatch('close-modal');
}

public function deleteChapter($chapterId)
{
    $chapter = RealProject::findOrFail($chapterId);
    $chapter->items()->delete(); // Elimina los ítems asociados
    $chapter->delete(); // Elimina el capítulo

    $this->dispatch('chapterDeleted');
}

    public function render()
    {
        $chapters = RealProject::where('project_id', $this->project->id)
                        ->with('items')
                        ->get();

        return view('livewire.proyecto-real', compact('chapters'));
    }
}