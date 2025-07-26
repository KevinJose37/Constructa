<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkProgressChapter extends Model
{
    use HasFactory;

    protected $table = 'work_progress_chapters';
    public $timestamps = false;

    protected $fillable = [
        'project_id',
        'invoice_detail_id',
        'chapter_name',
        'chapter_number',
        'id_capitulo',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function invoiceHeader()
    {
        return $this->belongsTo(InvoiceHeader::class, 'id_purchase_order', 'id');
    }

    public function invoiceDetail()
    {
        return $this->belongsTo(InvoiceDetail::class, 'invoice_detail_id');
    }
    public function details()
    {
        return $this->hasMany(WorkProgressChapterDetail::class, 'chapter_id');
    }
    public function chapter()
    {
        return $this->belongsTo(Chapter::class, 'id_capitulo', 'id_capitulo');
    }
}

