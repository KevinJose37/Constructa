<?php

namespace App\Livewire;

use Exception;
use Livewire\Component;
use App\Models\WeekProject;
use App\Models\weeklyProgresses;
use App\Services\ProjectChatLogger;
use App\Models\Week;

class ProgressWeek extends Component
{
	public $open = false;
	// Info
	public $detail;
	public $workProgress;
	public $weeksSelect = [];
	// Models
	public $quantity;
	public $week = null;
	public $altWeek = null;
	public $weekModel = null;

	public function mount($detail, $workProgress, $week = null)
	{
		$this->detail = $detail;
		$this->workProgress = $workProgress;
		$this->week = $week;

		if ($week && (is_array($week) && count($week) == 1)) {
			$this->weekModel = WeekProject::find($this->week[array_key_first($this->week)]);

			$weekId = $this->week[array_key_first($this->week)];
			$existingProgress = weeklyProgresses::where('chapter_detail_id', $this->detail->id)
				->where('week_project_id', $weekId)
				->first();

			if ($existingProgress && $existingProgress->executed_quantity) {
				$this->quantity = intval($existingProgress->executed_quantity);
			}
		}

		$this->weeksSelect = \App\Models\WeekProject::where('project_id', $workProgress->project_id)
			->orderBy('start_date')
			->get();
	}

	public function createAdvance()
	{
		$weekId = ($this->altWeek) ?  $this->altWeek :  $this->week[array_key_first($this->week)];

		$this->validate([
			'quantity' => 'required|numeric|min:0',
		], [
			'quantity.required' => 'La cantidad es requerida.',
			'quantity.numeric' => 'La cantidad debe ser un número.',
			'quantity.min' => 'La cantidad no puede ser negativa.',
		]);

		try {
			// Buscar si ya existe un avance previo para saber si es actualización o creación
			$existing = weeklyProgresses::where('chapter_detail_id', $this->detail->id)
				->where('week_project_id', $weekId)
				->first();

			$progress = weeklyProgresses::updateOrCreate(
				[
					'chapter_detail_id' => $this->detail->id,
					'week_project_id' => $weekId
				],
				[
					'executed_quantity' => $this->quantity
				]
			);

			// Obtener la semana
			$weekModel = WeekProject::find($weekId);

			// Construir el mensaje en primera persona
			$mensaje = $existing
				? 'actualicé el avance de obra de "' . $this->detail->item_description . '"'
				: 'registré un avance de obra para "' . $this->detail->item_description . '"';

			$mensaje .= ' con una cantidad ejecutada de ' . $this->quantity . ' unidades';
			if ($weekModel) {
				$mensaje .= ' en la semana "' . $weekModel->week_name . '"';
			}
			$mensaje .= '.';

			// Registrar en el chat del proyecto
			ProjectChatLogger::log($this->workProgress->project_id, $mensaje);

			$this->reset(['quantity']);
			$this->dispatch('weeklyProgressCreated');
			$this->dispatch('alert', type: 'success', title: 'Avance de obra', message: 'Se realizó el avance de obra');
		} catch (Exception $e) {
			$this->dispatch('alert', type: 'error', title: 'Avance de obra', message: "Ocurrió un error al realizar el avance de obra: {$e->getMessage()}");
		} finally {
			$this->open = false;
		}
	}

	public function render()
	{
		$this->weeksSelect = \App\Models\WeekProject::where('project_id', $this->workProgress->project_id)
			->orderBy('start_date')
			->get();

		return view('livewire.progress-week');
	}
}
