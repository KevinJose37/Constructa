<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\BudgetHeader;
use App\Models\Project;
use App\Models\ItemsBudgets;
use App\Models\Chapter;
use App\Models\WorkProgressChapter;
use App\Models\WorkProgressChapterDetail;
use Illuminate\Support\Facades\DB;


class Budget extends Component
{
	#[Layout('layouts.app')]
	#[Title('Presupuesto')]

	public $budget;
	public $localizacion;
	public $modalCapitulo = [
		'numero_capitulo' => '',
		'nombre_capitulo' => '',
	];
	public $modalItems = [];

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


	public function mount($id_presupuesto)
	{
		$this->budget = BudgetHeader::where('id_proyecto', $id_presupuesto)->first();

		if (!$this->budget) {
			$project = Project::findOrFail($id_presupuesto);
			$this->budget = BudgetHeader::create([
				'id_proyecto' => $project->id,
				'descripcion_obra' => $project->project_description,
				'localizacion' => 'Por definir',
				'fecha' => $project->project_start_date,
			]);
		}

		$this->localizacion = $this->budget->localizacion;
	}

	public function updateLocalizacion()
	{
		// Validar el campo de localización
		$this->validate([
			'localizacion' => 'required|string|max:255',
		]);

		// Actualizar la localización en la base de datos
		$this->budget->update([
			'localizacion' => $this->localizacion,
		]);

		// Cerrar el modal y mostrar un mensaje de éxito
		$this->dispatch('close-modal', 'localizacionModal');
		$this->dispatch('alert', type: 'success', title: 'Presupuesto', message: 'Localización actualizada correctamente.');
	}

	public function addItem()
	{
		$newItem = [
			'numero_item' => '',
			'descripcion' => '',
			'und' => '',
			'cantidad' => '',
			'vr_unit' => '',
		];

		if (!is_array($this->modalItems)) {
			$this->modalItems = [];
		}

		$this->modalItems[] = $newItem;
	}

	public function removeItem($index)
	{
		unset($this->modalItems[$index]);
		$this->modalItems = array_values($this->modalItems);
	}

	public function saveChapter()
	{
		$this->validate([
			'modalCapitulo.numero_capitulo' => 'required|string',
			'modalCapitulo.nombre_capitulo' => 'required|string',
			'modalItems.*.numero_item' => 'required|string',
			'modalItems.*.descripcion' => 'required|string',
			'modalItems.*.und' => 'required|string',
			'modalItems.*.cantidad' => 'required|numeric|min:0',
			'modalItems.*.vr_unit' => 'required|numeric|min:0',
		]);
		try {
			DB::beginTransaction();

			$chapter = Chapter::create([
				'id_presupuesto' => $this->budget->id_presupuesto,
				'numero_capitulo' => $this->modalCapitulo['numero_capitulo'],
				'nombre_capitulo' => $this->modalCapitulo['nombre_capitulo'],
			]);

			$progressChapter = WorkProgressChapter::create([
				'project_id' => $this->budget->id_proyecto,
				'chapter_number' => $chapter->numero_capitulo,
				'chapter_name' => $chapter->nombre_capitulo,
				'id_capitulo' => $chapter->id_capitulo, // la clave de enlace
			]);


			foreach ($this->modalItems as $item) {
				ItemsBudgets::create([
					'id_capitulo' => $chapter->id_capitulo,
					'numero_item' => $item['numero_item'],
					'descripcion' => $item['descripcion'],
					'und' => $item['und'],
					'cantidad' => $item['cantidad'],
					'vr_unit' => $item['vr_unit'],
					'vr_total' => round($item['cantidad'] * $item['vr_unit']),

				]);
				WorkProgressChapterDetail::create([
					'chapter_id' => $progressChapter->id, // capítulo del avance
					'item' => $item['numero_item'],
					'description' => $item['descripcion'],
					'unit' => $item['und'],
					'contracted_quantity' => $item['cantidad'],
					'unit_value' => $item['vr_unit'],
					'partial_value' => round($item['cantidad'] * $item['vr_unit']),
				]);
			}

			DB::commit();

			$this->modalCapitulo = [
				'numero_capitulo' => '',
				'nombre_capitulo' => '',
			];
			$this->modalItems = [];

			$this->dispatch('close-modal', 'chapterModal');
			$this->dispatch('alert', type: 'success', title: 'Presupuesto', message: 'Capítulo e ítems guardados correctamente.');
		} catch (\Exception $e) {
			DB::rollBack();
			$this->dispatch('alert', type: 'error', title: 'Presupuesto', message: 'Error al guardar capítulo e ítems: ' . $e->getMessage());
		}
	}

	public function deleteChapter($id_capitulo)
	{
		try {
			DB::beginTransaction();

			// Eliminar ítems del presupuesto
			ItemsBudgets::where('id_capitulo', $id_capitulo)->delete();

			// Buscar el capítulo de avance de obra asociado
			$progressChapter = WorkProgressChapter::where('id_capitulo', $id_capitulo)->first();

			if ($progressChapter) {
				// Eliminar detalles de avance de obra
				WorkProgressChapterDetail::where('chapter_id', $progressChapter->id)->delete();

				// Eliminar capítulo de avance de obra
				$progressChapter->delete();
			}

			// Eliminar capítulo de presupuesto
			$chapter = Chapter::find($id_capitulo);
			if ($chapter) {
				$chapter->delete(); // Aquí sí se disparan los eventos y cascadas
			}

			DB::commit();
			$this->dispatch('alert', type: 'success', title: 'Presupuesto', message: 'Capítulo e ítems eliminados correctamente.');
		} catch (\Exception $e) {
			DB::rollBack();
			$this->dispatch('alert', type: 'error', title: 'Presupuesto', message: 'Error al eliminar: ' . $e->getMessage());
		}
	}

