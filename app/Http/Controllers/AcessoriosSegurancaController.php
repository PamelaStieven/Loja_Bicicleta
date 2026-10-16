<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcessoriosSeguranca;

class AcessoriosSegurancaController extends Controller
{
    public function index(Request $request)
    {
        $query = AcessoriosSeguranca::query();

        if ($request->filled('tipo') && $request->filled('valor')) {
            $query->where($request->tipo, 'like', '%' . $request->valor . '%');
        }

        $dados = $query->get();

        return view('acessoriosSeguranca.list', compact('dados'));
    }

    public function create()
    {
        return view('acessoriosSeguranca.form');
    }

    private function validateForm(Request $request)
    {
        $request->validate([
            'nome'      => 'required',
            'marca'     => 'required',
            'tamanho'   => 'required',
            'preco'     => 'required|numeric',
            'quantidade' => 'required|integer|min:0',
            'estoque'   => 'required|integer|min:0',
        ], [
            'nome.required'      => 'O nome é obrigatório.',
            'marca.required'     => 'A marca é obrigatória.',
            'tamanho.required'   => 'O tamanho é obrigatório.',
            'preco.required'     => 'O preço é obrigatório.',
            'quantidade.required' => 'A quantidade é obrigatória.',
            'quantidade.integer' => 'A quantidade deve ser um valor inteiro.',
            'quantidade.min'     => 'A quantidade não pode ser negativa.',
            'estoque.required'   => 'O estoque é obrigatório.',
            'estoque.integer'    => 'O estoque deve ser um valor inteiro.',
            'estoque.min'        => 'O estoque não pode ser negativo.',
        ]);
    }

    public function store(Request $request)
    {
        $this->validateForm($request);

        AcessoriosSeguranca::create($request->all());

        return redirect()->route('acessoriosSeguranca.index')->with('success', 'Registro salvo com sucesso!');
    }

    public function edit($id)
    {
        $dado = AcessoriosSeguranca::findOrFail($id);

        return view('acessoriosSeguranca.form', compact('dado'));
    }

    public function update(Request $request, $id)
    {
        $this->validateForm($request);

        $acessorio = AcessoriosSeguranca::findOrFail($id);
        $acessorio->update($request->all());

        return redirect()->route('acessoriosSeguranca.index')->with('success', 'Registro atualizado com sucesso!');
    }

    public function destroy($id)
    {
        AcessoriosSeguranca::destroy($id);

        return redirect()->route('acessoriosSeguranca.index')->with('success', 'Registro removido com sucesso!');
    }

    public function search(Request $request)
    {
        if (!empty($request->valor)) {
            $dados = AcessoriosSeguranca::where(
                $request->tipo ?? 'nome',
                'like',
                "%$request->valor%"
            )->get();
        } else {
            $dados = AcessoriosSeguranca::all();
        }

        return view('acessoriosSeguranca.list', compact('dados'));
    }
}