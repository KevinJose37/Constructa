<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class WorkProgressChapterDetail extends Model
{
	use HasFactory;

	protected $table = 'work_progress_chapter_details';

	protected $fillable = [
		'chapter_id',
		'item',
		'description',
		'unit',
		'contracted_quantity',
		'unit_value',
		'partial_value',
		'balance_adjustment',
		'balance_quantity',
		'balance_value',
		'adjusted_quantity',
		'adjusted_value',
		'weekly_advance_quantity',
		'weekly_advance_value',
		'representation_percentage',
		'total_quantity',
		'remaining_balance',
		'executed_value',
		'executed_percentage',
	];


	protected $appends = [
		// Atributos de avance semanal
		'executed_quantity_sum',
		'executed_total_sum',
		'execute_percentage_sum',
		// Atributos de resumen
		'resume_quantity',
		'resume_value',
		'resume_execute_value',
		'resume_execute_percentage',
	];

	protected array $weekContext = [];

	public static function booted()
	{
		// Cuando se actualiza o crea se actualiza el total de balance
		static::saved(function ($m) {
			if ($m->balance_adjustment && $m->balance_quantity) {
				// Actualizamos el balance mayores y menores
				// [Cantidad balance * Valor unitario] esta es la fórmula XDD
				$m->balance_value = $m->unit_value * $m->balance_quantity;
				// Actualizamos las cantidades ajustadas al balance
				// [Cantidades contratadas + o - cantidades balance * valor unitario] fórmula tmb
				$m->adjusted_quantity = $m->contracted_quantity + ($m->balance_adjustment == 'up' ? $m->balance_quantity : -$m->balance_quantity);
				$m->adjusted_value = $m->adjusted_quantity * $m->unit_value;
			}
			$m->saveQuietly();
		});
	}

	// Relación con el modelo WorkProgressChapter
	public function chapter()
	{
		return $this->belongsTo(WorkProgressChapter::class, 'chapter_id');
	}

	public function weeklyProgresses()
	{
		return $this->hasMany(weeklyProgresses::class, 'chapter_detail_id');
	}

	public function weeklyProgressImages()
	{
		return $this->hasMany(WeeklyProgressImages::class, 'chapter_detail_id');
	}

	public function setWeekContext(array $weeks)
	{
		$this->weekContext = $weeks;
	}

	public function getExecutedQuantitySumAttribute()
	{
		if (!$this->relationLoaded('weeklyProgresses')) return null;

		return $this->weeklyProgresses
			->whereIn('week_project_id', $this->weekContext)
			->sum('executed_quantity');
	}

	public function getExecutedTotalSumAttribute()
	{
		if (!$this->relationLoaded('weeklyProgresses')) return null;

		return $this->weeklyProgresses
			->whereIn('week_project_id', $this->weekContext)
			->sum('executed_value');
	}

	// Este lo hizo chatgpt pq la vdd no le entendí a esteban XDD
	// Este hace la suma del porcentaje de ejecución pero por capítulo específico
	// No se necesitará así, pero lo dejo por si ps acaso
	// public function getExecutePercentageSumAttribute()
	// {
	// 	if (!$this->relationLoaded('weeklyProgresses')) return null;

	// 	$executedTotal = $this->executed_total_sum;
	// 	if ($executedTotal === null) return 0;

	// 	// Filtrar detalles con el mismo chapter_id y cargar sus progresses filtrados por semana
	// 	$relatedDetails = self::where('chapter_id', $this->chapter_id)
	// 		->with(['weeklyProgresses' => function ($q) {
	// 			$q->whereIn('week_project_id', $this->weekContext);
	// 		}])
	// 		->get();

	// 	// Sumar executed_total_sum de todos los detalles
	// 	$totalChapterSum = $relatedDetails->sum(function ($detail) {
	// 		$detail->setWeekContext($this->weekContext); // importante: pasar el contexto a cada uno
	// 		return $detail->executed_total_sum;
	// 	});

	// 	if (!$totalChapterSum) return 0;

	// 	// Log::debug('Calculating execute percentage', [
	// 	// 	'executed_total' => $executedTotal,
	// 	// 	'total_chapter_sum' => $totalChapterSum,
	// 	// ]);

	// 	return round(($executedTotal / $totalChapterSum) * 100, 2);
	// }

	// Para que funcione, siempre cargar la relación de esta manera:
	// with(['workProgressChapter.details.chapter'])

	// FÓRMULA: (Valor total ejecutado en la semana / Suma de valores totales ejecutados en las semanas del proyecto ) * 100

	public function getExecutePercentageSumAttribute()
	{
		if (!$this->relationLoaded('weeklyProgresses')) return null;

		$executedTotal = $this->executed_total_sum;
		Log::debug('ExecutedTotal', ['executed_total' => $executedTotal]);
		if ($executedTotal === null) return 0;

		if (!$this->relationLoaded('chapter')) {
			$this->load('chapter');
		}

		if (!$this->chapter) return 0;


		$projectId = $this->chapter->project_id;

		// Buscar todos los detalles cuyas chapters pertenecen a este project_id
		$relatedDetails = self::whereHas('chapter', function ($q) use ($projectId) {
			$q->where('project_id', $projectId);
		})
			->with(['weeklyProgresses' => function ($q) {
				$q->whereIn('week_project_id', $this->weekContext);
			}])
			->get();

		// Sumar los executed_total_sum de cada uno
		$totalProjectSum = $relatedDetails->sum(function ($detail) {
			$detail->setWeekContext($this->weekContext);
			return $detail->executed_total_sum;
		});

		Log::debug('Calculating execute percentage', [
			'executed_total' => $executedTotal,
			'total_project_sum' => $totalProjectSum,
		]);
		if (!$totalProjectSum) return 0;

		return round(($executedTotal / $totalProjectSum) * 100, 2);
	}


	// RESUMEN
	public function getResumeQuantityAttribute()
	{
		return $this->weeklyProgresses()->sum('executed_quantity');
	}

	public function getResumeValueAttribute()
	{
		return $this->weeklyProgresses()->sum('executed_value');
	}

	public function getResumeExecuteValueAttribute()
	{
		return $this->resume_value - $this->adjusted_value;
	}

	public function getResumeExecutePercentageAttribute()
	{
		if (!$this->adjusted_quantity || $this->adjusted_quantity == 0) {
			return 0;
		}

		return round(($this->resume_quantity / $this->adjusted_quantity) * 100, 2);
	}
}
