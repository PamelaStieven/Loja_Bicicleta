<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Acessorios;

class AcessoriosController extends Controller
{
    public function index()
    {
        $dados = Acessorios::all();

        return view('acessorios.list', compact('dados'));
    }

    public function create()
    {
        return view('acessorios.form');
    }

    private function validateForm(Request $request)
    {
        $request->validate([
            'nome'      => 'required',
            'categoria' => 'required',
            'preco'     => 'required|numeric',
        ], [
            'nome.required'      => 'O nome é obrigatório.',
            'categoria.required' => 'A categoria é obrigatória.',
            'preco.required'     => 'O preço é obrigatório.',
            'preco.numeric'      => 'O preço deve ser um valor numérico.',
        ]);
    }

    public function store(Request $request)
    {
        $this->validateForm($request);

        Acessorios::create($request->all());

        return redirect()->route('acessorios.index')->with('success', 'Registro salvo com sucesso!');
    }

    public function edit($id)
    {
        $data = Acessorios::findOrFail($id);

        return view('acessorios.form', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $this->validateForm($request);

        $acessorio = Acessorios::findOrFail($id);
        $acessorio->update($request->all());

        return redirect()->route('acessorios.index')->with('success', 'Registro atualizado com sucesso!');
    }

    public function destroy($id)
    {
        Acessorios::destroy($id);

        return redirect()->route('acessorios.index')->with('success', 'Registro removido com sucesso!');
    }

    public function search(Request $request)
    {
        if (!empty($request->valor)) {
            $dados = Acessorios::where(
                $request->tipo ?? 'nome',
                'like',
                "%$request->valor%"
            )->get();
        } else {
            $dados = Acessorios::all();
        }

        return view('acessorios.list', compact('dados'));
    }
}