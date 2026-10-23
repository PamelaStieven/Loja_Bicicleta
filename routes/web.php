<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\BicicletaController;
use App\Http\Controllers\AcessoriosController;
use App\Http\Controllers\VendaController;
use App\Http\Controllers\FiliaisController;
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\EstoqueController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function () {
    return redirect()->route('bicicleta.index');
})->name('login.post');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', function () {
    return redirect()->route('login')->with('success', 'Cadastro realizado com sucesso! Faça login.');
})->name('register.post');

// Rotas de Relatórios e Gráficos
Route::get('/fornecedor/report', [FornecedorController::class, 'report'])->name('fornecedor.report');
Route::get('/fornecedor/chart', [FornecedorController::class, 'chart'])->name('fornecedor.chart');

Route::get('/venda/report', [VendaController::class, 'report'])->name('venda.report');
Route::get('/venda/chart', [VendaController::class, 'chart'])->name('venda.chart');
Route::get('/venda/{id}/itens', [VendaController::class, 'show'])->name('venda.itens');
Route::get('/estoque/chart', [EstoqueController::class, 'chart'])->name('estoque.chart');

// Rotas de Pesquisa
Route::post('usuario/search', [UsuarioController::class, 'search'])->name('usuario.search');
Route::post('bicicleta/search', [BicicletaController::class, 'search'])->name('bicicleta.search');
Route::post('acessorios/search', [AcessoriosController::class, 'search'])->name('acessorios.search');
Route::post('venda/search', [VendaController::class, 'search'])->name('venda.search');
Route::post('filiais/search', [FiliaisController::class, 'search'])->name('filiais.search');
Route::post('fornecedor/search', [FornecedorController::class, 'search'])->name('fornecedor.search');
Route ::post('estoque/search', [EstoqueController::class, 'search'])->name('estoque.search');


Route::resource('usuario', UsuarioController::class)->names('usuario');
Route::resource('bicicleta', BicicletaController::class);
Route::resource('acessorios', AcessoriosController::class)->names('acessorios');
Route::resource('venda', VendaController::class)->names('venda');
Route::resource('filiais', FiliaisController::class)->names('filiais');
Route::resource('fornecedor', FornecedorController::class)->names('fornecedor');
Route::resource('estoque', EstoqueController::class)->names('estoque');