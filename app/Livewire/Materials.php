<?php

namespace App\Livewire;

use App\Models\Item;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CategoryItems;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class Materials extends Component
{
	use WithPagination;

	public $confirmingDelete = false;
	public $materialIdToDelete;
	public $materialName;
	public $materialUnit;
	public $materialCategory;
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

		$validator = Validator::make($this->all(), [
			'materialName' => 'required|string|max:255',
			'materialUnit' => 'required|string|max:10',
			'materialCategory' => 'required|exists:category_items,id',
		], [
			'materialName.required' => 'El nombre del material es requerido',
			'materialName.string' => 'El nombre del material debe ser una cadena de texto',
			'materialName.max' => 'El nombre del material debe tener menos de 255 caracteres',
			'materialUnit.required' => 'La unidad de medida es requerida',
			'materialUnit.string' => 'La unidad de medida debe ser una cadena de texto',
			'materialCategory.required' => 'La categoría es requerida',
			'materialCategory.exists' => 'La categoría seleccionada no es válida',
		]);

		if ($validator->fails()) {
			$this->addError('materialName', $validator->errors()->first('materialName'));
			$this->addError('materialUnit', $validator->errors()->first('materialUnit'));
			$this->addError('materialCategory', $validator->errors()->first('materialCategory'));
			return;
		}

		// Verificar si existe un material con el mismo nombre o código
		$existingMaterial = Item::where('name', $this->materialName)
			->where('id_category', $this->materialCategory)
			->first();

		if ($existingMaterial) {
			if ($existingMaterial->name === $this->materialName) {
				$this->addError('materialName', 'Ya existe un material con este nombre en la categoría seleccionada.');
				return;
			}
		}

		try {
			Item::create([
				'name' => $this->materialName,
				'unit_measurement' => $this->materialUnit,
				'id_category' => $this->materialCategory,
			]);

			$this->reset(['materialName', 'materialUnit', 'errorMessage', 'materialCategory']);
            $this->dispatch('alert', type: 'success', title: 'Material', message: 'Material creado exitosamente');
			$this->dispatch('close-modal', 'createMaterialModal');
		} catch (\Exception $e) {
			$this->errorMessage = 'Ocurrió un error al crear el material.';
		}
	}

	public function render()
	{
		$materials = Item::query()
			->when($this->search, function ($query) {
				$query->where(function ($q) {
					$q->where('name', 'like', '%' . $this->search . '%')
						->orWhere('cod', 'like', '%' . $this->search . '%')
						->orWhere('unit_measurement', 'like', '%' . $this->search . '%')
						->orWhereHas('categoryItems', function ($query) {
							$query->where('description', 'like', '%' . $this->search . '%');
						});
				});
			})
			->paginate(10);

		$categories = Cache::remember('categories', 180, function () {
			return CategoryItems::all();
		});

		return view('livewire.show-materials', compact('materials', 'categories'));
	}
}
