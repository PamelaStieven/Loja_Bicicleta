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
        // Redireciona os parâmetros POST/GET diretamente para o index
        return $this->index($request);
    }

    public function create()
    {
        $dado = new Bicicleta();
        return view('bicicleta.form', compact('dado'));
    }

    private function validateForm(Request $request)
    {
        $request->validate([
            'foto'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'marca'      => 'required|string|max:255',
            'modelo'     => 'required|string|max:255',
            'preco'      => 'required|numeric',
            'cor'        => 'required|string|max:255',
            'aro'        => 'required|integer',
            'quantidade' => 'required|integer',
        ], [
            'foto.image'        => 'O arquivo deve ser uma imagem.',
            'foto.mimes'        => 'A imagem deve ser do tipo: jpeg, png, jpg, gif.',
            'marca.required'      => 'O campo marca é obrigatório.',
            'modelo.required'     => 'O campo modelo é obrigatório.',
            'preco.required'      => 'O campo preço é obrigatório.',
            'cor.required'        => 'O campo cor é obrigatório.',
            'aro.required'        => 'O campo aro é obrigatório.',
            'quantidade.required' => 'O campo quantidade é obrigatório.',
        ]);
    }

   public function store(Request $request)
{
    $this->validateForm($request);

    $dados = $request->all();

    // Verifica se foi enviada uma imagem válida
   if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
    // Grava na pasta storage/app/public/bicicletas
    $dados['foto'] = $request->file('foto')->store('bicicletas', 'public');
}

    Bicicleta::create($dados);

    return redirect()->route('bicicleta.index')->with('success', 'Registro salvo com sucesso!');
}

public function update(Request $request, $id)
{
    $this->validateForm($request);

    $bicicleta = Bicicleta::findOrFail($id);
    $dados = $request->all();

    if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
        // Opcional: deletar a foto antiga se existir
        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
        // Grava na pasta storage/app/public/bicicletas
        $dados['foto'] = $request->file('foto')->store('bicicletas', 'public');
}
        
        $dados['foto'] = $request->file('foto')->store('bicicletas', 'public');
    }

    $bicicleta->update($dados);

    return redirect()->route('bicicleta.index')->with('success', 'Registro atualizado com sucesso!');
}

    public function edit($id)
    {
        $dado = Bicicleta::findOrFail($id);
        return view('bicicleta.form', compact('dado'));
    }

    public function destroy($id)
    {
        Bicicleta::destroy($id);

        return redirect()->route('bicicleta.index')->with('success', 'Registro removido com sucesso!');
    }
}