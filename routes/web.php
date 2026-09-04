<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\BicicletaController;
use App\Http\Controllers\AcessoriosController;
use App\Http\Controllers\VendaController;

Route::get('/', function () {
    return redirect()->route('bicicleta.index');
});

// Rotas de Busca
Route::post('usuario/search', [UsuarioController::class, 'search'])->name('usuario.search');
Route::post('bicicleta/search', [BicicletaController::class, 'search'])->name('bicicleta.search');
Route::post('acessorios/search', [AcessoriosController::class, 'search'])->name('acessorios.search');

// Rotas CRUD
Route::resource('usuario', UsuarioController::class)->names('usuario');
Route::resource('bicicleta', BicicletaController::class)->names('bicicleta');
Route::resource('acessorios', AcessoriosController::class)->names('acessorios');
Route::resource('venda', VendaController::class)->names('venda');