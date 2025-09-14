<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetHeader extends Model
{
	use HasFactory;

	protected $table = 'budgets';
	protected $primaryKey = 'id_presupuesto';
	public $timestamps = false; // Si no estás usando timestamps en esta tabla

	protected $fillable = [
		'id_proyecto',       // ID del proyecto relacionado
		'descripcion_obra',  // Descripción de la obra
		'localizacion',      // Localización
		'fecha',             // Fecha
	];

	protected static function booted()
    {
        // Crear duplicado en real_projects al crear un presupuesto
        static::created(function ($budget) {
            $realProject = RealProject::create([
                'project_id'     => $budget->id_proyecto,
                'descripcion_obra'=> $budget->descripcion_obra,
                'localizacion'    => $budget->localizacion,
                'fecha'           => $budget->fecha
            ]);

            // Copiar todos los ítems
            foreach ($budget->items as $item) {
                RealProjectInfo::create([
    'real_project_id' => $realProject->id,
    'item_number'     => $item->numero_item,
    'description'     => $item->descripcion,
    'total'           => $item->vr_total ?? 0, // aseguramos que no sea null
]);
            }
        });

        // Actualizar duplicado en real_projects
        static::updated(function ($budget) {
            $realProject = RealProject::where('id', $budget->id_proyecto)->first();
            if ($realProject) {
                $realProject->update([
                    'descripcion_obra'=> $budget->descripcion_obra,
                    'localizacion'    => $budget->localizacion,
                    'fecha'           => $budget->fecha
                ]);

                // Sincronizar ítems
                RealProjectInfo::where('id_real_project', $realProject->id_real_project)->delete();
                foreach ($budget->items as $item) {
                    RealProjectInfo::create([
    'real_project_id' => $realProject->id,
    'item_number'     => $item->numero_item,
    'description'     => $item->descripcion,
    'total'           => $item->vr_total ?? 0, // aseguramos que no sea null
]);
                }
            }
        });

        // Eliminar duplicado en real_projects
        static::deleted(function ($budget) {
            $realProject = RealProject::where('id', $budget->id_proyecto)->first();
            if ($realProject) {
                $realProject->details()->delete();
                $realProject->delete();
            }
        });
    }
	public function chapters()
	{
		return $this->hasMany(Chapter::class, 'id_presupuesto');
	}

	public function items()
	{
		return $this->hasMany(ItemsBudgets::class, 'id_presupuesto');
	}
}
