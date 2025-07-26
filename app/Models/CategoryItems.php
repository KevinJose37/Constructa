<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryItems extends Model
{
    use HasFactory;
	protected $table = 'category_items';
	protected $fillable = ['prefix', 'description'];
	protected $primaryKey = 'id';

	public $timestamps = false;

	// Atributos
	protected function prefix(): Attribute
	{
		return Attribute::make(
			get: fn ($value) => strtoupper($value),
		);
	}

	public function items(){
		return $this->hasMany(Item::class, 'id_category');
	}

}
