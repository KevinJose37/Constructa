<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "projects";

    protected $fillable = [
        'project_name',
        'project_description',
        'project_status_id',
        'project_start_date',
        'project_estimated_end',
        'nit',
        'contratista',
        'entidad_contratante',  // Nuevo campo
        'contract_number'      // Nuevo campo
    ];

    public function projectStatus()
    {
        return $this->belongsTo(ProjectStatus::class, 'project_status_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'participants_project', 'project_id', 'user_id')->withTimestamps();
    }

    public function invoiceHeaders()
    {
        return $this->hasMany(InvoiceHeader::class);
    }

    public function budget()
{
    return $this->hasOne(BudgetHeader::class, 'id_proyecto', 'id');
}
}
