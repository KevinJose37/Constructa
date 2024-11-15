<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Item;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

class Materials extends Component
{
    use WithPagination;

    public $confirmingDelete = false;
    public $materialIdToDelete;
    public $materialName;
    public $materialCode;
    public $materialUnit;
    public $search = '';
    public $errorMessage = '';

    #[Layout('layouts.app')]
    #[Title('Materiales')]

    protected $paginationTheme = 'bootstrap';

    protected function getListeners()
    {
        return [
            'search' => 'performSearch'
        ];
    }

    public function performSearch()
    {
        $this->resetPage();
    }

    public function updated($field)
    {
        if ($field === 'search') {
            $this->resetPage();
        }
    }

    public function clearSearch()
    {
        $this->reset('search');
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->materialIdToDelete = $id;
        $this->confirmingDelete = true;
    }

    public function deleteMaterial($id)
    {
        Item::findOrFail($id)->delete();
        $this->resetPage();
    }

    public function createMaterial()
    {
        $this->validate([
            'materialName' => 'required|string|max:255',
            'materialCode' => 'required|string|max:10',
            'materialUnit' => 'required|string|max:10',
        ]);

        // Verificar si existe un material con el mismo nombre o código
        $existingMaterial = Item::where('name', $this->materialName)
            ->orWhere('cod', $this->materialCode)
            ->first();

        if ($existingMaterial) {
            if ($existingMaterial->name === $this->materialName) {
                $this->addError('materialName', 'Ya existe un material con este nombre.');
                return;
            }
            if ($existingMaterial->cod === $this->materialCode) {
                $this->addError('materialCode', 'Ya existe un material con este código.');
                return;
            }
        }

        try {
            Item::create([
                'name' => $this->materialName,
                'cod' => $this->materialCode,
                'unit_measurement' => $this->materialUnit,
            ]);

            $this->reset(['materialName', 'materialCode', 'materialUnit', 'errorMessage']);
            $this->dispatch('material-created', ['message' => 'Material creado exitosamente']);
            $this->dispatch('close-modal');
        } catch (\Exception $e) {
            $this->errorMessage = 'Ocurrió un error al crear el material.';
        }
    }

    public function render()
    {
        $materials = Item::query()
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('cod', 'like', '%' . $this->search . '%')
                      ->orWhere('unit_measurement', 'like', '%' . $this->search . '%');
                });
            })
            ->paginate(10);

        return view('livewire.show-materials', compact('materials'));
    }
}