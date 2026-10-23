<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // <-- IMPORTANTE: Adicione esta linha no topo
use App\Models\Filiais;

class FiliaisController extends Controller
{
    public function index(Request $request)
    {
        $query = Filiais::query();

        if ($request->filled('tipo') && $request->filled('valor')) {
            $query->where($request->tipo, 'like', '%' . $request->valor . '%');
        }

        $dados = $query->get();

        return view('filiais.list', compact('dados'));
    }

    public function create()
    {
        return view('filiais.form');
    }

    private function validateForm(Request $request, $id = null)
    {
        // Opcional: Se quiser reutilizar o validateForm para store e update, 
        // podes passar o $id para aplicar o ignore no CNPJ também aqui.
        $request->validate([
            'nome'      => 'required',
            'cnpj'      => [
                'required',
                Rule::unique('filiais', 'cnpj')->ignore($id),
            ],
            'cidade'    => 'required',
            'bairro'    => 'required',
            'rua'       => 'required',
            'numero'    => 'required',
            'telefone'  => 'required',
            'horario_funcionamento' => 'required',
        ], [
            'nome.required'      => 'O nome é obrigatório.',
            'cnpj.required'      => 'O CNPJ é obrigatório.',
            'cnpj.unique'        => 'O CNPJ informado já está cadastrado.',
            'cidade.required'    => 'A cidade é obrigatória.',
            'bairro.required'    => 'O bairro é obrigatório.',
            'rua.required'       => 'A rua é obrigatória.',
            'numero.required'    => 'O número é obrigatório.',
            'telefone.required'  => 'O telefone é obrigatório.',
            'horario_funcionamento.required' => 'O horário de funcionamento é obrigatório.',
        ]);
    }

    public function store(Request $request)
    {
        $this->validateForm($request);

        Filiais::create($request->all());

        return redirect()->route('filiais.index')->with('success', 'Registro salvo com sucesso!');
    }

    public function edit($id)
    {
        $dado = Filiais::findOrFail($id);

        return view('filiais.form', compact('dado'));
    }

    public function update(Request $request, $id)
    {
        // Chama a validação passando o ID atual para ignorar o próprio registo no unique do CNPJ
        $this->validateForm($request, $id);

        $filial = Filiais::findOrFail($id);
        $filial->update($request->all());

        return redirect()->route('filiais.index')->with('success', 'Registro atualizado com sucesso!');
    }

    public function destroy($id)
    {
        Filiais::destroy($id);

        return redirect()->route('filiais.index')->with('success', 'Registro removido com sucesso!');
    }

    public function search(Request $request)
    {
        if (!empty($request->valor)) {
            $dados = Filiais::where(
                $request->tipo ?? 'nome',
                'like',
                "%$request->valor%"
            )->get();
        } else {
            $dados = Filiais::all();
        }

        return view('filiais.list', compact('dados'));
    }
}