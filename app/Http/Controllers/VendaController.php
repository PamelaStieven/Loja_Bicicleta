<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Venda;
use App\Models\ItemVenda;
use App\Models\Usuario;
use App\Models\Bicicleta;
use App\Models\Acessorios;
use App\Charts\VendaChart;       
use App\Charts\FornecedorChart;  

class VendaController extends Controller
{
  
public function index(Request $request)
{
    $query = Venda::with([
        'usuario',
        'funcionario',
        'itens.bicicleta',
        'itens.acessorio',
        'bicicleta',
        'acessorio'
    ]);

    $tipo = $request->input('tipo');
    $valor = $request->input('valor');

    if ($tipo && $valor !== null && $valor !== '') {
        switch ($tipo) {
            case 'usuario':
                $query->whereHas('usuario', function ($q) use ($valor) {
                    $q->where('nome', 'like', "%{$valor}%");
                });
                break;

            case 'funcionario':
                $query->whereHas('funcionario', function ($q) use ($valor) {
                    $q->where('nome', 'like', "%{$valor}%");
                });
                break;

            case 'item':
                $query->where(function ($q) use ($valor) {
                    $q->whereHas('itens.bicicleta', function ($b) use ($valor) {
                        $b->where('modelo', 'like', "%{$valor}%")
                          ->orWhere('marca', 'like', "%{$valor}%");
                    })->orWhereHas('itens.acessorio', function ($a) use ($valor) {
                        $a->where('nome', 'like', "%{$valor}%");
                    })->orWhereHas('bicicleta', function ($b) use ($valor) {
                        $b->where('modelo', 'like', "%{$valor}%")
                          ->orWhere('marca', 'like', "%{$valor}%");
                    })->orWhereHas('acessorio', function ($a) use ($valor) {
                        $a->where('nome', 'like', "%{$valor}%");
                    });
                });
                break;

            case 'quantidade':
                $query->whereHas('itens', function ($q) use ($valor) {
                    $q->where('quantidade', $valor);
                })->orWhere('quantidade', $valor);
                break;

            case 'data':
                $query->whereDate('data_venda', $valor);
                break;
        }
    }

    $dados = $query->get();

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

    public function edit($id)
    {
        $venda        = Venda::findOrFail($id); 
        $usuarios     = Usuario::all();
        $funcionarios = Usuario::all();
        $bicicletas   = Bicicleta::all();    
        $acessorios   = Acessorios::all();   

        return view('venda.form', compact('venda', 'usuarios', 'funcionarios', 'bicicletas', 'acessorios'));
    }

  
public function store(Request $request)
{
    $request->validate([
        'usuario_id' => 'required|exists:usuarios,id',
        'funcionario_id' => 'required|exists:usuarios,id',
        'itens' => 'required|array|min:1',
        'itens.*.tipo' => 'required|in:bicicleta,acessorio',
        'itens.*.produto_id' => 'required|integer|min:1',
        'itens.*.quantidade' => 'required|integer|min:1',
    ]);

    \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
        $valorTotal = 0;
        $itensCalculados = [];

        foreach ($request->input('itens') as $item) {
            if ($item['tipo'] === 'bicicleta') {
                $produto = Bicicleta::findOrFail($item['produto_id']);
            } else {
                $produto = Acessorios::findOrFail($item['produto_id']);
            }

            $quantidade = (int) $item['quantidade'];
            $preco = (float) $produto->preco;
            $subtotal = round($preco * $quantidade, 2);

            $itensCalculados[] = [
                'bicicleta_id' => $item['tipo'] === 'bicicleta'
                    ? $produto->id : null,
                'acessorio_id' => $item['tipo'] === 'acessorio'
                    ? $produto->id : null,
                'quantidade' => $quantidade,
                'valor_total' => $subtotal,
            ];

            $valorTotal += $subtotal;
        }

        $venda = Venda::create([
            'usuario_id' => $request->usuario_id,
            'funcionario_id' => $request->funcionario_id,
            'data_venda' => now(),
            'valor_total' => round($valorTotal, 2),
        ]);

        foreach ($itensCalculados as $itemCalculado) {
            $venda->itens()->create($itemCalculado);
        }
    });

    return redirect()
        ->route('venda.index')
        ->with('success', 'Venda registrada com sucesso!');
}



   
public function update(Request $request, $id)
{
    $request->validate([
        'usuario_id' => 'required|exists:usuarios,id',
        'funcionario_id' => 'required|exists:usuarios,id',
        'itens' => 'required|array|min:1',
        'itens.*.tipo' => 'required|in:bicicleta,acessorio',
        'itens.*.produto_id' => 'required|integer|min:1',
        'itens.*.quantidade' => 'required|integer|min:1',
    ]);

    \Illuminate\Support\Facades\DB::transaction(function () use ($request, $id) {
        $venda = Venda::findOrFail($id);

        $valorTotal = 0;
        $itensCalculados = [];

        foreach ($request->input('itens') as $item) {
            $produto = $item['tipo'] === 'bicicleta'
                ? Bicicleta::findOrFail($item['produto_id'])
                : Acessorios::findOrFail($item['produto_id']);

            $quantidade = (int) $item['quantidade'];
            $subtotal = round((float) $produto->preco * $quantidade, 2);

            $itensCalculados[] = [
                'bicicleta_id' => $item['tipo'] === 'bicicleta'
                    ? $produto->id : null,
                'acessorio_id' => $item['tipo'] === 'acessorio'
                    ? $produto->id : null,
                'quantidade' => $quantidade,
                'valor_total' => $subtotal,
            ];

            $valorTotal += $subtotal;
        }

        $venda->update([
            'usuario_id' => $request->usuario_id,
            'funcionario_id' => $request->funcionario_id,
            'data_venda' => $request->data_venda ?? $venda->data_venda,
            'valor_total' => round($valorTotal, 2),
        ]);

        $venda->itens()->delete();

        foreach ($itensCalculados as $itemCalculado) {
            $venda->itens()->create($itemCalculado);
        }
    });

    return redirect()
        ->route('venda.index')
        ->with('success', 'Venda atualizada com sucesso!');
}


    public function destroy($id)
    {
        Venda::destroy($id);

        return redirect()->route('venda.index')->with('success', 'Venda removida com sucesso!');
    }
    
    public function relatorioVendas(VendaChart $chart)
    {
        $vendas = Venda::with(['usuario', 'bicicleta', 'acessorio'])->get();
        return view('venda.relatorio', ['chart' => $chart->build(), 'vendas' => $vendas]);
    }

    public function relatorioFornecedores(FornecedorChart $chart)
    {
        $fornecedores = \App\Models\Fornecedor::all();
        return view('fornecedor.relatorio', ['chart' => $chart->build(), 'fornecedores' => $fornecedores]);
    }


    public function showItens($id)
{
    $venda = Venda::with([
        'usuario',
        'funcionario',
        'itens.bicicleta',
        'itens.acessorio',
        'bicicleta',
        'acessorio',
    ])->findOrFail($id);

    return view('venda.itens', compact('venda'));
}
}