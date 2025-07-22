<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
	use HasFactory;
	protected $table = 'items';
	public $timestamps = false;

	protected $fillable = [
		'name',
		'cod',
		'unit_measurement',
		'id_category'
	];

	public static function booted()
	{
		static::creating(function ($m) {
			if ($m->id_category) {
				DB::transaction(function () use ($m) {
					// Bloqueamos la fila de la categoría mientras trabajamos con ella 
					// porque si hay concurrencia, paila
					$category = CategoryItems::where('id', $m->id_category)
						->lockForUpdate()
						->first();

					// Calculamos el nuevo valor
					$nextCount = $category->last_value + 1;

					// Generamos el código único
					$m->cod = $category->prefix . $nextCount;

					// Actualizamos el contador en la categoría
					$category->last_value = $nextCount;
					$category->save();
				});
				return;
			}
			$m->cod = '';
		});
	}



	public function invoiceDetails()
	{
		return $this->hasMany(InvoiceDetail::class, 'id_item');
	}

	public function categoryItems()
	{
		return $this->belongsTo(CategoryItems::class, 'id_category');
	}
}
