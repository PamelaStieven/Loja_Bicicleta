<?php

namespace App\Http\Controllers;

use App\Models\Estoque;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Charts\EstoqueChart;

class EstoqueController extends Controller
{
    public function index()
    {
        $estoques = Estoque::orderBy('id', 'desc')->get();

        return view('Estoque.list', compact('estoques'));
    }

    public function create()
    {
        $estoque = new Estoque();

        return view('Estoque.form', compact('estoque'));
    }

    public function validateForm(Request $request)
    {
        $request->validate([
            'produto' => 'required|string|max:255',
            'quantidade' => 'required|integer|min:0',
            'preco' => 'required|numeric|min:0',
        ], [
            'produto.required' => 'O nome do produto é obrigatório.',
            'produto.max' => 'O nome do produto deve ter no máximo 255 caracteres.',
            'quantidade.required' => 'A quantidade é obrigatória.',
            'quantidade.integer' => 'A quantidade deve ser um número inteiro.',
            'quantidade.min' => 'A quantidade não pode ser negativa.',
            'preco.required' => 'O preço é obrigatório.',
            'preco.numeric' => 'O preço deve ser um valor numérico.',
            'preco.min' => 'O preço não pode ser negativo.',
        ]);
    }
    public function store(Request $request)
    {
        $this->validateForm($request);

        Estoque::create([
            'produto' => $request->produto,
            'quantidade' => $request->quantidade,
            'preco' => $request->preco,
        ]);
        //redireciona
        return redirect()
            ->route('estoque.index')
            ->with('success', 'Produto cadastrado no estoque com sucesso!');
    }

    public function edit($id)
    {
        $estoque = Estoque::findOrFail($id);

        return view('Estoque.form', compact('estoque'));
    }

    public function update(Request $request, $id)
    {
        $this->validateForm($request);

        $estoque = Estoque::findOrFail($id);

        $estoque->update([
            'produto' => $request->produto,
            'quantidade' => $request->quantidade,
            'preco' => $request->preco,
        ]);

        return redirect()
            ->route('estoque.index')
            ->with('success', 'Estoque atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $estoque = Estoque::findOrFail($id);
        $estoque->delete();

        return redirect()
            ->route('estoque.index')
            ->with('success', 'Produto removido do estoque com sucesso!');
    }

    public function show($id)
    {
        $estoque = Estoque::findOrFail($id);

        return view('Estoque.show', compact('estoque'));
    }

    // Buscar registros por produto, quantidade ou preço
    public function search(Request $request)
    {
        $request->validate([
            'tipo' => 'required|in:produto,quantidade,preco',
            'valor' => 'required|string',
        ], [
            'tipo.required' => 'Selecione o tipo de pesquisa.',
            'valor.required' => 'Digite um valor para pesquisar.',
        ]);

        $tipo = $request->tipo;
        $valor = trim($request->valor);

        $query = Estoque::query();

        switch ($tipo) {
            case 'produto':
                $query->where('produto', 'like', '%' . $valor . '%');
                break;

            case 'quantidade':
                if (!is_numeric($valor) || (int) $valor < 0) {
                    return back()->withErrors([
                        'valor' => 'Digite uma quantidade válida.'
                    ])->withInput();
                }

                $query->where('quantidade', '=', (int) $valor);
                break;

            case 'preco':
            
                $preco = str_replace(',', '.', $valor);

                if (!is_numeric($preco) || (float) $preco < 0) {
                    return back()->withErrors([
                        'valor' => 'Digite um preço válido.'
                    ])->withInput();
                }

                $query->where('preco', '=', (float) $preco);
                break;
        }

        $estoques = $query->orderBy('id', 'desc')->get();

        return view('Estoque.list', compact('estoques'));
    }

    public function report()
    {
        $estoques = Estoque::orderBy('produto', 'asc')->get();

        $data = [
            'titulo' => 'Relatório de Estoque',
            'date' => date('d/m/Y'),
            'dados' => $estoques,
        ];

        $pdf = Pdf::loadView('Estoque.report', $data);

        return $pdf->download('relatorio_estoque.pdf');
    }

    public function chart(EstoqueChart $chart)
    {
        $estoques = Estoque::all();

        return view('Estoque.chart', [
            'chart' => $chart->build(),
            'estoques' => $estoques
        ]);
    }
}