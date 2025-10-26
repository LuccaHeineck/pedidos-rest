<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProdutoController extends Controller
{
    # Lista paginada de produtos
	public function index()
	{
		$produtos = Produto::paginate(10);
		return response()->json($produtos);
	}

	# Cria um novo produto
	public function store(Request $request)
	{
		$validado = $request->validate([
			'nome' => 'required|string|max:255',
			'preco' => 'required|numeric|min:0',
			'estoque' => 'required|integer|min:0',
			'categoria' => 'required|string|max:255',
		]);

		$produto = Produto::create($validado);
		return response()->json($produto, Response::HTTP_CREATED);
	}

	# Produto específico
	public function show(Produto $produto)
	{
		return response()->json($produto);
	}

	# Atualiza um produto existente
	public function update(Request $request, Produto $produto)
	{
		$validado = $request->validate([
			'nome' => 'sometimes|required|string|max:255',
			'preco' => 'sometimes|required|numeric|min:0',
			'estoque' => 'sometimes|required|integer|min:0',
			'categoria' => 'sometimes|required|string|max:255',
		]);

		$produto->update($validado);
		return response()->json($produto);
	}

	# Se não estiver em pedidos, exclui o produto
	public function destroy(Produto $produto)
	{
		if ($produto->orderItems()->exists()) {
			return response()->json([
				'message' => 'Não é possível excluir um produto que está em pedidos.'
			], Response::HTTP_CONFLICT);
		}

		$produto->delete();

		return response()->json([
			'message' => 'Produto excluído com sucesso.'
		]);
	}
}