	// Funciones para edición
	public function editChapter($id_capitulo)
	{
		$chapter = Chapter::with('items')->findOrFail($id_capitulo);

		$this->isEditing = true;
		$this->editingChapter = $id_capitulo;

		$this->editCapitulo = [
			'id_capitulo' => $chapter->id_capitulo,
			'numero_capitulo' => $chapter->numero_capitulo,
			'nombre_capitulo' => $chapter->nombre_capitulo,
		];


		$this->editItems = [];
		foreach ($chapter->items as $item) {
			$this->editItems[] = [
				'id_item' => $item->id_item,
				'numero_item' => $item->numero_item,
				'descripcion' => $item->descripcion,
				'und' => $item->und,
				'cantidad' => $item->cantidad,
				'vr_unit' => $item->vr_unit,
			];
		}

		$this->dispatch('open-modal', 'editChapterModal');
	}

	public function addEditItem()
	{
		$newItem = [
			'id_item' => null,
			'numero_item' => '',
			'descripcion' => '',
			'und' => '',
			'cantidad' => '',
			'vr_unit' => '',
		];

		if (!is_array($this->editItems)) {
			$this->editItems = [];
		}

		$this->editItems[] = $newItem;
	}

	public function removeEditItem($index)
	{
		unset($this->editItems[$index]);
		$this->editItems = array_values($this->editItems);
	}

	public function updateChapter()
	{
		$this->validate([
			'editCapitulo.numero_capitulo' => 'required|string',
			'editCapitulo.nombre_capitulo' => 'required|string',
			'editItems.*.numero_item' => 'required|string',
			'editItems.*.descripcion' => 'required|string',
			'editItems.*.und' => 'required|string',
			'editItems.*.cantidad' => 'required|numeric|min:0',
			'editItems.*.vr_unit' => 'required|numeric|min:0',
		]);

		try {
			DB::beginTransaction();

			// Actualizar el capítulo
			$chapter = Chapter::findOrFail($this->editCapitulo['id_capitulo']);
			$chapter->update([
				'numero_capitulo' => $this->editCapitulo['numero_capitulo'],
				'nombre_capitulo' => $this->editCapitulo['nombre_capitulo'],
			]);

			// Eliminar ítems existentes
			$items = ItemsBudgets::where('id_capitulo', $chapter->id_capitulo)->get();
			foreach ($items as $item) {
				$item->delete(); // Aquí sí se dispara el evento deleted
			}

			// Crear/actualizar ítems
			foreach ($this->editItems as $itemData) {
				ItemsBudgets::create([
					'id_capitulo' => $chapter->id_capitulo,
					'numero_item' => $itemData['numero_item'],
					'descripcion' => $itemData['descripcion'],
					'und' => $itemData['und'],
					'cantidad' => $itemData['cantidad'],
					'vr_unit' => $itemData['vr_unit'],
					'vr_total' => round($itemData['cantidad'] * $itemData['vr_unit']),
				]);
			}

			DB::commit();
			$chapter->update([
				'numero_capitulo' => $this->editCapitulo['numero_capitulo'],
				'nombre_capitulo' => $this->editCapitulo['nombre_capitulo'],
			]);

			// Actualizar capítulo en avance de obra
			$progressChapter = WorkProgressChapter::where('id_capitulo', $chapter->id_capitulo)->first();
			if ($progressChapter) {
				$progressChapter->update([
					'chapter_number' => $this->editCapitulo['numero_capitulo'],
					'chapter_name' => $this->editCapitulo['nombre_capitulo'],
				]);

				// Eliminar detalles actuales
				WorkProgressChapterDetail::where('chapter_id', $progressChapter->id)->delete();

				// Crear nuevos detalles con los ítems editados
				foreach ($this->editItems as $itemData) {
					WorkProgressChapterDetail::create([
						'chapter_id' => $progressChapter->id,
						'item' => $itemData['numero_item'],
						'description' => $itemData['descripcion'],
						'unit' => $itemData['und'],
						'contracted_quantity' => $itemData['cantidad'],
						'unit_value' => $itemData['vr_unit'],
						'partial_value' => round($itemData['cantidad'] * $itemData['vr_unit']),
					]);
				}
			}

			$this->resetEditForm();
			$this->dispatch('close-modal', 'editChapterModal');
			$this->dispatch('alert', type: 'success', title: 'Presupuesto', message: 'Capítulo e ítems actualizados correctamente.');
		} catch (\Exception $e) {
			DB::rollBack();
			$this->dispatch('alert', type: 'error', title: 'Presupuesto', message: 'Error al actualizar capítulo e ítems: ' . $e->getMessage());
		}
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

	public function render()
	{
		return view('livewire.budget', [
			'budget' => $this->budget,
			'capitulos' => Chapter::where('id_presupuesto', $this->budget->id_presupuesto)
				->orderBy('numero_capitulo', 'asc')
				->with('items')
				->get()
		]);
	}
}
