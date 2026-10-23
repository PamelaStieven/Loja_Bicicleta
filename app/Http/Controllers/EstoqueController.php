<?php

namespace App\Http\Controllers;

use App\Models\Estoque;
use Illuminate\Http\Request;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Barryvdh\DomPDF\Facade\Pdf;

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

    public function search(Request $request)
    {
        $tipo = $request->input('tipo');
        $valor = $request->input('valor');

        $query = Estoque::query();

        if ($tipo === 'produto' && !empty($valor)) {
            $query->where('produto', 'like', '%' . $valor . '%');
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

    public function chart(LarapexChart $chart)
    {
        $estoques = Estoque::orderBy('produto', 'asc')->get();

        $produtos = $estoques->pluck('produto')->toArray();

        $quantidades = $estoques
            ->pluck('quantidade')
            ->map(fn ($quantidade) => (int) $quantidade)
            ->toArray();

        $chartResult = $chart->barChart()
            ->setTitle('Quantidade em Estoque por Produto')
            ->setSubtitle('Controle de Inventário - Loja de Bicicletas')
            ->addData('Quantidade', $quantidades)
            ->setXAxis($produtos);

        return view('Estoque.chart', [
            'chart' => $chartResult,
            'estoques' => $estoques,
        ]);
    }
}
