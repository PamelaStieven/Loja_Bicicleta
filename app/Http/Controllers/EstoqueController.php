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
        return view('Estoque.form');
    }

    public function validateForm(Request $request)
    {
        $request->validate([
            'produto'    => 'required|string|max:255',
            'quantidade' => 'required|integer|min:0',
            'preco'      => 'required|numeric|min:0',
        ], [
            'produto.required'    => 'O campo produto é obrigatório.',
            'quantidade.required' => 'A quantidade é obrigatória.',
            'preco.required'      => 'O preço é obrigatório.',
        ]);
    }

    public function store(Request $request)
    {
        $this->validateForm($request);

        Estoque::create($request->all());

        return redirect()->route('estoque.index')->with('success', 'Produto cadastrado no estoque com sucesso!');
    }

    public function edit($id)
    {
        $data = Estoque::findOrFail($id);
        return view('Estoque.form', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $this->validateForm($request);

        $estoque = Estoque::findOrFail($id);
        $estoque->update($request->all());

        return redirect()->route('estoque.index')->with('success', 'Estoque atualizado com sucesso!');
    }

    public function destroy($id)
    {
        Estoque::destroy($id);

        return redirect()->route('estoque.index')->with('success', 'Produto removido do estoque com sucesso!');
    }

    public function show($id)
    {
        $estoque = Estoque::findOrFail($id);
        return view('Estoque.show', compact('estoque'));
    }

    public function search(Request $request)
    {
        $tipo = $request->tipo;
        $valor = $request->valor;

        $query = Estoque::query();

        if (!empty($valor)) {
            if ($tipo === 'produto') {
                $query->where('produto', 'like', "%{$valor}%");
            }
        }

        $estoques = $query->get();
        return view('Estoque.list', compact('estoques'));
    }

    public function report()
    {
        $estoques = Estoque::orderBy('produto', 'asc')->get();
        $data = [
            'titulo' => 'Relatório de Estoque',
            'date'   => date('d/m/Y'),
            'dados'  => $estoques
        ];
        $pdf = Pdf::loadView('Estoque.report', $data);
        return $pdf->download('relatorio_estoque.pdf');
    }

    public function chart(LarapexChart $chart)
    {
        $estoques = Estoque::all();

        $produtos = $estoques->pluck('produto')->toArray();
        $quantidades = $estoques->pluck('quantidade')->map(fn($q) => (int) $q)->toArray();

        $chartResult = $chart->lineChart()
            ->setTitle('Quantidade em Estoque por Produto')
            ->setSubtitle('Controle de Inventário - Loja de Bicicletas')
            ->addData($quantidades, 'Quantidade')
            ->setXAxis($produtos);

        return view('Estoque.chart', [
            'chart' => $chartResult,
            'estoques' => $estoques
        ]);
    }
}