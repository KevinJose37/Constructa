<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemsBudgets extends Model
{
    use HasFactory;

    protected $table = 'items_budget'; // Nombre correcto de la tabla en la base de datos

    protected $primaryKey = 'id_item_budget'; // Nombre de la clave primaria

    protected $fillable = ['id_capitulo', 'numero_item', 'descripcion', 'und', 'cantidad', 'vr_unit', 'vr_total'];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class, 'id_capitulo');
    }
    // Aquí vamos a enganchar los eventos
    
protected static function booted()
{
    static::created(function ($item) {
        $item->syncToRealProjects();
    });

    static::updated(function ($item) {
        $item->syncToRealProjects();
    });

    static::deleted(function ($item) {
        $item->deleteFromRealProjects();
    });
}

public function syncToRealProjects()
{
    $chapter = $this->chapter;
    if (! $chapter) return;

    // obtener project_id desde el presupuesto vinculado
    $budgetHeader = \App\Models\BudgetHeader::find($chapter->id_presupuesto);
    $projectId = $budgetHeader?->id_proyecto ?? null;

    // buscar por id_capitulo (en real_projects añadiste id_capitulo)
    $realProject = \App\Models\RealProject::where('id_capitulo', $chapter->id_capitulo)->first();

    if (! $realProject) {
        $realProject = \App\Models\RealProject::create([
            'project_id'     => $projectId,
            'id_capitulo'    => $chapter->id_capitulo,
            'chapter_number' => $chapter->numero_capitulo,
            'chapter_name'   => $chapter->nombre_capitulo,
        ]);
    }

    \App\Models\RealProjectInfo::updateOrCreate(
        [
            'real_project_id' => $realProject->id,
            'item_number'     => $this->numero_item,
        ],
        [
            'description' => $this->descripcion,
            'total'       => $this->vr_total ?? 0,
        ]
    );
}

public function deleteFromRealProjects()
{
    $chapter = $this->chapter;
    if (! $chapter) return;

    // buscar real project por id_capitulo como enlace estable
    $realProject = \App\Models\RealProject::where('id_capitulo', $chapter->id_capitulo)->first();

    if (! $realProject) {
        // fallback: buscar por project_id + chapter_number
        $budgetHeader = \App\Models\BudgetHeader::find($chapter->id_presupuesto);
        $projectId = $budgetHeader?->id_proyecto ?? null;
        $realProject = \App\Models\RealProject::where('project_id', $projectId)
                        ->where('chapter_number', $chapter->numero_capitulo)
                        ->first();
    }

    if (! $realProject) return;

    \App\Models\RealProjectInfo::where('real_project_id', $realProject->id)
        ->where('item_number', $this->numero_item)
        ->delete();
}

}
