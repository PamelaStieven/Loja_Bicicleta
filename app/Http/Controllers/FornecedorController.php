<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fornecedor;
use App\Charts\FornecedorChart; 

class FornecedorController extends Controller
{
    public function index(Request $request)
    {
        $query = Fornecedor::query();

        if ($request->filled('tipo') && $request->filled('valor')) {
            $query->where($request->tipo, 'like', '%' . $request->valor . '%');
        }

        $dados = $query->get();

        return view('fornecedor.list', compact('dados'));
    }

    public function create()
    {
        return view('fornecedor.form');
    }

    private function validateForm(Request $request)
    {
        $request->validate([
            'nome_fantasia'    => 'required|string|max:255',
            'produto'    => 'required|string|max:255',
            'email'    => 'required|string|max:255',
            'telefone' => 'required|string|max:20',
            'endereco' => 'required|string|max:255',
            'prazo_entrega' => 'nullable|integer|min:0',
            'valor' => 'nullable|numeric|min:0',
        ], [
            'nome_fantasia.required'    => 'O nome fantasia do fornecedor é obrigatório.',
            'produto.required'    => 'O produto do fornecedor é obrigatório.',
            'email.required'    => 'O email é obrigatório.',
            'telefone.required' => 'O telefone é obrigatório.',
            'endereco.required' => 'O endereço é obrigatório.',
            'prazo_entrega.integer' => 'O prazo de entrega deve ser um número inteiro.',
            'valor.numeric' => 'O valor deve ser um número.',
        ]);
    }

    public function store(Request $request)
    {
        $this->validateForm($request);

        Fornecedor::create($request->all());

        return redirect()->route('fornecedor.index')->with('success', 'Fornecedor salvo com sucesso!');
    }

    public function edit($id)
    {
        $dado = Fornecedor::findOrFail($id);

        return view('fornecedor.form', compact('dado'));
    }

    public function update(Request $request, $id)
    {
        $this->validateForm($request);

        $fornecedor = Fornecedor::findOrFail($id);
        $fornecedor->update($request->all());

        return redirect()->route('fornecedor.index')->with('success', 'Fornecedor atualizado com sucesso!');
    }

    public function destroy($id)
    {
        Fornecedor::destroy($id);

        return redirect()->route('fornecedor.index')->with('success', 'Fornecedor removido com sucesso!');
    }

    public function search(Request $request)
    {
        if (!empty($request->valor)) {
            $dados = Fornecedor::where(
                $request->tipo ?? 'nome_fantasia',
                'like',
                "%$request->valor%"
            )->get();
        } else {
            $dados = Fornecedor::all();
        }

        return view('fornecedor.list', compact('dados'));
    }

    public function chart(FornecedorChart $chart)
    {
        $fornecedores = Fornecedor::all();
        
        return view('fornecedor.chart', [
            'chart' => $chart->build(),
            'fornecedores' => $fornecedores
        ]);
    }
}