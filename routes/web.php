<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\BicicletaController;
use App\Http\Controllers\AcessoriosController;
use App\Http\Controllers\VendaController;
use App\Http\Controllers\AcessoriosSegurancaController;
use App\Http\Controllers\FornecedorController;

Route::get('/', function () {return redirect()->route('login');
});

Route::get('/login', function () { return view('auth.login');})->name('login');


Route::post('/login', function () {return redirect()->route('bicicleta.index');})->name('login.post');

Route::get('/register', function () {return view('auth.register');})->name('register');

Route::post('/register', function () {return redirect()->route('login')->with('success', 'Cadastro realizado com sucesso! Faça login.');})->name('register.post');


Route::get('/fornecedor/report', [FornecedorController::class, 'chart'])->name('fornecedor.report');
Route::get('/fornecedor/chart', [FornecedorController::class, 'chart'])->name('fornecedor.chart');

// Rotas de Busca
Route::post('usuario/search', [UsuarioController::class, 'index'])->name('usuario.search');
Route::post('bicicleta/search', [BicicletaController::class, 'index'])->name('bicicleta.search');
Route::post('acessorios/search', [AcessoriosController::class, 'index'])->name('acessorios.search');
Route::post('venda/search', [VendaController::class, 'index'])->name('venda.search');
Route::post('acessoriosSeguranca/search', [\App\Http\Controllers\AcessoriosSegurancaController::class, 'index'])->name('acessoriosSeguranca.search');
Route::post('fornecedor/search', [FornecedorController::class, 'index'])->name('fornecedor.search');

// Rotas CRUD (Resource)
Route::resource('usuario', UsuarioController::class)->names('usuario');
Route::resource('bicicleta', BicicletaController::class)->names('bicicleta');
Route::resource('acessorios', AcessoriosController::class)->names('acessorios');
Route::resource('venda', VendaController::class)->names('venda');
Route::resource('acessoriosSeguranca', \App\Http\Controllers\AcessoriosSegurancaController::class)->names('acessoriosSeguranca');
Route::resource('fornecedor', FornecedorController::class)->names('fornecedor');

Route::get('/venda/{id}/itens', [VendaController::class, 'showItens'])->name('venda.itens');