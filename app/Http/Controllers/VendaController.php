<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venda;
use App\Models\Usuario;
use App\Models\Bicicleta;
use App\Models\Acessorios;

class VendaController extends Controller
{
    public function index()
    {
        $dados = Venda::with(['usuario', 'funcionario', 'bicicleta', 'acessorio'])->get();

        return view('venda.list', compact('dados'));
    }

    public function create()
    {
        // Busca os usuários com tipo 'usuario' e tipo 'funcionario'
        $usuarios     = Usuario::where('tipo', 'usuario')->get();
        $funcionarios = Usuario::where('tipo', 'funcionario')->get();
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

    public function destroy($id)
    {
        Venda::destroy($id);

        return redirect()->route('venda.index')->with('success', 'Venda removida com sucesso!');
    }
}