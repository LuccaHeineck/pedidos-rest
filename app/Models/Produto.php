<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
	use HasFactory;

	protected $fillable = [
		'nome',
		'preco',
		'estoque',
		'categoria',
	];

	public function pedidoProdutos()
	{
		return $this->hasMany(OrderItem::class);
	}
}