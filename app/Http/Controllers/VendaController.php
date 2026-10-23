<?php

namespace App\Http\Controllers;

use App\Models\Venda;
use App\Models\ItemVenda;
use App\Models\Usuario;
use App\Models\Bicicleta;
use App\Models\Acessorios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Charts\VendaChart;

class VendaController extends Controller
{
    public function index()
    {
        $dados = Venda::with(['usuario', 'funcionario', 'itens.bicicleta', 'itens.acessorio'])->orderBy('id', 'desc')->get();
        return view('venda.list', compact('dados'));
    }

    public function create()
    {
        $usuarios     = Usuario::all();
        $funcionarios = Usuario::all();
        $bicicletas   = Bicicleta::all();
        $acessorios   = Acessorios::all();
        return view('venda.form', compact('usuarios', 'funcionarios', 'bicicletas', 'acessorios'));
    }

    public function validateForm(Request $request)
    {
        $request->validate([
            'usuario_id'     => 'required|exists:usuario,id',
            'funcionario_id' => 'required|exists:usuario,id',
            'itens'          => 'required|array|min:1',
            'itens.*.tipo'   => 'required|in:bicicleta,acessorio',
            'itens.*.produto_id' => 'required|integer',
            'itens.*.quantidade' => 'required|integer|min:1',
            'itens.*.preco'      => 'required|numeric',
        ], [
            'usuario_id.required'     => 'O Cliente é obrigatório.',
            'funcionario_id.required' => 'O Funcionário é obrigatório.',
            'itens.required'          => 'Adicione pelo menos um item à venda.',
            'itens.min'               => 'Adicione pelo menos um item à venda.',
        ]);
    }

    public function store(Request $request)
    {
        $this->validateForm($request);

        DB::transaction(function () use ($request) {
            $valorTotal = 0;

            $venda = Venda::create([
                'usuario_id'     => $request->usuario_id,
                'funcionario_id' => $request->funcionario_id,
                'data_venda'     => now(),
                'valor_total'    => 0,
            ]);

            foreach ($request->itens as $item) {
                $subtotal = $item['quantidade'] * $item['preco'];
                $valorTotal += $subtotal;

                ItemVenda::create([
                    'venda_id'       => $venda->id,
                    'bicicleta_id'   => $item['tipo'] === 'bicicleta' ? $item['produto_id'] : null,
                    'acessorio_id'   => $item['tipo'] === 'acessorio' ? $item['produto_id'] : null,
                    'quantidade'     => $item['quantidade'],
                    'preco_unitario' => $item['preco'],
                ]);
            }

            $venda->update(['valor_total' => $valorTotal]);
        });

        return redirect()->route('venda.index')->with('success', 'Venda registrada com sucesso!');
    }

    public function edit($id)
    {
        $data         = Venda::with(['itens.bicicleta', 'itens.acessorio'])->findOrFail($id);
        $usuarios     = Usuario::all();
        $funcionarios = Usuario::all();
        $bicicletas   = Bicicleta::all();
        $acessorios   = Acessorios::all();
        return view('venda.form', compact('data', 'usuarios', 'funcionarios', 'bicicletas', 'acessorios'));
    }

    public function update(Request $request, $id)
    {
        $this->validateForm($request);

        DB::transaction(function () use ($request, $id) {
            $venda = Venda::findOrFail($id);
            
            ItemVenda::where('venda_id', $venda->id)->delete();

            $valorTotal = 0;
            foreach ($request->itens as $item) {
                $subtotal = $item['quantidade'] * $item['preco'];
                $valorTotal += $subtotal;

                ItemVenda::create([
                    'venda_id'       => $venda->id,
                    'bicicleta_id'   => $item['tipo'] === 'bicicleta' ? $item['produto_id'] : null,
                    'acessorio_id'   => $item['tipo'] === 'acessorio' ? $item['produto_id'] : null,
                    'quantidade'     => $item['quantidade'],
                    'preco_unitario' => $item['preco'],
                ]);
            }

            $venda->update([
                'usuario_id'     => $request->usuario_id,
                'funcionario_id' => $request->funcionario_id,
                'valor_total'    => $valorTotal,
            ]);
        });

        return redirect()->route('venda.index')->with('success', 'Venda atualizada com sucesso!');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            ItemVenda::where('venda_id', $id)->delete();
            Venda::destroy($id);
        });

        return redirect()->route('venda.index')->with('success', 'Registro removido com sucesso!');
    }

    public function show($id)
    {
        $venda = Venda::with(['usuario', 'funcionario', 'itens.bicicleta', 'itens.acessorio'])->findOrFail($id);
        return view('venda.itens', compact('venda'));
    }

    public function search(Request $request)
    {
        $tipo = $request->tipo;
        $valor = $request->valor;

        $query = Venda::with(['usuario', 'funcionario', 'itens.bicicleta', 'itens.acessorio']);

        if (!empty($valor)) {
            if ($tipo === 'usuario') {
                $query->whereHas('usuario', function ($q) use ($valor) {
                    $q->where('nome', 'like', "%{$valor}%");
                });
            } elseif ($tipo === 'funcionario') {
                $query->whereHas('funcionario', function ($q) use ($valor) {
                    $q->where('nome', 'like', "%{$valor}%");
                });
            }
        }

        $dados = $query->get();
        return view('venda.list', compact('dados'));
    }

    public function report()
    {
        $vendas = Venda::with(['usuario', 'funcionario', 'itens'])->orderBy('data_venda', 'desc')->get();
        $data = [
            'titulo' => 'Relatório Listagem de Vendas',
            'date'   => date('d/m/Y'),
            'dados'  => $vendas
        ];
        $pdf = Pdf::loadView('venda.report', $data);
        return $pdf->download('relatorio_vendas.pdf');
    }

    public function chart(VendaChart $chart)
    {
        $vendas = Venda::all();

        return view('venda.chart', [
            'chart' => $chart->build(),
            'vendas' => $vendas
        ]);
    }
}