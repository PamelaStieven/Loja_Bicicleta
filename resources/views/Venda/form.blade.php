@extends('main')

@section('titulo', isset($data) && !empty($data->id) ? 'Editar Venda' : 'Registrar Nova Venda')

@section('conteudo')

@php
    $editando = isset($data) && !empty($data->id);

    $action = $editando
        ? route('venda.update', $data->id)
        : route('venda.store');

    $itensVenda = session('itens_venda_temp', []);
    $tipoSelecionado = session('tipo_produto_temp', 'bicicleta');

    //recupera od dados

    $clienteSelecionado = old('usuario_id',
        $data->usuario_id ?? session('venda_usuario_id', '')
    );

    $funcionarioSelecionado = old(
        'funcionario_id',
        $data->funcionario_id ?? session('venda_funcionario_id', '')
    );

    $valorTotalGeral = 0;
@endphp
{{-- verifica se é edição ou cadastro por true e false --}}
<div class="container py-4">
    g
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <h3 class="mb-0">
            {{ $editando ? 'Editar Venda #' . $data->id : 'Registrar Nova Venda' }}
        </h3>

        <a href="{{ route('venda.index') }}" class="btn btn-secondary">
            Voltar
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Verifique os seguintes erros:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="vendaForm" action="{{ $action }}" method="POST">
        @csrf

        @if ($editando)
            @method('PUT')
        @endif

        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0">1. Dados da venda</h5>
            </div>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label for="usuario_id" class="form-label">Cliente *</label>

                        <select name="usuario_id" id="usuario_id"
                                class="form-select" required>
                            <option value="">Selecione o cliente...</option>

                            @foreach ($usuarios ?? [] as $usuario)
                                <option value="{{ $usuario->id }}"
                                    {{ (string) $clienteSelecionado === (string) $usuario->id ? 'selected' : '' }}>
                                    {{ $usuario->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="funcionario_id" class="form-label">Funcionário *</label>

                        <select name="funcionario_id" id="funcionario_id"
                                class="form-select" required>
                            <option value="">Selecione o funcionário...</option>

                            @foreach ($funcionarios ?? [] as $funcionario)
                                <option value="{{ $funcionario->id }}"
                                    {{ (string) $funcionarioSelecionado === (string) $funcionario->id ? 'selected' : '' }}>
                                    {{ $funcionario->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- ITENS DA VENDA ENVIADOS AO CONTROLLER --}}
        {{-- Percorre todos os itens ja add da venda e organiza em formato de lista --}}
        @foreach ($itensVenda as $indice => $item)
            <input type="hidden"
                   name="itens[{{ $indice }}][produto_id]"
                   value="{{ $item['produto_id'] }}">

            <input type="hidden"
                   name="itens[{{ $indice }}][tipo]"
                   value="{{ $item['tipo'] }}">

            <input type="hidden"
                   name="itens[{{ $indice }}][quantidade]"
                   value="{{ $item['quantidade'] }}">

            <input type="hidden"
                   name="itens[{{ $indice }}][preco]"
                   value="{{ $item['preco'] }}">
        @endforeach

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">2. Adicionar bicicletas e acessórios</h5>
            </div>

            <div class="card-body">
                <div class="row g-3 align-items-end">

                    <div class="col-12 col-md-3">
                        <label for="tipo_produto_temp" class="form-label">
                            Tipo de produto
                        </label>

                        <select name="tipo_produto_temp"
                                id="tipo_produto_temp"
                                class="form-select" required>
                            <option value="bicicleta"
                                {{ $tipoSelecionado === 'bicicleta' ? 'selected' : '' }}>
                                Bicicleta
                            </option>

                            <option value="acessorio"
                                {{ $tipoSelecionado === 'acessorio' ? 'selected' : '' }}>
                                Acessório
                            </option>
                        </select>
                    </div>

                    <div class="col-12 col-md-5">
                        <label for="produto_temp_id" class="form-label">Produto</label>

                        <select name="produto_temp_id"
                                id="produto_temp_id"
                                class="form-select">
                            <option value="">Selecione um produto...</option>

                            @if ($tipoSelecionado === 'bicicleta')
                                @foreach ($bicicletas ?? [] as $bicicleta)
                                    <option value="{{ $bicicleta->id }}">
                                        {{ $bicicleta->modelo ?? $bicicleta->nome ?? 'Bicicleta' }}
                                        — R$ {{ number_format($bicicleta->preco ?? 0, 2, ',', '.') }}
                                    </option>
                                @endforeach
                            @else
                                @foreach ($acessorios ?? [] as $acessorio)
                                    <option value="{{ $acessorio->id }}">
                                        {{ $acessorio->nome }}
                                        — R$ {{ number_format($acessorio->preco ?? 0, 2, ',', '.') }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-12 col-md-2">
                        <label for="quantidade_temp" class="form-label">Quantidade</label>

                        <input type="number"
                               name="quantidade_temp"
                               id="quantidade_temp"
                               class="form-control"
                               value="1"
                               min="1"
                               required>
                    </div>

                    {{--selecionar bicicletas--}}
                    <div class="col-12 col-md-2">
                        <button type="submit"
                                name="acao"
                                value="adicionar_item"
                                formaction="{{ route('venda.gerenciar') }}"
                                formmethod="POST"
                                formnovalidate
                                class="btn btn-success w-100">
                            Adicionar
                        </button>
                    </div>

                    <div class="col-12">
                        <button type="submit"
                                name="acao"
                                value="mostrar_produtos"
                                formaction="{{ route('venda.gerenciar') }}"
                                formmethod="POST"
                                formnovalidate
                                class="btn btn-outline-primary btn-sm">
                            Atualizar lista de produtos
                        </button>
                    </div>

                </div>
            </div>
        </div>

        {{-- ITENS COMPRADOS --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                <h5 class="mb-0">3. Itens comprados</h5>

                <span class="badge bg-primary">
                    {{ count($itensVenda) }} item(ns)
                </span>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Produto</th>
                                <th>Tipo</th>
                                <th class="text-center">Quantidade</th>
                                <th class="text-end">Preço unitário</th>
                                <th class="text-end">Subtotal</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($itensVenda as $indice => $item)
                                @php
                                    $quantidade = (int) $item['quantidade'];
                                    $preco = (float) $item['preco'];
                                    $subtotal = $quantidade * $preco;
                                    $valorTotalGeral += $subtotal;
                                @endphp

                                <tr>
                                    <td>{{ $item['nome'] ?? 'Produto' }}</td>

                                    <td>
                                        {{ $item['tipo'] === 'bicicleta' ? 'Bicicleta' : 'Acessório' }}
                                    </td>

                                    <td class="text-center">{{ $quantidade }}</td>

                                    <td class="text-end">
                                        R$ {{ number_format($preco, 2, ',', '.') }}
                                    </td>

                                    <td class="text-end">
                                        R$ {{ number_format($subtotal, 2, ',', '.') }}
                                    </td>

                                    <td class="text-center">
                                        <button type="submit"
                                                name="acao"
                                                value="remover_{{ $indice }}"
                                                formaction="{{ route('venda.gerenciar') }}"
                                                formmethod="POST"
                                                formnovalidate
                                                class="btn btn-danger btn-sm">
                                            Remover
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        Nenhum produto adicionado à venda.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                        <tfoot>
                            <tr class="table-light">
                                <td colspan="4" class="text-end">
                                    <strong>Total da venda:</strong>
                                </td>

                                <td colspan="2" class="text-end">
                                    <strong class="text-success fs-5">
                                        R$ {{ number_format($valorTotalGeral, 2, ',', '.') }}
                                    </strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0">4. Finalizar venda</h5>
            </div>

            <div class="card-body">
                <div class="row align-items-end g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label">Valor total da venda</label>

                        <input type="text"
                               class="form-control form-control-lg fw-bold text-success"
                               value="R$ {{ number_format($valorTotalGeral, 2, ',', '.') }}"
                               readonly>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="d-flex flex-wrap justify-content-md-end gap-2">
                            <a href="{{ route('venda.index') }}"
                               class="btn btn-secondary">
                                Cancelar
                            </a>

                            <button type="submit"
                                    formaction="{{ $action }}"
                                    formmethod="POST"
                                    class="btn btn-success"
                                    {{ count($itensVenda) === 0 ? 'disabled' : '' }}>
                                {{ $editando ? 'Salvar alterações' : 'Registrar venda' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>

@endsection