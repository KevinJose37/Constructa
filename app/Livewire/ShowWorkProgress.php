<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\Chapter;

class ShowWorkProgress extends Component
{
	#[Layout('layouts.app')]
	#[Title('Avance de obra')]
	public $chapters = [];
	public $originalChapters;
	public $projectId;
	public $weeks = [];
	public array $filterWeeks = [];
	public $selectedWeek = null;


	public function mount($id)
	{
		$this->projectId = $id;

		// Cargar todos los capítulos con sus avances y detalles
		$this->chapters = Chapter::with('workProgressChapter.details', 'workProgressChapter.details.weeklyProgresses')
			->where('id_presupuesto', $id)
			->orderBy('numero_capitulo') // si tienes este campo
			->get();

		$this->originalChapters = $this->chapters;

		$this->weeks = \App\Models\WeekProject::where('project_id', $id)
			->orderBy('start_date')
			->get();
	}

	public function refilterWeeks()
	{
		if (!$this->filterWeeks) {
			$this->mount($this->projectId);
			return;
		}

		$this->chapters = Chapter::where('id_presupuesto', $this->projectId)
			->with([
				'workProgressChapter.details',
				'workProgressChapter.details.weeklyProgresses' => function ($q) {
					$q->whereIn('week_project_id', $this->filterWeeks);
				},
			])
			->orderBy('numero_capitulo')
			->get();

		foreach ($this->chapters as $chapter) {
			foreach ($chapter->workProgressChapter->details as $detail) {
				$detail->setWeekContext($this->filterWeeks);
			}
		}
	}


	public function updatedSelectedWeek($value)
	{
		if ($value && !in_array($value, $this->filterWeeks)) {
			$this->filterWeeks[] = $value;
			$this->refilterWeeks();
		}

		$this->selectedWeek = null; // limpiar select
	}

	public function removeWeek($id)
	{
		$this->filterWeeks = array_filter($this->filterWeeks, fn($weekId) => $weekId != $id);
		$this->refilterWeeks();
	}


	protected function getListeners()
	{
		return [
			'workProgressUpdate' => '$refresh',
			'weeklyProgressCreated' => 'refilterWeeks',
		];
	}

	public function render()
	{
		return view('livewire.show-work-progress');
	}
}
