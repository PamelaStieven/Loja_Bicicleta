<?php

namespace App\Http\Controllers;

use App\Models\Venda;
use App\Models\ItemVenda;
use App\Models\Usuario;
use App\Models\Bicicleta;
use App\Models\Acessorios;
use App\Charts\VendaChart;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendaController extends Controller
{
    public function index(Request $request)
    {   
        session()->forget([
            'itens_venda_temp',
            'venda_usuario_id',
            'venda_funcionario_id',
            'tipo_produto_temp',
            'venda_editando_id',
            'venda_modo'
        ]);

        $valor = $request->input('valor');
        $tipo  = $request->input('tipo', 'usuario');

        $query = Venda::with(['usuario', 'funcionario', 'itens.bicicleta', 'itens.acessorio']);

        if (!empty($valor)) {
            switch ($tipo) { //verifica o tipo de filtro e aplica a consulta correspondente
                case 'usuario':
                    $query->whereHas('usuario', function ($q) use ($valor) {
                        $q->where('nome', 'like', '%' . $valor . '%');
                    });
                    break;

                case 'funcionario':
                    $query->whereHas('funcionario', function ($q) use ($valor) {
                        $q->where('nome', 'like', '%' . $valor . '%');
                    });
                    break;

                case 'quantidade':
                    if (is_numeric($valor)) {
                        $query->whereHas('itens')//consulta às vendas que possuem itens relacionados
                            ->withSum('itens as quantidade_total', 'quantidade')
                            ->having('quantidade_total', '=', (int) $valor);
                    } else {
                        $query->whereRaw('1 = 0');
                    }
                    break;

                case 'data':
                    try {
                        $dataFormatted = \Carbon\Carbon::parse($valor)->format('Y-m-d');
                        $query->whereDate('data_venda', $dataFormatted);
                    } catch (\Exception $e) {
                        $query->whereRaw('1 = 0');
                    }
                    break;

                default:
                    $query->whereRaw('1 = 0');
                    break;
            }
        }

        $dados = $query->orderBy('id', 'desc')->get();

        return view('venda.list', [
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
        if (session('venda_modo') !== 'nova') { //!== :não é idêntico a
            session()->forget([
                'itens_venda_temp', 
                'venda_usuario_id',
                'venda_funcionario_id',
                'tipo_produto_temp',
                'venda_editando_id',
                'venda_modo'
            ]);

            session([
                'itens_venda_temp'  => [],
                'tipo_produto_temp' => 'bicicleta',
                'venda_modo'        => 'nova'
            ]);
        }

        $usuarios     = Usuario::where('tipo', 'cliente')->get();
        $funcionarios = Usuario::where('tipo', 'funcionario')->get();
        $bicicletas   = Bicicleta::all();
        $acessorios   = Acessorios::all();

        return view('venda.form', compact(
            'usuarios',
            'funcionarios',
            'bicicletas',
            'acessorios'
        ));
    }

  
    public function gerenciarItem(Request $request)
    {
        //informa o que o usuário deseja fazer. 
        $acao = $request->input('acao');

        // descobre o que esta sendo editado
        $vendaId = $request->input(
            'venda_id',
            session('venda_editando_id')
        );

        // Verificando se existe esse id venda.
        if (!empty($vendaId)) {
            $vendaExistente = Venda::findOrFail($vendaId);

            session([
                'venda_editando_id' => $vendaExistente->id,
                'venda_modo' => 'editar',
            ]);

            // Recupera o cliente
            if (
                !$request->filled('usuario_id')
                && 
                !session('venda_usuario_id')
            ) {
                session([
                    'venda_usuario_id' => $vendaExistente->usuario_id,
                ]);
            }

            // Recupera o funcionário 
            if (
                !$request->filled('funcionario_id')
                && 
                !session('venda_funcionario_id')
            ) {
                session([
                    'venda_funcionario_id' => $vendaExistente->funcionario_id,
                ]);
            }
        }

        // Verifica o modo atual da venda.
        $modoAtual = session('venda_modo');

        // !in_array :verifica se o valor existe dentro de uma lista
        if (!in_array($modoAtual, ['nova', 'editar'], true)) {
            return redirect()
                ->route('venda.create')
                ->withErrors([
                    'venda' => 'A sessão da venda expirou. Inicie novamente.',
                ]);
        }

        // Atualiza o cliente selecionado na sessão, guarda ID.
        if ($request->filled('usuario_id')) {
            session([
                'venda_usuario_id' => $request->input('usuario_id'),
            ]);
        }

        // Atualiza o funcionário selecionado na sessão.
        if ($request->filled('funcionario_id')) {
            session([
                'venda_funcionario_id' => $request->input('funcionario_id'),
            ]);
        }

        // Obtém o tipo de produto selecionado.
        $tipo = $request->input(
            'tipo_produto_temp',
            session('tipo_produto_temp', 'bicicleta')
        );

        // Aceita somente bicicletas ou acessórios.
        if (!in_array($tipo, ['bicicleta', 'acessorio'], true)) {
            return redirect()
                ->back()
                ->withErrors([
                    'tipo_produto_temp' => 'Tipo de produto inválido.',
                ]);
        }

        // Salva o tipo de produto selecionado na sessão
        session([
            'tipo_produto_temp' => $tipo,
        ]);

        // Recupera os itens temporários da venda ja add
        $itens = session('itens_venda_temp', []);

        // Define para qual tela o usuário deve retornar edit ou create
        $redirecionar = function () {
            if (
                session('venda_modo') === 'editar'
                && session('venda_editando_id')
            ) {
                return redirect()->route(
                    'venda.edit',
                    session('venda_editando_id')
                );
            }

            return redirect()->route('venda.create');
        };

        // Ação para mostrar os produtos disponíveis
        if ($acao === 'mostrar_produtos') {
            return $redirecionar();
        }

        // Ação para adicionar um produto à venda
        if ($acao === 'adicionar_item') {

            
            $dados = $request->validate([
                'produto_temp_id' => 'required|integer|min:1',
                'quantidade_temp' => 'required|integer|min:1',
            ], [
                'produto_temp_id.required' => 'Selecione um produto.',
                'quantidade_temp.required' => 'Informe a quantidade.',
                'quantidade_temp.min' => 'A quantidade deve ser maior que zero.',
            ]);

            // verifica se o produto selecionado é uma bicicleta ou um acessório
            if ($tipo === 'bicicleta') {
                $produto = Bicicleta::findOrFail(
                    $dados['produto_temp_id']
                );

                $nome = $produto->modelo
                    ?? $produto->nome
                    ?? 'Bicicleta';

            } else {
                $produto = Acessorios::findOrFail(
                    $dados['produto_temp_id']
                );

                $nome = $produto->nome ?? 'Acessório';
            }

            // Obtém os dados do produto.
            $produtoId = (int) $produto->id;
            $quantidade = (int) $dados['quantidade_temp'];
            $preco = (float) $produto->preco;

            // Verifica se o produto já está na lista.
            $encontrado = false;

            // Percorre os itens temporários da venda para verificar se o produto já existe
            // Se já existir, atualiza a quantidade e o preço sem duplicar o item
            foreach ($itens as &$item) {
                if (
                    $item['tipo'] === $tipo
                    && (int) $item['produto_id'] === $produtoId
                ) {
                    // Se já existe, aumenta a quantidade.
                    $item['quantidade'] += $quantidade;

                    // Atualiza o preço e o nome do produto.
                    $item['preco'] = $preco;
                    $item['nome'] = $nome;

                    $encontrado = true;

                    break;
                }
            }

            // encerra a referência utilizada &item.
            unset($item);

            // Se o produto ainda não existe na lista, adiciona um novo item
            if (!$encontrado) {
                $itens[] = [
                    'tipo' => $tipo,
                    'produto_id' => $produtoId,
                    'nome' => $nome,
                    'preco' => $preco,
                    'quantidade' => $quantidade,
                ];
            }

            session([
                'itens_venda_temp' => array_values($itens),
            ]);

            return $redirecionar();
        }

        // Ação para remover um item da venda.
        if (
            is_string($acao) 
            && preg_match('/^remover_(\d+)$/', $acao, $matches) //verifica se um texto corresponde a '/^remover_(\d+)$/'
        ) {
            // Obtém o índice do item que será removido.
            $indice = (int) $matches[1];

            // Verifica 
            if (array_key_exists($indice, $itens)) {

                // Remove
                unset($itens[$indice]);

                // Reorganiza
                $itens = array_values($itens);

                // Salva
                session([
                    'itens_venda_temp' => $itens,
                ]);
            }

            return $redirecionar();
        }

        return redirect()
            ->back()
            ->withErrors([
                'venda' => 'Ação não reconhecida.',
            ]);
    }


    private function validateForm(Request $request)
    {
        $request->validate([
            'usuario_id'          => 'required|integer|exists:usuario,id',
            'funcionario_id'      => 'required|integer|exists:usuario,id',
            'itens'               => 'required|array|min:1',
            'itens.*.tipo'        => 'required|in:bicicleta,acessorio',
            'itens.*.produto_id'  => 'required|integer|min:1',
            'itens.*.quantidade'  => 'required|integer|min:1',
            'itens.*.preco'       => 'required|numeric|min:0',
        ], [
            'usuario_id.required'         => 'Selecione um cliente.',
            'funcionario_id.required'     => 'Selecione um funcionário.',
            'itens.required'              => 'Adicione pelo menos um item à venda.',
            'itens.min'                   => 'Adicione pelo menos um item à venda.',
            'itens.*.tipo.required'       => 'O tipo de produto é obrigatório.',
            'itens.*.produto_id.required' => 'Selecione um produto válido.',
            'itens.*.quantidade.min'      => 'A quantidade deve ser maior que zero.',
        ]);

        $clienteValido = Usuario::where('id', $request->usuario_id)
            ->where('tipo', 'cliente')
            ->exists();

        if (!$clienteValido) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'usuario_id' => 'O usuário selecionado não é um cliente válido.'
            ]);
        }

        $funcionarioValido = Usuario::where('id', $request->funcionario_id)
            ->where('tipo', 'funcionario')
            ->exists();

        if (!$funcionarioValido) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'funcionario_id' => 'Selecione um funcionário válido.'
            ]);
        }
        //percorre a lista itens; se ela não estiver disponível, usa um array vazio
        foreach ($request->input('itens', []) as $indice => $item) {
            if ($item['tipo'] === 'bicicleta') {
                $existe = Bicicleta::where('id', $item['produto_id'])->exists();
            } else {
                $existe = Acessorios::where('id', $item['produto_id'])->exists();
            }

            if (!$existe) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    "itens.$indice.produto_id" => 'Um dos produtos selecionados não existe mais.'
                ]);
            }
        }
    }

    public function store(Request $request)
    {
        $this->validateForm($request);

        //permite que as operações realizadas sejam desfeitas
        DB::transaction(function () use ($request) { //função anonima que recebe o request como parâmetro
            $venda = Venda::create([
                'usuario_id'     => $request->usuario_id,
                'funcionario_id' => $request->funcionario_id,
                'data_venda'     => now(),
                'valor_total'    => 0,
            ]);

            $valorTotal = 0;

            foreach ($request->input('itens', []) as $item) {
                $quantidade = (int) $item['quantidade'];
                $preco      = (float) $item['preco'];
                $subtotal   = $quantidade * $preco;

                $valorTotal += $subtotal;
                //Salvar cada item da venda
                ItemVenda::create([
                    'venda_id'       => $venda->id,
                    'bicicleta_id'   => $item['tipo'] === 'bicicleta' ? $item['produto_id'] : null,
                    'acessorio_id'   => $item['tipo'] === 'acessorio' ? $item['produto_id'] : null,
                    'quantidade'     => $quantidade,
                    'preco_unitario' => $preco,
                ]);
            }

            $venda->update(['valor_total' => $valorTotal]);
        });

        session()->forget([
            'itens_venda_temp',
            'venda_usuario_id',
            'venda_funcionario_id',
            'tipo_produto_temp',
            'venda_editando_id',
            'venda_modo'
        ]);

        return redirect()->route('venda.index')->with('success', 'Venda registrada com sucesso!');
    }

    public function edit($id)
    {
        $data = Venda::with(['usuario', 'funcionario', 'itens.bicicleta', 'itens.acessorio'])->findOrFail($id);

        if (
            session('venda_modo') !== 'editar' ||
            (int) session('venda_editando_id') !== (int) $data->id
        ) {
            $itensAtuais = [];

            foreach ($data->itens as $detalhe) {
                $produto = $detalhe->bicicleta ?? $detalhe->acessorio;

                if (!$produto) {
                    continue;
                }

                $tipo = $detalhe->bicicleta_id ? 'bicicleta' : 'acessorio';

                $itensAtuais[] = [
                    'tipo'       => $tipo,
                    'produto_id' => $produto->id,
                    'nome'       => $produto->modelo ?? $produto->nome ?? 'Produto',
                    'preco'      => (float) $detalhe->preco_unitario,
                    'quantidade' => (int) $detalhe->quantidade,
                ];
            }

            session([
                'itens_venda_temp'     => $itensAtuais,
                'venda_usuario_id'     => $data->usuario_id,
                'venda_funcionario_id' => $data->funcionario_id,
                'tipo_produto_temp'    => 'bicicleta',
                'venda_editando_id'    => $data->id,
                'venda_modo'           => 'editar',
            ]);
        } else {
            if (!session('venda_usuario_id')) {
                session(['venda_usuario_id' => $data->usuario_id]);
            }
            if (!session('venda_funcionario_id')) {
                session(['venda_funcionario_id' => $data->funcionario_id]);
            }
        }

        $usuarios     = Usuario::where('tipo', 'cliente')->get();
        $funcionarios = Usuario::where('tipo', 'funcionario')->get();
        $bicicletas   = Bicicleta::all();
        $acessorios   = Acessorios::all();

        return view('venda.form', compact(
            'data',
            'usuarios',
            'funcionarios',
            'bicicletas',
            'acessorios'
        ));
    }

    public function update(Request $request, $id)
    {
        $this->validateForm($request);

        DB::transaction(function () use ($request, $id) {
            $venda = Venda::findOrFail($id);

            ItemVenda::where('venda_id', $venda->id)->delete();

            $valorTotal = 0;

            foreach ($request->input('itens', []) as $item) {
                $quantidade = (int) $item['quantidade'];
                $preco      = (float) $item['preco'];
                $subtotal   = $quantidade * $preco;

                $valorTotal += $subtotal;

                ItemVenda::create([
                    'venda_id'       => $venda->id,
                    'bicicleta_id'   => $item['tipo'] === 'bicicleta' ? $item['produto_id'] : null,
                    'acessorio_id'   => $item['tipo'] === 'acessorio' ? $item['produto_id'] : null,
                    'quantidade'     => $quantidade,
                    'preco_unitario' => $preco,
                ]);
            }

            $venda->update([
                'usuario_id'     => $request->usuario_id,
                'funcionario_id' => $request->funcionario_id,
                'valor_total'    => $valorTotal,
            ]);
        });

        session()->forget([
            'itens_venda_temp',
            'venda_usuario_id',
            'venda_funcionario_id',
            'tipo_produto_temp',
            'venda_editando_id',
            'venda_modo'
        ]);

        return redirect()->route('venda.index')->with('success', 'Venda atualizada com sucesso!');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $venda = Venda::findOrFail($id);
            $venda->itens()->delete();
            $venda->delete();
        });

        return redirect()->route('venda.index')->with('success', 'Venda removida com sucesso!');
    }

    public function show($id)
    {
        $venda = Venda::with(['usuario', 'funcionario', 'itens.bicicleta', 'itens.acessorio'])->findOrFail($id);

        return view('venda.itens', compact('venda'));
    }

    public function report()
    {
        $vendas = Venda::with([
            'usuario',
            'funcionario',
            'itens.bicicleta',
            'itens.acessorio'
        ])
        ->orderBy('data_venda', 'desc')
        ->get();

        $data = [
            'titulo' => 'Relatório Listagem de Vendas',
            'date'   => date('d/m/Y'),
            'dados'  => $vendas,
        ];

        $pdf = Pdf::loadView('venda.report', $data);

        return $pdf->download('relatorio_vendas.pdf');
    }

    public function chart(VendaChart $chart)
    {
        $vendas = Venda::all();

        return view('venda.chart', [
            'chart'  => $chart->build(),
            'vendas' => $vendas,
        ]);
    }
}