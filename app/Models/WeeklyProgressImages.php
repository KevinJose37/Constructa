<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyProgressImages extends Model
{
	use HasFactory;
	public $table = 'weekly_progress_images';
	public $timestamps = true;
	public $softDeletes = true;

	protected $fillable = [
		'chapter_detail_id',
		'week_project_id',
		'image_path',
	];

	public function detail()
	{
		return $this->belongsTo(WorkProgressChapterDetail::class, 'chapter_detail_id');
	}

	public function week()
	{
		return $this->belongsTo(WeekProject::class, 'week_project_id');
	}
}
