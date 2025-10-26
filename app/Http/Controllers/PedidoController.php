<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\PedidoProduto;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PedidoController extends Controller
{
	
	# Lista pedidos do usuário autenticado.	
	public function index()
	{
		$user = Auth::user();
		$pedidos = Pedido::where('user_id', $user->id)->with('produtos.produto')->paginate(10);
		return response()->json($pedidos);
	}

	
	# Cria um novo pedido.
	public function store(Request $request)
	{
		$validado = $request->validate([
			'produtos' => 'required|array|min:1',
			'produtos.*.id_produto' => 'required|integer|exists:produtos,id',
			'produtos.*.quantidade' => 'required|integer|min:1',
		]);

		$user = Auth::user();

		$pedido = DB::transaction(function () use ($validado, $user) {
			$pedido = pedido::create([
				'user_id' => $user->id,
				'preco_total' => 0,
			]);

			$total = 0;

			foreach ($validado['produtos'] as $itemData) {
				$produto = Produto::lockForUpdate()->find($itemData['id_produto']);

				if ($produto->estoque < $itemData['quantidade']) {
					throw ValidationException::withMessages([
						'estoque' => "Estoque insuficiente para o produto {$produto->nome}."
					]);
				}

				$produto->decrement('estoque', $itemData['quantidade']);

				$subtotal = $produto->preco * $itemData['quantidade'];
				$total += $subtotal;

				PedidoProduto::create([
					'id_pedido' => $pedido->id,
					'id_produto' => $produto->id,
					'quantidade' => $itemData['quantidade'],
					'preco_unidade' => $produto->preco,
					'subtotal' => $subtotal,
				]);
			}

			$pedido->update(['preco_total' => $total]);

			return $pedido->load('produtos.produto');
		});

		return response()->json($pedido, Response::HTTP_CREATED);
	}

	
	# Exibe detalhes de um pedido do usuário autenticado.
	public function show(Pedido $pedido)
	{
		$user = Auth::user();

		if ($pedido->user_id !== $user->id) {
			return response()->json(['message' => 'Acesso negado.'], Response::HTTP_FORBIDDEN);
		}

		return response()->json($pedido->load('produtos.produto'));
	}

	
	# Atualiza um pedido (apenas pelo criador e se não estiver cancelado).
	public function update(Request $request, Pedido $pedido)
	{
		$user = Auth::user();

		if ($pedido->user_id !== $user->id) {
			return response()->json(['message' => 'Acesso negado.'], Response::HTTP_FORBIDDEN);
		}

		if ($pedido->status === 'cancelled') {
			return response()->json(['message' => 'Pedido cancelado não pode ser editado.'], Response::HTTP_CONFLICT);
		}

		# Exemplo: permitir reedição de itens (simplificado)
		$validado = $request->validate([
			'status' => 'sometimes|in:pending,confirmed,cancelled'
		]);

		$pedido->update($validado);
		return response()->json($pedido);
	}

	
	# Cancela um pedido (muda status para 'cancelled').
	public function cancel(Pedido $pedido)
	{
		$user = Auth::user();

		if ($pedido->user_id !== $user->id) {
			return response()->json(['message' => 'Acesso negado.'], Response::HTTP_FORBIDDEN);
		}

		if ($pedido->status === 'cancelled') {
			return response()->json(['message' => 'Pedido já está cancelado.'], Response::HTTP_CONFLICT);
		}

		$pedido->update(['status' => 'cancelled']);

		return response()->json(['message' => 'Pedido cancelado com sucesso.']);
	}
}