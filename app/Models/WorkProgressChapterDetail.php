<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
