<?php

namespace App\Http\Controllers;

use App\Models\Bicicleta;
use Illuminate\Http\Request;

class BicicletaController extends Controller
{
    public function index(Request $request)
    {
        $dados = Bicicleta::when($request->filled('valor'), function ($query) use ($request) {
            $tipo = $request->input('tipo', 'modelo');
            $query->where($tipo, 'like', '%' . $request->input('valor') . '%');
        })->get();

        return view('bicicleta.list', [
            'dados' => $dados,
            'tipo'  => $request->input('tipo', 'modelo'),
            'valor' => $request->input('valor', '')
        ]);
    }

    public function search(Request $request)
    {
        return $this->index($request);
    }

    public function create()
    {
        $bicicleta = new Bicicleta();
        return view('bicicleta.form', compact('bicicleta'));
    }

    private function validateForm(Request $request)
    {
        $request->validate([
            'foto'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:50048',
            'categoria'  => 'nullable|string|max:255',
            'marca'      => 'required|string|max:255',
            'modelo'     => 'required|string|max:255',
            'preco'      => 'required|numeric',
            'cor'        => 'nullable|string|max:255',
            'aro'        => 'nullable|integer',
            'quantidade' => 'nullable|integer',
        ], [
            'foto.image'          => 'O arquivo deve ser uma imagem.',
            'foto.mimes'          => 'A imagem deve ser do tipo: jpeg, png, jpg, gif.',
            'categoria.required' => 'O campo categoria é obrigatório.',
            'marca.required'      => 'O campo marca é obrigatório.',
            'modelo.required'     => 'O campo modelo é obrigatório.',
            'preco.required'      => 'O campo preço é obrigatório.',
            'quantidade.required' => 'O campo quantidade é obrigatório.',
        ]);
    }

    public function store(Request $request)
    {
        $this->validateForm($request);

        $dados = $request->all();

        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $dados['foto'] = $request->file('foto')->store('bicicletas', 'public');
        }

        Bicicleta::create($dados);

        return redirect()->route('bicicleta.index')->with('success', 'Bicicleta salva com sucesso!');
    }

    public function update(Request $request, $id)
    {
        $this->validateForm($request);

        $bicicleta = Bicicleta::findOrFail($id);
        $dados = $request->all();

        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $dados['foto'] = $request->file('foto')->store('bicicletas', 'public');
        } else {
            unset($dados['foto']);
        }

        $bicicleta->update($dados);

        return redirect()->route('bicicleta.index')->with('success', 'Registro atualizado com sucesso!');
    }

    public function edit($id)
    {
        $bicicleta = Bicicleta::findOrFail($id);
        return view('bicicleta.form', compact('bicicleta'));
    }

    public function destroy($id)
    {
        Bicicleta::destroy($id);

        return redirect()->route('bicicleta.index')->with('success', 'Registro removido com sucesso!');
    }
}