<?php

namespace App\Livewire;

use Exception;
use Carbon\Carbon;
use App\Models\Project;
use Livewire\Component;
use App\Models\WeekProject;
use Livewire\Attributes\On;

class WorkProgressWeek extends Component
{

	public $open = false;
	public $project;

	public $weekDate;
	public $numberWeek;

	public function mount($projectId)
	{
		$this->project = Project::find($projectId)->with('weekProjects')->first();
		if (!$this->project) {
			$this->dispatch('alert', type: 'error', title: 'Error', message: 'Proyecto no encontrado.');
			return;
		}
	}


	public function render()
	{
		return view('livewire.work-progress-week');
	}

	public function createWeek()
	{
		$this->validate([
			'numberWeek' => 'required|numeric|min:0',
			'weekDate' => 'required',
		], [
			'numberWeek.required' => 'El número de semanas es requerida.',
			'numberWeek.numeric' => 'El número de semanas debe ser un número.',
			'numberWeek.min' => 'El número de semanas no puede ser negativa.',
			'weekDate.required' => 'El rango de fechas es requerida.',
		]);

		try {
			list($startDate, $endDate) = array_map('trim', explode('a', $this->weekDate));

			$existDates = $this->validateDates($startDate, $endDate, $this->project->id);
			if ($existDates) {
				$this->dispatch('alert', type: 'error', title: 'Error', message: 'Ya existe una semana con estas fechas.');
				return;
			}

			try {
				$start = \Carbon\Carbon::createFromFormat('d-m-Y', $startDate);
				$end = \Carbon\Carbon::createFromFormat('d-m-Y', $endDate);
			} catch (\Exception $e) {
				throw new \Exception('Las fechas deben tener el formato correcto (d-m-Y).');
			}

			if ($start->diffInDays($end) > 7) {
				$this->dispatch('alert', type: 'error', title: 'Error', message: 'Los días máximos a seleccionar son (7)');
				return;
			}

			$existWeekNumber = $this->project->weekProjects()
				->where('number_week', $this->numberWeek)
				->exists();
			if ($existWeekNumber) {
				$this->dispatch('alert', type: 'error', title: 'Error', message: 'Ya existe una semana con este número.');
				return;
			}

			$this->project->weekProjects()->create([
				'start_date'  => $startDate,
				'end_date'    => $endDate,
				'number_week' => $this->numberWeek,
			]);

			$this->dispatch('alert', type: 'success', title: 'Éxito', message: 'Semana de avance creada exitosamente.');
			$this->reset(['weekDate', 'numberWeek']);
			$this->dispatch('createWeek')->to(ShowWorkProgress::class);
		} catch (Exception $th) {
			$this->dispatch('alert', type: 'error', title: 'Éxito', message: 'Ocurrió un error al crear la semana de avance: ' . $th->getMessage());
		}
	}

	protected function validateDates($initDate, $endDate, $projectId)
	{
		$initData = Carbon::parse($initDate);
		$endData = Carbon::parse($endDate);
		return WeekProject::where('project_id', $projectId)
			->where('start_date', '<=', $endData)
			->where('end_date', '>=', $initData)
			->exists();
	}

	#[On('destroy-week-project')]
	public function destroy($id)
	{
		$deleteWeek = WeekProject::find($id)?->delete();
		if ($deleteWeek === true) {
			$this->dispatch('alert', type: 'success', title: 'Éxito', message: "Se eliminó correctamente la semana de avance");
			$this->dispatch('createWeek')->to(ShowWorkProgress::class);
			return;
		}

		$this->dispatch('alert', type: 'error', title: 'Error', message: "Ocurrió un error al eliminar la semana de avance.");
	}

	public function destroyAlert($id, $nameWeek)
	{
		$this->dispatch(
			'alertConfirmation',
			id: $id,
			type: 'warning',
			title: 'Semana avance de obra',
			message: "¿estás seguro de eliminar la semana {$nameWeek}?",
			emit: 'destroy-week-project',
		);
	}
}
