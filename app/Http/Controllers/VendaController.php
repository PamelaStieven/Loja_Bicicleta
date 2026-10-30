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
    /*
    |--------------------------------------------------------------------------
    | LISTAR VENDAS
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        // Limpa os dados temporários ao voltar para a listagem.
        session()->forget([
            'itens_venda_temp',
            'venda_usuario_id',
            'venda_funcionario_id',
            'tipo_produto_temp',
            'venda_editando_id',
            'venda_modo'
        ]);

        $dados = Venda::with([
            'usuario',
            'funcionario',
            'itens.bicicleta',
            'itens.acessorio'
        ])
        ->orderBy('id', 'desc')
        ->get();

        return view('venda.list', compact('dados'));
    }

    /*
    |--------------------------------------------------------------------------
    | ABRIR FORMULÁRIO DE NOVA VENDA
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        // Inicializa uma nova venda somente quando necessário.
        if (session('venda_modo') !== 'nova') {

            session()->forget([
                'itens_venda_temp',
                'venda_usuario_id',
                'venda_funcionario_id',
                'tipo_produto_temp',
                'venda_editando_id',
                'venda_modo'
            ]);

            session([
                'itens_venda_temp' => [],
                'tipo_produto_temp' => 'bicicleta',
                'venda_modo' => 'nova'
            ]);
        }

        $usuarios = Usuario::where('tipo', 'cliente')->get();

        $funcionarios = Usuario::where('tipo', 'funcionario')->get();

        $bicicletas = Bicicleta::all();

        $acessorios = Acessorios::all();

        return view('venda.form', compact(
            'usuarios',
            'funcionarios',
            'bicicletas',
            'acessorios'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | ADICIONAR E REMOVER ITENS TEMPORÁRIOS
    |--------------------------------------------------------------------------
    */

    public function gerenciarItem(Request $request)
    {
        $acao = $request->input('acao');

        // Impede que uma ação temporária misture duas vendas.
        $modoAtual = session('venda_modo');

        if (!in_array($modoAtual, ['nova', 'editar'], true)) {
            return redirect()
                ->route('venda.create')
                ->withErrors([
                    'venda' => 'A sessão da venda expirou. Inicie novamente.'
                ]);
        }

        // Atualiza o cliente selecionado.
        if ($request->filled('usuario_id')) {
            session([
                'venda_usuario_id' => $request->input('usuario_id')
            ]);
        }

        // Atualiza o funcionário selecionado.
        if ($request->filled('funcionario_id')) {
            session([
                'venda_funcionario_id' => $request->input('funcionario_id')
            ]);
        }

        // Recupera o tipo de produto.
        $tipo = $request->input(
            'tipo_produto_temp',
            session('tipo_produto_temp', 'bicicleta')
        );

        if (!in_array($tipo, ['bicicleta', 'acessorio'], true)) {
            return redirect()->back()
                ->withErrors([
                    'tipo_produto_temp' => 'Tipo de produto inválido.'
                ]);
        }

        session(['tipo_produto_temp' => $tipo]);

        // Recupera os itens que estão na sessão.
        $itens = session('itens_venda_temp', []);

        /*
        |--------------------------------------------------------------------------
        | ATUALIZAR LISTA DE PRODUTOS
        |--------------------------------------------------------------------------
        */

        if ($acao === 'mostrar_produtos') {
            return redirect()->back();
        }

        /*
        |--------------------------------------------------------------------------
        | ADICIONAR ITEM
        |--------------------------------------------------------------------------
        */

        if ($acao === 'adicionar_item') {

            $dados = $request->validate([
                'produto_temp_id' => 'required|integer|min:1',
                'quantidade_temp' => 'required|integer|min:1',
            ], [
                'produto_temp_id.required' => 'Selecione um produto.',
                'quantidade_temp.required' => 'Informe a quantidade.',
                'quantidade_temp.min' => 'A quantidade deve ser maior que zero.',
            ]);

            // Busca o produto na tabela correta.
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

            $produtoId = (int) $produto->id;
            $quantidade = (int) $dados['quantidade_temp'];
            $preco = (float) $produto->preco;

            $encontrado = false;

            // Se o mesmo produto já estiver na lista,
            // soma a quantidade em vez de duplicar a linha.
            foreach ($itens as &$item) {

                if (
                    $item['tipo'] === $tipo &&
                    (int) $item['produto_id'] === $produtoId
                ) {
                    $item['quantidade'] += $quantidade;
                    $item['preco'] = $preco;
                    $item['nome'] = $nome;

                    $encontrado = true;
                    break;
                }
            }

            unset($item);

            // Se o produto ainda não estiver na lista,
            // adiciona um novo item.
            if (!$encontrado) {

                $itens[] = [
                    'tipo' => $tipo,
                    'produto_id' => $produtoId,
                    'nome' => $nome,
                    'preco' => $preco,
                    'quantidade' => $quantidade,
                ];
            }

            // Salva a lista atualizada.
            session([
                'itens_venda_temp' => array_values($itens)
            ]);

            return redirect()->back();
        }

        /*
        |--------------------------------------------------------------------------
        | REMOVER ITEM
        |--------------------------------------------------------------------------
        */

        if (
            is_string($acao) &&
            preg_match('/^remover_(\d+)$/', $acao, $matches)
        ) {
            $indice = (int) $matches[1];

            if (array_key_exists($indice, $itens)) {

                unset($itens[$indice]);

                // Reorganiza os índices após a remoção.
                $itens = array_values($itens);

                session([
                    'itens_venda_temp' => $itens
                ]);
            }

            return redirect()->back();
        }

        return redirect()->back()
            ->withErrors([
                'venda' => 'Ação não reconhecida.'
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDAR FORMULÁRIO
    |--------------------------------------------------------------------------
    */

    private function validateForm(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|integer|exists:usuario,id',
            'funcionario_id' => 'required|integer|exists:usuario,id',

            'itens' => 'required|array|min:1',

            'itens.*.tipo' => 'required|in:bicicleta,acessorio',

            'itens.*.produto_id' => 'required|integer|min:1',

            'itens.*.quantidade' => 'required|integer|min:1',

            'itens.*.preco' => 'required|numeric|min:0',

        ], [
            'usuario_id.required' => 'Selecione um cliente.',
            'funcionario_id.required' => 'Selecione um funcionário.',
            'itens.required' => 'Adicione pelo menos um item à venda.',
            'itens.min' => 'Adicione pelo menos um item à venda.',
            'itens.*.tipo.required' => 'O tipo de produto é obrigatório.',
            'itens.*.produto_id.required' => 'Selecione um produto válido.',
            'itens.*.quantidade.min' => 'A quantidade deve ser maior que zero.',
        ]);

        // Confirma que o usuário selecionado é um cliente.
        $clienteValido = Usuario::where('id', $request->usuario_id)
            ->where('tipo', 'cliente')
            ->exists();

        if (!$clienteValido) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'usuario_id' => 'O usuário selecionado não é um cliente válido.'
            ]);
        }

        // Confirma que o funcionário selecionado é um funcionário.
        $funcionarioValido = Usuario::where('id', $request->funcionario_id)
            ->where('tipo', 'funcionario')
            ->exists();

        if (!$funcionarioValido) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'funcionario_id' => 'Selecione um funcionário válido.'
            ]);
        }

        // Confere se cada produto realmente existe na tabela correspondente.
        foreach ($request->input('itens', []) as $indice => $item) {

            if ($item['tipo'] === 'bicicleta') {

                $existe = Bicicleta::where(
                    'id',
                    $item['produto_id']
                )->exists();

            } else {

                $existe = Acessorios::where(
                    'id',
                    $item['produto_id']
                )->exists();
            }

            if (!$existe) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    "itens.$indice.produto_id" =>
                        'Um dos produtos selecionados não existe mais.'
                ]);
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTRAR NOVA VENDA
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $this->validateForm($request);

        // Cria uma nova venda a cada envio válido.
        // Não procura nem reutiliza uma venda anterior do cliente.
        DB::transaction(function () use ($request) {

            $venda = Venda::create([
                'usuario_id' => $request->usuario_id,
                'funcionario_id' => $request->funcionario_id,
                'data_venda' => now(),
                'valor_total' => 0,
            ]);

            $valorTotal = 0;

            foreach ($request->input('itens', []) as $item) {

                $quantidade = (int) $item['quantidade'];
                $preco = (float) $item['preco'];

                $subtotal = $quantidade * $preco;

                $valorTotal += $subtotal;

                ItemVenda::create([
                    'venda_id' => $venda->id,

                    'bicicleta_id' => $item['tipo'] === 'bicicleta'
                        ? $item['produto_id']
                        : null,

                    'acessorio_id' => $item['tipo'] === 'acessorio'
                        ? $item['produto_id']
                        : null,

                    'quantidade' => $quantidade,

                    'preco_unitario' => $preco,
                ]);
            }

            $venda->update([
                'valor_total' => $valorTotal
            ]);
        });

        // Limpa os dados temporários depois de salvar.
        session()->forget([
            'itens_venda_temp',
            'venda_usuario_id',
            'venda_funcionario_id',
            'tipo_produto_temp',
            'venda_editando_id',
            'venda_modo'
        ]);

        return redirect()
            ->route('venda.index')
            ->with('success', 'Venda registrada com sucesso!');
    }

    /*
    |--------------------------------------------------------------------------
    | ABRIR VENDA PARA EDIÇÃO
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $data = Venda::with([
            'usuario',
            'funcionario',
            'itens.bicicleta',
            'itens.acessorio'
        ])->findOrFail($id);

        // Inicializa a sessão ao entrar em outra venda.
        // Se for a mesma venda, preserva os itens removidos.
        if (
            session('venda_modo') !== 'editar' ||
            (int) session('venda_editando_id') !== (int) $data->id
        ) {
            $itensAtuais = [];

            foreach ($data->itens as $detalhe) {

                $produto = $detalhe->bicicleta
                    ?? $detalhe->acessorio;

                if (!$produto) {
                    continue;
                }

                $tipo = $detalhe->bicicleta_id
                    ? 'bicicleta'
                    : 'acessorio';

                $itensAtuais[] = [
                    'tipo' => $tipo,
                    'produto_id' => $produto->id,

                    'nome' => $produto->modelo
                        ?? $produto->nome
                        ?? 'Produto',

                    'preco' => (float) $detalhe->preco_unitario,

                    'quantidade' => (int) $detalhe->quantidade,
                ];
            }

            session([
                'itens_venda_temp' => $itensAtuais,
                'venda_usuario_id' => $data->usuario_id,
                'venda_funcionario_id' => $data->funcionario_id,
                'tipo_produto_temp' => 'bicicleta',
                'venda_editando_id' => $data->id,
                'venda_modo' => 'editar',
            ]);
        }

        $usuarios = Usuario::where('tipo', 'cliente')->get();

        $funcionarios = Usuario::where('tipo', 'funcionario')->get();

        $bicicletas = Bicicleta::all();

        $acessorios = Acessorios::all();

        return view('venda.form', compact(
            'data',
            'usuarios',
            'funcionarios',
            'bicicletas',
            'acessorios'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | ATUALIZAR VENDA
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        $this->validateForm($request);

        DB::transaction(function () use ($request, $id) {

            $venda = Venda::findOrFail($id);

            // Exclui somente os itens da venda que está sendo editada.
            ItemVenda::where('venda_id', $venda->id)->delete();

            $valorTotal = 0;

            // Registra novamente somente os itens enviados pelo formulário.
            foreach ($request->input('itens', []) as $item) {

                $quantidade = (int) $item['quantidade'];
                $preco = (float) $item['preco'];

                $subtotal = $quantidade * $preco;

                $valorTotal += $subtotal;

                ItemVenda::create([
                    'venda_id' => $venda->id,

                    'bicicleta_id' => $item['tipo'] === 'bicicleta'
                        ? $item['produto_id']
                        : null,

                    'acessorio_id' => $item['tipo'] === 'acessorio'
                        ? $item['produto_id']
                        : null,

                    'quantidade' => $quantidade,

                    'preco_unitario' => $preco,
                ]);
            }

            // Atualiza a venda existente, sem criar outra.
            $venda->update([
                'usuario_id' => $request->usuario_id,
                'funcionario_id' => $request->funcionario_id,
                'valor_total' => $valorTotal,
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

        return redirect()
            ->route('venda.index')
            ->with('success', 'Venda atualizada com sucesso!');
    }

    /*
    |--------------------------------------------------------------------------
    | EXCLUIR VENDA
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {

            $venda = Venda::findOrFail($id);

            // Exclui primeiro os itens associados.
            $venda->itens()->delete();

            // Depois exclui a venda.
            $venda->delete();
        });

        return redirect()
            ->route('venda.index')
            ->with('success', 'Venda excluída com sucesso!');
    }

    /*
    |--------------------------------------------------------------------------
    | VISUALIZAR ITENS DA VENDA
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $venda = Venda::with([
            'usuario',
            'funcionario',
            'itens.bicicleta',
            'itens.acessorio'
        ])->findOrFail($id);

        return view('venda.itens', compact('venda'));
    }

    /*
    |--------------------------------------------------------------------------
    | PESQUISAR VENDAS
    |--------------------------------------------------------------------------
    */

    public function search(Request $request)
    {
        $tipo = $request->input('tipo');
        $valor = trim((string) $request->input('valor', ''));

        $query = Venda::with([
            'usuario',
            'funcionario',
            'itens.bicicleta',
            'itens.acessorio'
        ]);

        if ($valor !== '') {

            if ($tipo === 'usuario') {

                $query->whereHas('usuario', function ($q) use ($valor) {
                    $q->where('nome', 'like', "%{$valor}%");
                });

            } elseif ($tipo === 'funcionario') {

                $query->whereHas('funcionario', function ($q) use ($valor) {
                    $q->where('nome', 'like', "%{$valor}%");
                });

            } elseif ($tipo === 'quantidade' && is_numeric($valor)) {

                $query->whereHas('itens', function ($q) use ($valor) {
                    $q->where('quantidade', (int) $valor);
                });

            } elseif ($tipo === 'data') {

                try {
                    \Carbon\Carbon::createFromFormat('Y-m-d', $valor);

                    $query->whereDate('data_venda', $valor);

                } catch (\Exception $e) {

                    return redirect()
                        ->route('venda.index')
                        ->withErrors([
                            'valor' => 'Informe uma data válida no formato AAAA-MM-DD.'
                        ]);
                }
            }
        }

        $dados = $query->orderBy('id', 'desc')->get();

        return view('venda.list', compact('dados'));
    }

    /*
    |--------------------------------------------------------------------------
    | GERAR RELATÓRIO PDF
    |--------------------------------------------------------------------------
    */

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
            'titulo' => 'Relatório de Vendas',
            'date' => date('d/m/Y'),
            'dados' => $vendas,
        ];

        $pdf = Pdf::loadView('venda.report', $data);

        return $pdf->download('relatorio_vendas.pdf');
    }

    /*
    |--------------------------------------------------------------------------
    | GRÁFICO DE VENDAS
    |--------------------------------------------------------------------------
    */

    public function chart(VendaChart $chart)
    {
        $vendas = Venda::all();

        return view('venda.chart', [
            'chart' => $chart->build(),
            'vendas' => $vendas,
        ]);
    }
}