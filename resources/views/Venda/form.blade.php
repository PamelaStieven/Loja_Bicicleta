@extends('main')
@section('titulo', isset($data) && !empty($data->id) ? 'Editar Venda' : 'Registrar Nova Venda')
@section('conteudo')
    <div class="row">
        @php
            $action = !empty($data->id) ? route('venda.update', $data->id) : route('venda.store');
        @endphp

        <h4>{{ isset($data->id) ? 'Editar Venda #' . $data->id : 'Formulário Venda' }}</h4>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ $action }}" method="post" enctype="multipart/form-data">
            @csrf
            @if (!empty($data->id))
                @method('PUT')
            @endif

            <input type="hidden" name="id" value="{{ old('id', $data->id ?? '') }}">

            <div class="row mb-3">
                <div class="col-6">
                    <label for="usuario_id">Usuário (Cliente)</label>
                    <select name="usuario_id" class="form-control" required>
                        <option value="">Selecione o cliente...</option>
                        @foreach($usuarios ?? [] as $usuario)
                            <option value="{{ $usuario->id }}" {{ old('usuario_id', $data->usuario_id ?? '') == $usuario->id ? 'selected' : '' }}>
                                {{ $usuario->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6">
                    <label for="funcionario_id">Funcionário</label>
                    <select name="funcionario_id" class="form-control" required>
                        <option value="">Selecione o funcionário...</option>
                        @foreach($funcionarios ?? [] as $funcionario)
                            <option value="{{ $funcionario->id }}" {{ old('funcionario_id', $data->funcionario_id ?? '') == $funcionario->id ? 'selected' : '' }}>
                                {{ $funcionario->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <hr>
            <h5>Adicionar Produtos</h5>
            
            <div class="row g-3 align-items-end mb-3">
                <div class="col-md-4">
                    <label class="form-label">Tipo de Produto</label>
                    <select id="tipo_produto" class="form-control">
                        <option value="bicicleta">Bicicleta</option>
                        <option value="acessorio">Acessório</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Selecione o Item</label>
                    <select id="produto_select" class="form-control">
                        <option value="">Selecione...</option>
                        @foreach($bicicletas ?? [] as $bicicleta)
                            <option value="{{ $bicicleta->id }}" data-tipo="bicicleta" data-nome="{{ $bicicleta->modelo ?? $bicicleta->nome }}" data-preco="{{ $bicicleta->preco ?? 0 }}">
                                {{ $bicicleta->modelo ?? $bicicleta->nome }} - R$ {{ number_format($bicicleta->preco ?? 0, 2, ',', '.') }}
                            </option>
                            //percorre a lista de bicicletas e cria uma opção para cada uma.
                        @endforeach
                        @foreach($acessorios ?? [] as $acessorio)
                            <option value="{{ $acessorio->id }}" data-tipo="acessorio" data-nome="{{ $acessorio->nome }}" data-preco="{{ $acessorio->preco ?? 0 }}" style="display:none;">
                                {{ $acessorio->nome }} - R$ {{ number_format($acessorio->preco ?? 0, 2, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Quantidade</label>
                    <input type="number" id="quantidade_item" class="form-control" value="1" min="1">
                </div>
                <div class="col-md-2">
                    <button type="button" id="btn-adicionar" class="btn btn-success w-100 mt-4">Adicionar</button>
                </div>
            </div>

            <table class="table table-bordered mt-3" id="tabela-itens">
                <thead class="table-light">
                    <tr>
                        <th>Produto</th>
                        <th>Tipo</th>
                        <th>Qtd</th>
                        <th>Preço Unitário</th>
                        <th>Subtotal</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    //Verifica se a venda que está sendo editada já possui itens e, se possuir, percorre cada
                    @if(isset($data) && $data->itens)
                        @foreach($data->itens as $index => $detalhe)
                        //Identifica o produto e calcular o subtotal
                            @php
                                $prodObj = $detalhe->bicicleta ?? $detalhe->acessorio;
                                $tipoItem = $detalhe->bicicleta_id ? 'bicicleta' : 'acessorio';
                                $nomeProd = $prodObj->modelo ?? $prodObj->nome ?? 'Produto';
                                $precoUnit = $detalhe->preco_unitario ?? $prodObj->preco ?? 0;
                                $subtotal = $precoUnit * $detalhe->quantidade;
                            @endphp
                            //Monta uma linha da tabela para cada item que já existe na venda.
                            <tr>
                                <td>
                                    {{ $nomeProd }}
                                    <input type="hidden" name="itens[{{ $index }}][produto_id]" value="{{ $prodObj->id ?? 0 }}">
                                    <input type="hidden" name="itens[{{ $index }}][tipo]" value="{{ $tipoItem }}">
                                </td>
                                <td class="text-capitalize">{{ $tipoItem }}</td>
                                <td>
                                    <input type="number" name="itens[{{ $index }}][quantidade]" value="{{ $detalhe->quantidade }}" class="form-control form-control-sm" readonly>
                                </td>
                                <td>
                                    R$ {{ number_format($precoUnit, 2, ',', '.') }}
                                    <input type="hidden" name="itens[{{ $index }}][preco]" value="{{ $precoUnit }}">
                                </td>
                                <td class="item-subtotal" data-valor="{{ $subtotal }}">R$ {{ number_format($subtotal, 2, ',', '.') }}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm btn-remover">Remover</button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>

            <div class="row mt-4">
                <div class="col-md-4 offset-md-8">
                    <label class="form-label fw-bold">Valor Total (R$) *</label>
                    <input type="text" name="valor_total" id="valor_total" class="form-control fw-bold fs-5" value="{{ number_format($data->valor_total ?? 0, 2, '.', '') }}" readonly required>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-success">Salvar</button>
                <a href="{{ url('venda') }}" class="btn btn-primary">Voltar</a>
            </div>
        </form>
    </div>

<script>
    // Função para lidar com a adição e remoção de itens na tabela
    //espera a página terminar de carregar antes de executar as ações do JavaScript.
    document.addEventListener('DOMContentLoaded', function () {
        const tipoSelect = document.getElementById('tipo_produto');
        const produtoSelect = document.getElementById('produto_select');
        const btnAdicionar = document.getElementById('btn-adicionar');
        const tabelaCorpo = document.querySelector('#tabela-itens tbody');
        const inputValorTotal = document.getElementById('valor_total');

        //Verifica o tipo escolhido e tenta ocultar as opções que não foram
        function filtrarOpcoes() {
            const tipoEscolhido = tipoSelect.value;
            Array.from(produtoSelect.options).forEach((opt, index) => {
                if (index === 0) return;
                const tipoItem = opt.getAttribute('data-tipo');
                opt.style.display = (tipoItem === tipoEscolhido) ? '' : 'none';
            });
            produtoSelect.value = "";
        }

        if (tipoSelect) {
            tipoSelect.addEventListener('change', filtrarOpcoes);
            filtrarOpcoes();
        }

        function reindexarItens() {
            document.querySelectorAll('#tabela-itens tbody tr').forEach((tr, index) => {
                tr.querySelectorAll('input').forEach(input => {
                    input.name = input.name.replace(/\[\d+\]|\[__INDEX__\]/, `[${index}]`);
                });
            });
        }

        function atualizarTotal() {
            let total = 0;
            document.querySelectorAll('#tabela-itens tbody tr').forEach(tr => {
                const subtotalTd = tr.querySelector('.item-subtotal');
                if (subtotalTd) {
                    total += parseFloat(subtotalTd.getAttribute('data-valor')) || 0;
                }
            });
            if (inputValorTotal) {
                inputValorTotal.value = total.toFixed(2);
            }
        }

        if (btnAdicionar) {
            btnAdicionar.addEventListener('click', function() {
                const optSelecionada = produtoSelect.options[produtoSelect.selectedIndex];
                const prodId = produtoSelect.value;
                const qtd = parseInt(document.getElementById('quantidade_item').value) || 1;

                if (!prodId || produtoSelect.selectedIndex === 0) {
                    alert('Selecione um produto.');
                    return;
                }

                const nome = optSelecionada.getAttribute('data-nome');
                const preco = parseFloat(optSelecionada.getAttribute('data-preco')) || 0;
                const tipo = tipoSelect.value;
                const subtotal = preco * qtd;

                const novaLinha = document.createElement('tr');
                novaLinha.innerHTML = `
                    <td>
                        ${nome}
                        <input type="hidden" name="itens[0][produto_id]" value="${prodId}">
                        <input type="hidden" name="itens[0][tipo]" value="${tipo}">
                    </td>
                    <td class="text-capitalize">${tipo}</td>
                    <td>
                        <input type="number" name="itens[0][quantidade]" value="${qtd}" class="form-control form-control-sm" readonly>
                    </td>
                    <td>
                        R$ ${preco.toFixed(2).replace('.', ',')}
                        <input type="hidden" name="itens[0][preco]" value="${preco}">
                    </td>
                    <td class="item-subtotal" data-valor="${subtotal}">R$ ${subtotal.toFixed(2).replace('.', ',')}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm btn-remover">Remover</button>
                    </td>
                `;

                tabelaCorpo.appendChild(novaLinha);
                reindexarItens();
                atualizarTotal();

                produtoSelect.value = "";
                document.getElementById('quantidade_item').value = 1;
            });
        }

        if (tabelaCorpo) {
            tabelaCorpo.addEventListener('click', function(e) {
                if (e.target.classList.contains('btn-remover')) {
                    e.target.closest('tr').remove();
                    reindexarItens();
                    atualizarTotal();
                }
            });
        }

        reindexarItens();
        atualizarTotal();
    });
</script>
@stop