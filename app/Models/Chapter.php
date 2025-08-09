<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Chapter extends Model
{
    use HasFactory;

    protected $table = 'chapters';
    protected $primaryKey = 'id_capitulo';
    public $timestamps = false;

    protected $fillable = ['id_presupuesto', 'numero_capitulo', 'nombre_capitulo'];

    public function budget()
    {
        return $this->belongsTo(BudgetHeader::class, 'id_presupuesto');
    }

    public function items()
{
    return $this->hasMany(ItemsBudgets::class, 'id_capitulo');
}

    public function BudgetHeader()
    {
        return $this->belongsTo(BudgetHeader::class, 'id_presupuesto');
    }

    public function workProgressChapter()
    {
        return $this->hasOne(WorkProgressChapter::class, 'id_capitulo', 'id_capitulo');
    }
    protected static function booted()
    {
        // Usar deleting para poder acceder a relaciones antes del borrado
        static::deleting(function ($chapter) {
            Log::info("Chapter::deleting id_capitulo={$chapter->id_capitulo}");

            // 1) Borrar items uno a uno para que se disparen sus events (ItemsBudgets::deleted)
            foreach ($chapter->items as $item) {
                Log::info(" -> Deleting item id {$item->id_item_budget} (numero_item={$item->numero_item})");
                $item->delete();
            }

            // 2) Intentar localizar el RealProject asociado (primario: id_capitulo)
            $realProject = \App\Models\RealProject::where('id_capitulo', $chapter->id_capitulo)->first();

            // Fallback: buscar por project_id + chapter_number
            if (! $realProject) {
                $budgetHeader = \App\Models\BudgetHeader::find($chapter->id_presupuesto);
                $projectId = $budgetHeader?->id_proyecto ?? null;
                $realProject = \App\Models\RealProject::where('project_id', $projectId)
                                ->where('chapter_number', $chapter->numero_capitulo)
                                ->first();
            }

            if ($realProject) {
                $rpKey = $realProject->getKey();
                Log::info(" -> Found RealProject (pk={$realProject->getKeyName()}={$rpKey}). Deleting its infos...");

                // Borrar filas en RealProjectInfo soportando ambos nombres de FK
                \App\Models\RealProjectInfo::where('real_project_id', $rpKey)->delete();


                // Finalmente borrar el realProject
                $realProject->delete();
                Log::info(" -> RealProject deleted (pk={$rpKey})");
            } else {
                Log::info(" -> No se encontró RealProject para id_capitulo={$chapter->id_capitulo}");
            }
        });
    }
}
