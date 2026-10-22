@extends('main')
@section('titulo', isset($venda) ? 'Editar Venda' : 'Registrar Nova Venda')

@section('conteudo')
<div class="container my-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">{{ isset($venda) ? 'Editar Venda #' . $venda->id : 'Registrar Nova Venda' }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ isset($venda) ? route('venda.update', $venda->id) : route('venda.store') }}" method="POST">
                @csrf
                @if(isset($venda))
                    @method('PUT')
                @endif

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Usuário (Cliente) *</label>
                        <select name="usuario_id" class="form-select" required>
                            <option value="">Selecione o cliente...</option>
                            @foreach($usuarios ?? [] as $usuario)
                                {{-- Exibe apenas quem for do tipo Cliente/Usuário --}}
                                @if(isset($usuario->tipo) && strtolower(trim($usuario->tipo)) == 'usuario')
                                    <option value="{{ $usuario->id }}" {{ (isset($venda) && $venda->usuario_id == $usuario->id) ? 'selected' : '' }}>
                                        {{ $usuario->nome }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Funcionário *</label>
                        <select name="funcionario_id" class="form-select" required>
                            <option value="">Selecione o funcionário...</option>
                            @foreach($usuarios ?? [] as $func)
                                {{-- Exibe apenas quem for do tipo Funcionário --}}
                                @if(isset($func->tipo) && strtolower(trim($func->tipo)) == 'funcionario')
                                    <option value="{{ $func->id }}" {{ (isset($venda) && $venda->funcionario_id == $func->id) ? 'selected' : '' }}>
                                        {{ $func->nome }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <hr>
                <h5>Adicionar Produtos</h5>
                
                <div class="row g-3 align-items-end mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Tipo de Produto</label>
                        <select id="tipo_produto" class="form-select">
                            <option value="bicicleta">Bicicleta</option>
                            <option value="acessorio">Acessório</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Selecione o Item</label>
                        <select id="produto_select" class="form-select">
                            <option value="">Selecione...</option>
                            @foreach($bicicletas ?? [] as $bicicleta)
                                <option value="{{ $bicicleta->id }}" data-tipo="bicicleta" data-nome="{{ $bicicleta->modelo ?? $bicicleta->nome }}" data-preco="{{ $bicicleta->preco ?? 0 }}">
                                    {{ $bicicleta->modelo ?? $bicicleta->nome }} - R$ {{ number_format($bicicleta->preco ?? 0, 2, ',', '.') }}
                                </option>
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
                        <button type="button" id="btn-adicionar" class="btn btn-success w-100">Adicionar</button>
                    </div>
                </div>

                {{-- Tabela de Itens Adicionados --}}
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
                        {{-- Linhas inseridas dinamicamente --}}
                    </tbody>
                </table>

                <div class="row mt-4">
                    <div class="col-md-4 offset-md-8">
                        <label class="form-label fw-bold">Valor Total (R$) *</label>
                        <input type="text" name="valor_total" id="valor_total" class="form-control fw-bold fs-5" readonly required>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">Salvar Venda</button>
                    <a href="{{ route('venda.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const tipoSelect = document.getElementById('tipo_produto');
    const produtoSelect = document.getElementById('produto_select');
    const btnAdicionar = document.getElementById('btn-adicionar');
    const tabelaCorpo = document.querySelector('#tabela-itens tbody');
    const inputValorTotal = document.getElementById('valor_total');

    // Mostra apenas os itens da categoria selecionada (Bicicleta ou Acessório)
    function filtrarOpcoes() {
        const tipoEscolhido = tipoSelect.value;
        let primeiraOpcaoValida = null;

        Array.from(produtoSelect.options).forEach((opt, index) => {
            if (index === 0) return; // Mantém a opção "Selecione..."
            const tipoItem = opt.getAttribute('data-tipo');
            if (tipoItem === tipoEscolhido) {
                opt.style.display = '';
                if (!primeiraOpcaoValida) primeiraOpcaoValida = opt.value;
            } else {
                opt.style.display = 'none';
            }
        });
        produtoSelect.value = "";
    }

    if (tipoSelect) {
        tipoSelect.addEventListener('change', filtrarOpcoes);
        filtrarOpcoes();
    }

    let contadorItem = 0;

    // Adiciona o item selecionado na tabela abaixo
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
                    <input type="hidden" name="itens[${contadorItem}][produto_id]" value="${prodId}">
                    <input type="hidden" name="itens[${contadorItem}][tipo]" value="${tipo}">
                </td>
                <td class="text-capitalize">${tipo}</td>
                <td>
                    <input type="number" name="itens[${contadorItem}][quantidade]" value="${qtd}" class="form-control form-control-sm" readonly>
                </td>
                <td>
                    R$ ${preco.toFixed(2).replace('.', ',')}
                    <input type="hidden" name="itens[${contadorItem}][preco]" value="${preco}">
                </td>
                <td>R$ ${subtotal.toFixed(2).replace('.', ',')}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm btn-remover">Remover</button>
                </td>
            `;

            tabelaCorpo.appendChild(novaLinha);
            contadorItem++;
            atualizarTotal();

            produtoSelect.value = "";
            document.getElementById('quantidade_item').value = 1;
        });
    }

    // Remove a linha da tabela
    if (tabelaCorpo) {
        tabelaCorpo.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-remover')) {
                e.target.closest('tr').remove();
                atualizarTotal();
            }
        });
    }

    // Atualiza o valor total geral
    function atualizarTotal() {
        let total = 0;
        document.querySelectorAll('#tabela-itens tbody tr').forEach(tr => {
            const subtotalText = tr.children[4]?.textContent;
            if (subtotalText) {
                const valorLimpo = subtotalText.replace('R$', '').trim().replace('.', '').replace(',', '.');
                total += parseFloat(valorLimpo) || 0;
            }
        });
        if (inputValorTotal) {
            inputValorTotal.value = total.toFixed(2);
        }
    }
</script>
@endsection