<?php

namespace App\Http\Controllers;

use App\Models\Bicicleta;
use Illuminate\Http\Request;

class BicicletaController extends Controller
{
    public function index(Request $request)
    {
        $query = Bicicleta::query();

        if ($request->filled('tipo') && $request->filled('valor')) {
            $query->where($request->tipo, 'like', '%' . $request->valor . '%');
        }

        $dados = $query->get();

        return view('bicicleta.list', compact('dados'));
    }

    public function create()
    {
        $dado = new Bicicleta();

        return view('bicicleta.form', compact('dado'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'marca'  => 'required|string|max:255',
            'modelo' => 'required|string|max:255',
            'preco'  => 'required|numeric',
        ], [
            'marca.required'  => 'O campo marca é obrigatório.',
            'modelo.required' => 'O campo modelo é obrigatório.',
            'preco.required'  => 'O campo preço é obrigatório.',
        ]);

        Bicicleta::create($request->all());

        return redirect()->route('bicicleta.index')->with('success', 'Bicicleta cadastrada com sucesso!');
    }

    public function edit($id)
    {
        // Busca a bicicleta para preencher as informações no formulário
        $dado = Bicicleta::findOrFail($id);

        return view('bicicleta.form', compact('dado'));
    }

    public function update(Request $request, $id)
    {
        $bicicleta = Bicicleta::findOrFail($id);

        $request->validate([
            'marca'  => 'required|string|max:255',
            'modelo' => 'required|string|max:255',
            'preco'  => 'required|numeric',
        ]);

        $bicicleta->update($request->all());

        return redirect()->route('bicicleta.index')->with('success', 'Bicicleta atualizada com sucesso!');
    }

    public function destroy($id)
    {
        Bicicleta::destroy($id);

        return redirect()->route('bicicleta.index')->with('success', 'Bicicleta removida com sucesso!');
    }
}