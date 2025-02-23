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
}
