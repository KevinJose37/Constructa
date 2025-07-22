<?php

namespace App\Livewire;

use App\Models\Item;
use Livewire\Component;
use App\Models\CategoryItems;
use Illuminate\Support\Facades\Cache;

class CreateMaterialModal extends Component
{
	public $materialName = '';
	public $materialUnit = '';
	public $materialCategory = '';
	public $errorMessage = '';
	public $categories = [];

	protected $rules = [
		'materialName' => 'required|string|max:255',
		'materialUnit' => 'required|string|max:255',
		'materialCategory' => 'required|exists:category_items,id',
	];

	protected $messages = [
		'materialName.required' => 'El nombre del material es requerido',
		'materialName.string' => 'El nombre del material debe ser una cadena de texto',
		'materialName.max' => 'El nombre del material debe tener menos de 255 caracteres',
		'materialUnit.required' => 'La unidad de medida es requerida',
		'materialUnit.string' => 'La unidad de medida debe ser una cadena de texto',
		'materialCategory.required' => 'La categoría es requerida',
		'materialCategory.exists' => 'La categoría seleccionada no es válida',
	];

	public function mount()
	{
		$this->categories = Cache::remember('categories', 180, function () {
			return CategoryItems::all();
		});
	}

	public function createMaterial()
	{
		$this->validate();
		try {
			Item::create([
				'name' => $this->materialName,
				'unit_measurement' => $this->materialUnit,
				'id_category' => $this->materialCategory,
			]);
			$this->reset(['materialName', 'materialUnit', 'materialCategory']);
			$this->dispatch('close-modal', 'createMaterialModal');
			$this->dispatch('alert', type: 'success', title: 'Materiales', message: "Se creó correctamente el material");

			$this->dispatch('materialCreated');
		} catch (\Exception $e) {
			$this->errorMessage = 'Error al crear el material: ' . $e->getMessage();
		}
	}

	public function render()
	{
		return view('livewire.create-material-modal');
	}
}
