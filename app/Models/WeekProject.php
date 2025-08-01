<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WeekProject extends Model
{
	use HasFactory;
	public $timestamps = false;

	protected $fillable = [
		'project_id',
		'start_date',
		'end_date',
		'number_week',
		'string_date'
	];

	protected $casts = [
		'start_date' => 'date',
		'end_date' => 'date'
	];

	public static function booted()
	{
		static::saving(function ($m) {
			if ($m->start_date && $m->end_date) {
				Carbon::setLocale('es');
				$from = Carbon::instance($m->start_date);
				$to   = Carbon::instance($m->end_date);
				$prettyFrom = ucfirst($from->translatedFormat('l d F, Y'));
				$prettyTo   = ucfirst($to->translatedFormat('l d F, Y'));

				$m->string_date = $prettyFrom . ' a ' . $prettyTo;
			}
		});
	}

	public function project()
	{
		return $this->belongsTo(Project::class, 'project_id');
	}

	public function weeklyProgresses()
{
    return $this->hasMany(weeklyProgresses::class, 'week_project_id');
}

}
