<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\PedidoController;

Route::get('/ping', fn() => response()->json(['message' => 'API OK']));

Route::apiResource('produtos', ProdutoController::class);

Route::middleware('auth:sanctum')->group(function () {
	Route::apiResource('pedidos', PedidoController::class)->except(['destroy']);
	Route::post('pedidos/{pedido}/cancel', [PedidoController::class, 'cancel']);
});

use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);