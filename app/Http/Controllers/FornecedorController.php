<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use App\Charts\FornecedorChart;
use Barryvdh\DomPDF\Facade\Pdf; 
use Illuminate\Validation\Rule; 
use Illuminate\Http\Request;

class FornecedorController extends Controller
{
    public function index(Request $request)
    {
        $valor = $request->input('valor');
        $tipo  = $request->input('tipo', 'nome');

        $dados = Fornecedor::when(!empty($valor), function ($query) use ($tipo, $valor) {
            $query->where($tipo, 'like', '%' . $valor . '%');
        })->get();

        return view('fornecedor.list', [
            'dados' => $dados,
            'tipo'  => $tipo,
            'valor' => $valor
        ]);
    }

    public function search(Request $request)
    {
        return $this->index($request);
    }

    public function create()
    {
        $dado = new Fornecedor(); 

        return view('fornecedor.form', compact('dado'));
    }

    
    private function validateForm(Request $request, $id = null)
    {
        $request->validate([
            'nome'          => 'required|string|max:255',
            'produto'       => 'required|string|max:255',
            'email'         => 'required|string|max:255',
            'telefone'      => 'required|string|max:20',
            'endereco'      => 'required|string|max:255',
            'cnpj'          => [
                'required',
                Rule::unique('fornecedor', 'cnpj')->ignore($id),
            ],
            'prazo_entrega' => 'nullable|integer|min:0',
            'valor'         => 'nullable|numeric|min:0',
            'funcionario_id' => 'nullable|exists:users,id',
            'status'        => 'nullable|string|max:50',
            'link_site'     => 'nullable|url|max:255',
            'redes_sociais' => 'nullable|string|max:255',
        ], [
            'nome.required'         => 'O nome do fornecedor é obrigatório.',
            'produto.required'      => 'O produto do fornecedor é obrigatório.',
            'email.required'        => 'O email é obrigatório.',
            'telefone.required'     => 'O telefone é obrigatório.',
            'endereco.required'     => 'O endereço é obrigatório.',
            'cnpj.required'         => 'O CNPJ é obrigatório.',
            'cnpj.unique'           => 'O CNPJ informado já está cadastrado.',
            'prazo_entrega.integer' => 'O prazo de entrega deve ser um número inteiro.',
            'valor.numeric'         => 'O valor deve ser um número.',
            'funcionario_id.exists' => 'O funcionário selecionado não existe.',
            'status.required'       => 'O status é obrigatório.',
            'link_site.url'         => 'O link do site deve ser uma URL válida.',
            'redes_sociais.max'     => 'O campo de redes sociais deve ter no máximo 255 caracteres.',
        ]);
    }

    public function store(Request $request)
    {
        $this->validateForm($request); // No cadastro não precisa de ID

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
        $this->validateForm($request, $id); 

        $fornecedor = Fornecedor::findOrFail($id);
        $fornecedor->update($request->all());

        return redirect()->route('fornecedor.index')->with('success', 'Fornecedor atualizado com sucesso!');
    }

    public function destroy($id)
    {
        Fornecedor::destroy($id);

        return redirect()->route('fornecedor.index')->with('success', 'Fornecedor removido com sucesso!');
    }

    public function report()
    {
        $fornecedores = Fornecedor::all();
        $data = [
            'titulo' => 'Relatório Listagem de Fornecedores',
            'date'   => date('d/m/Y'),
            'dados'  => $fornecedores
        ];
        $pdf = Pdf::loadView('fornecedor.report', $data);
        return $pdf->download('relatorio_fornecedores.pdf');
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