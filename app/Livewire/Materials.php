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
	public $search = '';
	public $errorMessage = '';

	#[Layout('layouts.app')]
	#[Title('Materiales')]

	protected $paginationTheme = 'bootstrap';

	protected function getListeners()
	{
		return [
			'search' => 'performSearch',
			'materialCreated' => '$refresh', // Refresca la lista cuando se crea un material
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


		return view('livewire.show-materials', compact('materials'));
	}
}
