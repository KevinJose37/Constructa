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
use App\Services\ProjectChatLogger;

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
	public $totalItemsRedirect = 0;

	public $totalesPorItem = [];
	public $totalGeneral = 0;
	public $porcentajePorItem = [];

	public function mount(int $id)
	{
		$this->project = Project::findOrFail($id);
		$this->addItem();
		$this->loadItemsRedirect(); 
		$this->loadTotals();
	}

	public function loadTotals()
	{
		$this->totalesPorItem = MaterialRedirections::select(
				'material_redirections.item_id',
				DB::raw('SUM(invoice_details.total_price_iva) as total')
			)
			->join('invoice_details', 'invoice_details.id', '=', 'material_redirections.invoice_detail_id')
			->groupBy('material_redirections.item_id')
			->get()
			->pluck('total', 'item_id');

		$this->totalGeneral = $this->totalesPorItem->sum();

		$totalGeneral = $this->totalGeneral;

		$this->porcentajePorItem = $this->totalesPorItem->mapWithKeys(function ($valor, $itemId) use ($totalGeneral) {
			$porcentaje = $totalGeneral > 0 ? ($valor / $totalGeneral) * 100 : 0;
			return [$itemId => round($porcentaje, 2)];
		});
		
		logger('TOTALES POR ITEM:', $this->totalesPorItem->toArray());
		logger('PORCENTAJE POR ITEM:', $this->porcentajePorItem->toArray());
	}

	public function loadItemsRedirect()
	{
		$this->currentItemsRedirect = MaterialRedirections::with(['invoiceDetail', 'invoiceDetail.item'])->get();

		$this->totalItemsRedirect = collect($this->currentItemsRedirect)->sum(function ($item) {
			return $item->invoiceDetail->total_price_iva ?? 0;
		});
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
		$this->validate([
			'chapter_number' => 'required|string',
			'chapter_name' => 'required|string',
			'items' => 'required|array|min:1',
			'items.*.item_number' => 'required|string',
			'items.*.description' => 'required|string',
			'items.*.umbral_fisico' => 'required|numeric|min:0',
			'items.*.umbral_financiero' => 'required|numeric|min:0',
		]);

		// Crear capítulo
		$chapter = RealProject::create([
			'project_id' => $this->project->id,
			'chapter_number' => $this->chapter_number,
			'chapter_name' => $this->chapter_name,
		]);

		// Guardar ítems
		foreach ($this->items as $item) {
			$chapter->items()->create([
				'item_number' => $item['item_number'],
				'description' => $item['description'],
				'umbral_fisico' => $item['umbral_fisico'],
				'umbral_financiero' => $item['umbral_financiero'],
				'total' => 0,
			]);
		}

		// Registrar log
		ProjectChatLogger::log(
			$this->project->id,
			'creé un nuevo capítulo (' . $this->chapter_number . ') llamado "' . $this->chapter_name . '" en el proyecto real, con ' . count($this->items) . ' ítems asociados.'
		);

		$this->reset(['chapter_number', 'chapter_name', 'items']);
		$this->addItem();

		$this->dispatch('alert', type: 'success', title: 'Proyecto real', message: 'Capítulo e ítems creados correctamente.');
		$this->dispatch('close-modal', 'chapterModal');
	}

	public function deleteChapter($chapterId)
	{
		$chapter = RealProject::findOrFail($chapterId);
		$chapterName = $chapter->chapter_name;
		$chapterNumber = $chapter->chapter_number;

		$chapter->items()->delete();
		$chapter->delete();

		// Registrar log
		ProjectChatLogger::log(
			$this->project->id,
			'eliminé el capítulo (' . $chapterNumber . ') llamado "' . $chapterName . '" del proyecto real.'
		);

		$this->dispatch('chapterDeleted');
	}

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
				'umbral_fisico' => number_format($current->umbral_fisico),
				'umbral_financiero' => number_format($current->umbral_financiero),
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
			'editItems.*.umbral_fisico' => 'required|numeric|min:0',
			'editItems.*.umbral_financiero' => 'required|numeric|min:0',
		], [
			'editCapitulo.numero_capitulo.required' => 'El número de capítulo es obligatorio.',
			'editCapitulo.numero_capitulo.string' => 'El número de capítulo debe ser un texto.',
			'editCapitulo.nombre_capitulo.required' => 'El nombre del capítulo es obligatorio.',
			'editCapitulo.nombre_capitulo.string' => 'El nombre del capítulo debe ser un texto.',
			'editItems.*.item_number.required' => 'El número de ítem es obligatorio.',
			'editItems.*.item_number.string' => 'El número de ítem debe ser un texto.',
			'editItems.*.description.required' => 'La descripción del ítem es obligatoria.',
			'editItems.*.description.string' => 'La descripción del ítem debe ser un texto.',
			'editItems.*.umbral_fisico.required' => 'La unidad física del ítem es obligatoria.',
			'editItems.*.umbral_fisico.numeric' => 'La unidad física del ítem debe ser numérica.',
			'editItems.*.umbral_fisico.min' => 'La unidad física del ítem no puede ser negativa.',
			'editItems.*.umbral_financiero.required' => 'La unidad financiera del ítem es obligatoria.',
			'editItems.*.umbral_financiero.numeric' => 'La unidad financiera del ítem debe ser numérica.',
			'editItems.*.umbral_financiero.min' => 'La unidad financiera del ítem no puede ser negativa.',
		]);

		try {
			DB::beginTransaction();

			$chapter = RealProject::findOrFail($this->editCapitulo['id_capitulo']);
			$chapter->update([
				'chapter_number' => $this->editCapitulo['numero_capitulo'],
				'chapter_name' => $this->editCapitulo['nombre_capitulo'],
			]);

			RealProjectInfo::where('real_project_id', $chapter->id)->delete();

			foreach ($this->editItems as $itemData) {
				RealProjectInfo::create([
					'real_project_id' => $chapter->id,
					'item_number' => $itemData['item_number'],
					'description' => $itemData['description'],
					'umbral_fisico' => $itemData['umbral_fisico'],
					'umbral_financiero' => $itemData['umbral_financiero'],
					'total' => 0,
				]);
			}

			DB::commit();

			// Registrar log
			ProjectChatLogger::log(
				$this->project->id,
				'actualicé el capítulo (' . $this->editCapitulo['numero_capitulo'] . ') llamado "' . $this->editCapitulo['nombre_capitulo'] . '" y sus ítems asociados en el proyecto real.'
			);

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

	public function viewInfoItem($itemId, $chapterId)
	{
		$this->currentItemsRedirect = MaterialRedirections::with(['invoiceDetail', 'invoiceDetail.item'])
			->where('chapter_id', $chapterId)
			->where('item_id', $itemId)
			->has('invoiceDetail')
			->get();

		$this->totalItemsRedirect = $this->currentItemsRedirect->sum(function ($item) {
			return $item->invoiceDetail->total_price_iva ?? 0;
		});

		if ($this->currentItemsRedirect->isEmpty()) {
			$this->dispatch('alert', type: 'error', title: 'Proyecto real', message: 'No hay items redireccionados para este ítem.');
			return;
		}

		$this->dispatch('open-modal', 'itemsModal');
	}

	public function render()
	{
		$chapters = RealProject::where('project_id', $this->project->id)
			->with('items')
			->with('workProgressChapters.details')
			->get();

		return view('livewire.proyecto-real', compact('chapters'));
	}
}
