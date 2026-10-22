<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index(Request $request)
    {
        $query = Categoria::query();

        if ($request->filled('tipo') && $request->filled('valor')) {
            $query->where($request->tipo, 'like', '%' . $request->valor . '%');
        }

        $dados = $query->get();

        return view('categoria.list', compact('dados'));
    }

    public function create()
    {
        $dado = new Categoria();
        return view('categoria.form', compact('dado'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
        ], [
            'nome.required' => 'O campo nome é obrigatório.',
        ]);

        Categoria::create($request->all());

        return redirect()->route('categoria.index')->with('success', 'Categoria cadastrada com sucesso!');
    }

    public function edit($id)
    {
        $dado = Categoria::findOrFail($id);
        return view('categoria.form', compact('dado'));
    }

    public function update(Request $request, $id)
    {
        $categoria = Categoria::findOrFail($id);

        $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        $categoria->update($request->all());

        return redirect()->route('categoria.index')->with('success', 'Categoria atualizada com sucesso!');
    }

    public function destroy($id)
    {
        Categoria::destroy($id);

        return redirect()->route('categoria.index')->with('success', 'Categoria removida com sucesso!');
    }
}