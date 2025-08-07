<?php

namespace App\Livewire;

use App\Models\Chapter;
use Livewire\Component;
use App\Models\WeekProject;
use Livewire\Attributes\Layout;

class WorkProgressReport extends Component
{
	public $projectId;
	public array $filterWeeks = [];

	public $chapters = [];
	public $weeks = [];

	public function mount($projectId)
	{
		$this->projectId = $projectId;
		$this->filterWeeks = request()->query('filterWeeks', []);

		$this->weeks = WeekProject::where('project_id', $this->projectId)
			->orderBy('number_week')
			->get();

		$this->chapters = Chapter::where('id_presupuesto', $this->projectId)
			->with([
				'workProgressChapter.details.weeklyProgresses' => function ($q) {
					if ($this->filterWeeks) {
						$q->whereIn('week_project_id', $this->filterWeeks);
					}
				},
				// Precarga las imágenes filtradas por semana
				'workProgressChapter.details.weeklyProgressImages' => function ($q) {
					if (!empty($this->filterWeeks)) {
						$q->whereIn('week_project_id', $this->filterWeeks);
					}
					$q->with('week');
				}
			])
			->orderBy('numero_capitulo')
			->get();

		foreach ($this->chapters as $chapter) {
			if (!$chapter->workProgressChapter) continue;

			foreach ($chapter->workProgressChapter->details as $detail) {
				$detail->setWeekContext($this->filterWeeks);
			}
		}
	}


	#[Layout('layouts.app')]
	public function render()
	{
		return view('livewire.work-progress-report');
	}
}
