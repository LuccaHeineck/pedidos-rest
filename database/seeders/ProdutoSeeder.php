<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produto;

class ProdutoSeeder extends Seeder
{
	public function run(): void
	{
		Produto::insert([
			[
				'nome' => 'Notebook Dell',
				'preco' => 4500.00,
				'estoque' => 10,
				'categoria' => 'Eletrônicos',
			],
			[
				'nome' => 'Smartphone Samsung',
				'preco' => 2500.00,
				'estoque' => 15,
				'categoria' => 'Eletrônicos',
			],
			[
				'nome' => 'Cadeira Gamer',
				'preco' => 900.00,
				'estoque' => 5,
				'categoria' => 'Móveis',
			],
		]);
	}
}