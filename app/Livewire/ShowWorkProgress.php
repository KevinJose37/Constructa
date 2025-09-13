<?php

namespace App\Livewire;

use App\Models\Chapter;
use Livewire\Component;
use App\Models\WeekProject;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ChapterWorkProgressExport;

class ShowWorkProgress extends Component
{
	#[Layout('layouts.app')]
	#[Title('Avance de obra')]
	public $chapters = [];
	public $originalChapters;
	public $projectId;
	public $project;
	public $weeks = [];
	public array $filterWeeks = [];
	public $selectedWeek = null;


	public function mount($id)
	{
		$this->projectId = $id;
		$this->project = \App\Models\Project::find($id);


		// Cargar todos los capítulos con sus avances y detalles
		$this->chapters = Chapter::with('workProgressChapter.details', 'workProgressChapter.details.weeklyProgresses')
			->whereHas('BudgetHeader', function ($query) use ($id) {
				$query->where('id_proyecto', $id);
			})
			->orderBy('numero_capitulo')
			->get();


		$this->originalChapters = $this->chapters;

		$this->weeks = \App\Models\WeekProject::where('project_id', $id)
			->orderBy('number_week')
			->get();
	}

	public function refilterWeeks()
	{
		if (!$this->filterWeeks) {
			$this->reloadData();
			return;
		}

		$this->chapters = Chapter::whereHas('BudgetHeader', function ($query) {
			$query->where('id_proyecto', $this->projectId);
		})
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

	public function reloadData()
	{
		$this->mount($this->projectId);
	}

	public function exportExcel()
	{
		$this->refilterWeeks();

		return Excel::download(
			new ChapterWorkProgressExport($this->chapters, $this->filterWeeks),
			'avance_obra.xlsx'
		);
	}

	protected function getListeners()
	{
		return [
			'workProgressUpdate' => '$refresh',
			'weeklyProgressCreated' => 'refilterWeeks',
			'createWeek' => 'reloadData'
		];
	}


	public function render()
	{
		return view('livewire.show-work-progress');
	}
}
