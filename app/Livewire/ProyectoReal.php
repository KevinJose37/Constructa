<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\RealProjectInfo;
use App\Models\Project;
use App\Models\RealProject;
use App\Models\Chapter;
use App\Models\MaterialRedirections;
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
    // Variables para edición
    public $editingChapter = null;
    public $editingItem = null;
    public $isEditing = false;
    public $editCapitulo = [
        'id_capitulo' => '',
        'numero_capitulo' => '',
        'nombre_capitulo' => '',
    ];
    public $editItems = [];
    // Ver items por item
    public $currentItemsRedirect = [];

    public function mount(int $id)
    {
        $this->project = Project::findOrFail($id);
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
            // 'items.*.total' => 'required|numeric',
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
                'total' => 0,
            ]);
        }

        // Reiniciar campos
        $this->reset(['chapter_number', 'chapter_name', 'items']);
        $this->addItem();

        // Emitir evento para actualizar tabla
        // $this->dispatch('chapterSaved');
            $this->dispatch('alert', type: 'success', title: 'Proyecto real', message: 'Capítulo e ítems creados correctamente.');

        // Cerrar el modal
        $this->dispatch('close-modal', 'chapterModal');
    }

    public function deleteChapter($chapterId)
    {
        $chapter = RealProject::findOrFail($chapterId);
        $chapter->items()->delete(); // Elimina los ítems asociados
        $chapter->delete(); // Elimina el capítulo

        $this->dispatch('chapterDeleted');
    }

    // Funciones para edición
    public function editChapter($id_capitulo)
    {
        $chapter = RealProject::with('items')->findOrFail($id_capitulo);

        $this->isEditing = true;
        $this->editingChapter = $id_capitulo;

        $this->editCapitulo = [
            'id_capitulo' => $chapter->id,
            'numero_capitulo' => $chapter->chapter_number,
            'nombre_capitulo' => $chapter->chapter_name,
        ];

        $this->editItems = [];
        foreach ($chapter->items as $current) {
            $this->editItems[] = [
                'id' => $current->id,
                'item_number' => $current->item_number,
                'description' => $current->description,
                'total' => 0,
            ];
        }

        $this->dispatch('open-modal', 'editChapterModal');
    }

    public function updateChapter()
    {
        $this->validate([
            'editCapitulo.numero_capitulo' => 'required|string',
            'editCapitulo.nombre_capitulo' => 'required|string',
            'editItems.*.item_number' => 'required|string',
            'editItems.*.description' => 'required|string',
            // 'editItems.*.total' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Actualizar el capítulo
            $chapter = RealProject::findOrFail($this->editCapitulo['id_capitulo']);
            $chapter->update([
                'chapter_number' => $this->editCapitulo['numero_capitulo'],
                'chapter_name' => $this->editCapitulo['nombre_capitulo'],
            ]);

            // Eliminar ítems existentes
            RealProjectInfo::where('real_project_id', $chapter->id)->delete();


            // Crear/actualizar ítems
            foreach ($this->editItems as $itemData) {
                RealProjectInfo::create([
                    'real_project_id' => $chapter->id,
                    'item_number' => $itemData['item_number'],
                    'description' => $itemData['description'],
                    'total' => 0,
                ]);
            }

            DB::commit();

            $this->resetEditForm();
            $this->dispatch('close-modal', 'editChapterModal');
            $this->dispatch('alert', type: 'success', title: 'Proyecto real', message: 'Capítulo e ítems actualizados correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('alert', type: 'error', title: 'Proyecto real', message: 'Error al actualizar capítulo e ítems: ' . $e->getMessage());
        }
    }

    public function removeEditItem($index)
    {
        unset($this->editItems[$index]);
        $this->editItems = array_values($this->editItems);
    }

    public function addEditItem()
    {
        $newItem = [
            'id_item' => null,
            'item_number' => '',
            'description' => '',
            'total' => '',
        ];

        if (!is_array($this->editItems)) {
            $this->editItems = [];
        }

        $this->editItems[] = $newItem;
    }

    public function cancelEdit()
    {
        $this->resetEditForm();
        $this->dispatch('close-modal', 'editChapterModal');
    }

    private function resetEditForm()
    {
        $this->isEditing = false;
        $this->editingChapter = null;
        $this->editCapitulo = [
            'id_capitulo' => '',
            'numero_capitulo' => '',
            'nombre_capitulo' => '',
        ];
        $this->editItems = [];
    }

    // Ver items redireccionados

    public function viewInfoItem($itemId, $chapterId)
    {
        $this->currentItemsRedirect = MaterialRedirections::with(['invoiceDetail', 'invoiceDetail.item'])
            ->where('chapter_id', $chapterId)
            ->where('item_id', $itemId)
            ->get();

        // dd($this->currentItemsRedirect);

        if (!$this->currentItemsRedirect && $this->currentItemsRedirect->isEmpty()) {
            $this->dispatch('alert', [
                'type' => 'info',
                'title' => 'Proyecto real',
                'message' => 'Este Item aún no tiene materiales direccionados'
            ]);
            return;
        }

        $this->dispatch('open-modal', 'itemsModal');
    }


    public function render()
    {
        $chapters = RealProject::where('project_id', $this->project->id)
            ->with('items')
            ->get();

        return view('livewire.proyecto-real', compact('chapters'));
    }
}
