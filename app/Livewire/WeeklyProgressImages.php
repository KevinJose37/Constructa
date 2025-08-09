<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\WeekProject;
use Livewire\WithFileUploads;
use App\Models\weeklyProgresses;
use Illuminate\Support\Facades\Storage;
use App\Models\WeeklyProgressImages as ModelsWeeklyProgressImages;

class WeeklyProgressImages extends Component
{

	use WithFileUploads;
	public bool $open = false;
	// Models
	public $detail;
	public $workProgress;
	public $weekModel = null;
	public $existImages;
	// Fields
	public $weeksSelect = [];
	public $altWeek = null; // Select de seleccionar semana
	public $mode = 'UPLOAD';
	public $canSave = false;
	public $filterWeeks = [];
	// Imágenes
	public $newImages = [];
	public $tempImages = [];
	public $savedImages = [];


	public function mount($detail, $workProgress, $filterWeeks = [])
	{
		$this->detail = $detail;
		$this->weeksSelect = \App\Models\WeekProject::where('project_id', $workProgress->project_id)
			->orderBy('number_week')
			->get();

		$this->workProgress = $workProgress;
		$this->filterWeeks = $filterWeeks;
		// Si hay más de una semana seleccionada
		if (is_array($filterWeeks) && count($filterWeeks) > 0) {
			$this->reloadWeek($filterWeeks);
			$this->mode = 'VIEW';
		}
	}

	protected function validProgressQuantityWeek($week)
	{
		$existQuantity = weeklyProgresses::where('chapter_detail_id', $this->detail->id)->where('week_project_id', $week)->first()?->executed_quantity;
		// Si la cantidad registrada no es mayor a 0, o sea <= 0
		if (!$existQuantity || !($existQuantity > 0)) {
			$this->dispatch('alert', type: 'error', title: 'Evidencia', message: "No hay evidencia registrada, seleccione una semana con avance superior a 0");
			$this->canSave = false;
			return;
		}

		$this->canSave = true;
	}

	public function reloadWeek($v)
	{
		$vArr = is_array($v) ? $v : [$v];

		$this->weekModel = WeekProject::find($v);

		// No agrupar aquí, solo obtener los modelos completos
		$this->savedImages = ModelsWeeklyProgressImages::with('week')
			->where('chapter_detail_id', $this->detail->id)
			->whereIn('week_project_id', $vArr)
			->get()
			->sortBy(fn($img) => $img->week->number_week);
	}



	// [IMAGES]

	public function updatedNewImages()
	{
		$this->validate([
			'newImages.*' => 'image|max:5120', // 5MB máx
		]);

		$this->tempImages = $this->newImages;
	}

	public function removeTempImage($index)
	{
		if (isset($this->tempImages[$index])) {
			unset($this->tempImages[$index]);
			$this->tempImages = array_values($this->tempImages); // Reindexar
		}
	}

	public function saveImages()
	{
		$this->validate([
			'tempImages.*' => 'image|max:5120',
		]);

		foreach ($this->tempImages as $image) {
			$path = $image->store("progress_images", "public");

			// Guarda en la BD (ajusta el modelo y relaciones según tu lógica)
			ModelsWeeklyProgressImages::create([
				'image_path' => $path,
				'chapter_detail_id' => $this->detail->id,
				'week_project_id' => $this->weekModel->id
			]);
		}

		$this->tempImages = [];
		$this->newImages = [];

		$this->reloadWeek($this->weekModel->id);
		$this->dispatch('alert', type: 'success', title: 'Evidencia', message: "Se cargó correctamente las imágenes");
	}

	public function deleteSavedImage($id)
	{
		$image = ModelsWeeklyProgressImages::findOrFail($id);
		Storage::disk('public')->delete($image->image_path);
		$image->delete();

		$this->reloadWeek($this->weekModel->id);
	}

	// [LISTENERS]
	public function updatedAltWeek($v)
	{
		if ($this->open) {
			$this->validProgressQuantityWeek($v);
			$this->reloadWeek($v);
		}
	}


	// public function closeModal()
	// {
	// 	$this->open = false;
	// 	// $this->altWeek = null;
	// 	// $this->weekModel = null;
	// 	// $this->reset(['mode', 'canSave', 'filterWeeks', 'newImages', 'tempImages', 'savedImages']);
	// 	$this->dispatch('$refresh');
	// }

	public function closeModal()
	{
		$this->open = false;

		$detail = $this->detail;
		$workProgress = $this->workProgress;
		$filterWeeks = $this->filterWeeks;

		// Reset completo
		$this->reset();
		$this->resetErrorBag();
		$this->resetValidation();
		// Re-ejecuta mount
		$this->mount($detail, $workProgress, $filterWeeks);
	}

	public function render()
	{
		return view('livewire.weekly-progress-images');
	}
}
