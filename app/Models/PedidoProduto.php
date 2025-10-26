<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoProduto extends Model
{
	use HasFactory;

	protected $fillable = [
		'id_pedido',
		'id_produto',
		'quantidade',
		'preco_unidade',
		'subtotal',
	];

	public function pedido()
	{
		return $this->belongsTo(Pedido::class);
	}

	public function produto()
	{
		return $this->belongsTo(Produto::class);
	}
}