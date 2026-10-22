<?php

namespace App\Http\Controllers;

use App\Models\Bicicleta;
use App\Models\Categoria;
use Illuminate\Http\Request;

class BicicletaController extends Controller
{
    public function index(Request $request)
    {
        $query = Bicicleta::with('categoria'); // Carrega a relação com categoria para otimizar

        if ($request->filled('tipo') && $request->filled('valor')) {
            $query->where($request->tipo, 'like', '%' . $request->valor . '%');
        }

        $dados = $query->get();

        return view('bicicleta.list', compact('dados'));
    }

    public function create()
    {
        $dado = new Bicicleta(); // Cria objeto vazio
        $categorias = Categoria::all(); // Busca as categorias para o select

        return view('bicicleta.form', compact('dado', 'categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'marca'        => 'required|string|max:255',
            'modelo'       => 'required|string|max:255',
            'preco'        => 'required|numeric',
            'cor'          => 'nullable|string|max:255',
            'aro'          => 'nullable|integer',
            'quantidade'   => 'nullable|integer',
            'categoria_id' => 'required|exists:categoria,id', // Valida se a categoria existe
        ], [
            'marca.required'        => 'O campo marca é obrigatório.',
            'modelo.required'       => 'O campo modelo é obrigatório.',
            'preco.required'        => 'O campo preço é obrigatório.',
            'categoria_id.required' => 'O campo categoria é obrigatório.',
        ]);

        Bicicleta::create($request->all());

        return redirect()->route('bicicleta.index')->with('success', 'Bicicleta cadastrada com sucesso!');
    }

    public function edit($id)
    {
        $dado = Bicicleta::findOrFail($id); // Busca a bicicleta
        $categorias = Categoria::all(); // Busca as categorias para o select

        return view('bicicleta.form', compact('dado', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        $bicicleta = Bicicleta::findOrFail($id);

        $request->validate([
            'marca'        => 'required|string|max:255',
            'modelo'       => 'required|string|max:255',
            'preco'        => 'required|numeric',
            'cor'          => 'nullable|string|max:255',
            'aro'          => 'nullable|integer',
            'quantidade'   => 'nullable|integer',
            'categoria_id' => 'required|exists:categoria,id',
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