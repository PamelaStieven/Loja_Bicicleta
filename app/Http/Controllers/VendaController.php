<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venda;
use App\Models\Usuario;
use App\Models\Bicicleta;
use App\Models\Acessorios;

class VendaController extends Controller
{
   public function index(Request $request)
    {
        $query = Venda::with(['usuario', 'funcionario', 'bicicleta', 'acessorio']);

        $tipo  = $request->input('tipo');
        $valor = $request->input('valor');

        if ($tipo && $valor) {
            switch ($tipo) {
                case 'usuario':
                    $query->whereHas('usuario', function ($q) use ($valor) {
                        $q->where('nome', 'like', "%{$valor}%"); // Removido o orWhere('name')
                    });
                    break;

                case 'funcionario':
                    $query->whereHas('funcionario', function ($q) use ($valor) {
                        $q->where('nome', 'like', "%{$valor}%"); // Removido o orWhere('name')
                    });
                    break;

                case 'item':
                    $query->where(function ($q) use ($valor) {
                        $q->whereHas('bicicleta', function ($b) use ($valor) {
                            $b->where('modelo', 'like', "%{$valor}%")
                            ->orWhere('marca', 'like', "%{$valor}%");
                        })->orWhereHas('acessorio', function ($a) use ($valor) {
                            $a->where('nome', 'like', "%{$valor}%");
                        });
                    });
                    break;

                case 'quantidade':
                    $query->where('quantidade', $valor);
                    break;

                case 'data':
                    $query->whereDate('data_venda', $valor);
                    break;
            }
        }

        $dados = $query->get();

        return view('venda.list', compact('dados'));
    }

    public function create()
    {
        $usuarios     = Usuario::all();
        $funcionarios = Usuario::all();
        $bicicletas   = Bicicleta::all();
        $acessorios   = Acessorios::all();

        return view('venda.form', compact('usuarios', 'funcionarios', 'bicicletas', 'acessorios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'usuario_id'     => 'required|exists:usuario,id',
            'funcionario_id' => 'required|exists:usuario,id',
            'valor_total'    => 'required|numeric',
            'data_venda'     => 'required|date',
            'quantidade'     => 'required|integer|min:1',
        ], [
            'usuario_id.required'     => 'Selecione o usuário.',
            'funcionario_id.required' => 'Selecione o funcionário.',
            'valor_total.required'    => 'Informe o valor total.',
            'data_venda.required'     => 'Informe a data da venda.',
        ]);

        Venda::create($request->all());

        return redirect()->route('venda.index')->with('success', 'Venda registrada com sucesso!');
    }

    public function edit($id)
    {
        // Renomeado para $venda para bater com a view form.blade.php
        $venda        = Venda::findOrFail($id); 
        $usuarios     = Usuario::all();
        $funcionarios = Usuario::all();
        $bicicletas   = Bicicleta::all();
        $acessorios   = Acessorios::all();

        return view('venda.form', compact('venda', 'usuarios', 'funcionarios', 'bicicletas', 'acessorios'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'usuario_id'     => 'required|exists:usuario,id',
            'funcionario_id' => 'required|exists:usuario,id',
            'valor_total'    => 'required|numeric',
            'data_venda'     => 'required|date',
            'quantidade'     => 'required|integer|min:1',
        ], [
            'usuario_id.required'     => 'Selecione o usuário.',
            'funcionario_id.required' => 'Selecione o funcionário.',
            'valor_total.required'    => 'Informe o valor total.',
            'data_venda.required'     => 'Informe a data da venda.',
        ]);

        $venda = Venda::findOrFail($id);
        $venda->update($request->all());

        return redirect()->route('venda.index')->with('success', 'Venda atualizada com sucesso!');
    }

    public function destroy($id)
    {
        Venda::destroy($id);

        return redirect()->route('venda.index')->with('success', 'Venda removida com sucesso!');
    }
}