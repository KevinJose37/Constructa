<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class weeklyProgresses extends Model
{
	use HasFactory;

	public $timestamps = false;

	protected $fillable = [
		'chapter_detail_id',
		'week_project_id',
		'executed_quantity',
		'executed_value',
		'executed_percentage'
	];

	public function detail()
	{
		return $this->belongsTo(WorkProgressChapterDetail::class, 'chapter_detail_id');
	}

	public function week()
	{
		return $this->belongsTo(WeekProject::class, 'week_project_id');
	}

	public static function booted()
	{
		// Cuando se actualiza o crea se actualiza el total de balance
		static::saving(function ($m) {
			if ($m->executed_quantity && $m->chapter_detail_id) {
				$detail = WorkProgressChapterDetail::find($m->chapter_detail_id);
				if ($detail) {
					$m->executed_value = $detail->unit_value * $m->executed_quantity;
					$m->executed_percentage = $m->executed_quantity > 0
						? round(($m->executed_value / $detail->partial_value) * 100, 2)
						: 0;
				}
			}
		});
	}

}
