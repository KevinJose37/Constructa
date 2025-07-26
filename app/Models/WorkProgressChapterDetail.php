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

    // Relación con el modelo WorkProgressChapter
    public function chapter()
    {
        return $this->belongsTo(WorkProgressChapter::class, 'chapter_id');
    }
}
