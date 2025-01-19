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

    public function chapters()
{
    return $this->hasMany(Chapter::class, 'id_presupuesto');
}

public function items()
{
    return $this->hasMany(ItemsBudgets::class, 'id_presupuesto');
}

}
