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
   public function index(Request $request)//listar
    {
        $query = Venda::with(['usuario', 'funcionario', 'bicicleta', 'acessorio']); //carrega antecipadamente os dados pra evitar problema

        $tipo  = $request->input('tipo');
        $valor = $request->input('valor');

        if ($tipo && $valor) {
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
                        $q->whereHas('bicicleta', function ($b) use ($valor) {
                            $b->where('modelo', 'like', "%{$valor}%")
                            ->orWhere('marca', 'like', "%{$valor}%");
                        })->orWhereHas('acessorio', function ($a) use ($valor) {
                            $a->where('nome', 'like', "%{$valor}%");
                        });
                    });
                    break;

                case 'quantidade':
                    $query->where('quantidade', $valor);
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
    // 1. Calcula o valor total com base nos produtos selecionados (você pode ajustar conforme os campos do seu form)
    $valorTotal = 0;

    if ($request->filled('bicicleta_id')) {
        $bicicleta = Bicicleta::find($request->bicicleta_id);
        if ($bicicleta) {
            $valorTotal += $bicicleta->preco * ($request->quantidade ?? 1);
        }
    }

    if ($request->filled('acessorio_id')) {
        $acessorio = Acessorios::find($request->acessorio_id);
        if ($acessorio) {
            $valorTotal += $acessorio->preco * ($request->quantidade ?? 1);
        }
    }

    // 2. Cria a venda principal
    $venda = Venda::create([
        'usuario_id' => $request->usuario_id,
        'funcionario_id' => $request->funcionario_id,
        'data_venda' => now(),
        'valor_total' => $valorTotal > 0 ? $valorTotal : ($request->valor_total ?? 0),
    ]);

    // 3. Salva automaticamente na tabela 1:N (ItemVenda) para a tela de itens funcionar
    if ($request->filled('bicicleta_id')) {
        ItemVenda::create([
            'venda_id' => $venda->id,
            'bicicleta_id' => $request->bicicleta_id,
            'acessorio_id' => null,
            'quantidade' => $request->quantidade ?? 1,
            'preco_unitario' => $bicicleta->preco ?? 0,
        ]);
    }

    if ($request->filled('acessorio_id')) {
        ItemVenda::create([
            'venda_id' => $venda->id,
            'bicicleta_id' => null,
            'acessorio_id' => $request->acessorio_id,
            'quantidade' => $request->quantidade ?? 1,
            'preco_unitario' => $acessorio->preco ?? 0,
        ]);
    }

    return redirect()->route('venda.index')->with('success', 'Venda registrada com sucesso!');
}



   public function update(Request $request, $id)
    {
        $venda = Venda::findOrFail($id);
        
        $venda->update([
            'usuario_id' => $request->usuario_id,
            'funcionario_id' => $request->funcionario_id,
            'valor_total' => $request->valor_total,
            'data_venda' => $request->data_venda ?? $venda->data_venda,
        ]);

        // Se novos itens foram enviados, atualiza a relação 1:N
        if ($request->has('itens')) {
            // Opcional: remove os antigos para substituir pelos novos
            ItemVenda::where('venda_id', $venda->id)->delete();

            foreach ($request->itens as $item) {
                ItemVenda::create([
                    'venda_id' => $venda->id,
                    'bicicleta_id' => $item['tipo'] == 'bicicleta' ? $item['produto_id'] : null,
                    'acessorio_id' => $item['tipo'] == 'acessorio' ? $item['produto_id'] : null,
                    'quantidade' => $item['quantidade'],
                    'preco_unitario' => $item['preco'],
                ]);
            }
        }

        return redirect()->route('venda.index')->with('success', 'Venda atualizada com sucesso!');
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
    $venda = Venda::with(['usuario', 'funcionario', 'itens.bicicleta', 'itens.acessorio'])->findOrFail($id);
    return view('venda.itens', compact('venda'));
}
}