<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_capitulo';
    protected $fillable = ['id_presupuesto', 'numero_capitulo', 'nombre_capitulo'];

    public function items()
{
    return $this->hasMany(ItemsBudgets::class, 'id_capitulo');
}
}