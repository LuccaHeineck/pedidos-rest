<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
	use HasFactory;

	protected $fillable = [
		'user_id',
		'preco_total',
		'status',
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function produtos()
	{
		return $this->hasMany(PedidoPoduto::class);
	}
}