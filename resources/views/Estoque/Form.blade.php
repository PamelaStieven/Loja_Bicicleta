@extends('main')
@section('titulo', isset($data) && !empty($data->id) ? 'Editar Venda' : 'Registrar Nova Venda')

@section('conteudo')
<div class="container my-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">{{ isset($data->id) ? 'Editar Venda #' . $data->id : 'Formulário Venda' }}</h4>
        </div>
        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @php
                $action = !empty($data->id) ? route('venda.update', $data->id) : route('venda.store');
            @endphp

            <form action="{{ $action }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if (!empty($data->id))
                    @method('PUT')
                @endif

                <input type="hidden" name="id" value="{{ old('id', $data->id ?? '') }}">

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="usuario_id" class="form-label">Usuário (Cliente) *</label>
                        <select name="usuario_id" id="usuario_id" class="form-select" required>
                            <option value="">Selecione o cliente...</option>
                            @foreach($usuarios ?? [] as $usuario)
                                <option value="{{ $usuario->id }}" {{ old('usuario_id', $data->usuario_id ?? '') == $usuario->id ? 'selected' : '' }}>
                                    {{ $usuario->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="funcionario_id" class="form-label">Funcionário *</label>
                        <select name="funcionario_id" id="funcionario_id" class="form-select" required>
                            <option value="">Selecione o funcionário...</option>
                            @foreach($funcionarios ?? [] as $funcionario)
                                <option value="{{ $funcionario->id }}" {{ old('funcionario_id', $data->funcionario_id ?? '') == $funcionario->id ? 'selected' : '' }}>
                                    {{ $funcionario->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <hr class="my-4">
                <h5 class="mb-3">Adicionar Produtos</h5>

                <div class="row g-3 align-items-end mb-3">
                    <div class="col-md-4">
                        <label for="tipo_produto_temp" class="form-label">Tipo de Produto</label>
                        <select name="tipo_produto_temp" id="tipo_produto_temp" class="form-select">
                            <option value="bicicleta">Bicicleta</option>
                            <option value="acessorio">Acessório</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="produto_temp_id" class="form-label">Selecione o Item</label>
                        <select name="produto_temp_id" id="produto_temp_id" class="form-select">
                            <option value="">Selecione o item...</option>
                            <optgroup label="Bicicletas">
                                @foreach($bicicletas ?? [] as $bicicleta)
                                    <option value="{{ $bicicleta->id }}">
                                        [Bicicleta] {{ $bicicleta->modelo ?? $bicicleta->nome }} - R$ {{ number_format($bicicleta->preco ?? 0, 2, ',', '.') }}
                                    </option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Acessórios">
                                @foreach($acessorios ?? [] as $acessorio)
                                    <option value="{{ $acessorio->id }}">
                                        [Acessório] {{ $acessorio->nome }} - R$ {{ number_format($acessorio->preco ?? 0, 2, ',', '.') }}
                                    </option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="quantidade_temp" class="form-label">Quantidade</label>
                        <input type="number" name="quantidade_temp" id="quantidade_temp" class="form-control" value="1" min="1">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" name="acao" value="adicionar_item" formaction="{{ route('venda.gerenciar') }}" formnovalidate class="btn btn-success w-100">Adicionar</button>
                    </div>
                </div>

                <table class="table table-bordered table-striped align-middle mt-3" id="tabela-itens">
                    <thead class="table-light">
                        <tr>
                            <th>Produto</th>
                            <th>Tipo</th>
                            <th>Qtd</th>
                            <th>Preço Unitário</th>
                            <th>Subtotal</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $listaItens = session('itens_venda_temp', []);
                            $valorTotalGeral = 0;
                        @endphp

                        @if(!empty($listaItens) && count($listaItens) > 0)
                            @foreach($listaItens as $index => $item)
                                @php
                                    $subtotal = $item['quantidade'] * $item['preco'];
                                    $valorTotalGeral += $subtotal;
                                @endphp
                                <tr>
                                    <td>
                                        {{ $item['nome'] }}
                                        <input type="hidden" name="itens[{{ $index }}][produto_id]" value="{{ $item['produto_id'] }}">
                                        <input type="hidden" name="itens[{{ $index }}][tipo]" value="{{ $item['tipo'] }}">
                                    </td>
                                    <td class="text-capitalize">{{ $item['tipo'] }}</td>
                                    <td>
                                        <input type="number" name="itens[{{ $index }}][quantidade]" value="{{ $item['quantidade'] }}" class="form-control form-control-sm" readonly>
                                    </td>
                                    <td>
                                        R$ {{ number_format($item['preco'], 2, ',', '.') }}
                                        <input type="hidden" name="itens[{{ $index }}][preco]" value="{{ $item['preco'] }}">
                                    </td>
                                    <td>R$ {{ number_format($subtotal, 2, ',', '.') }}</td>
                                    <td class="text-center">
                                        <button type="submit" name="acao" value="remover_{{ $index }}" formaction="{{ route('venda.gerenciar') }}" formnovalidate class="btn btn-danger btn-sm">Remover</button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">Nenhum item adicionado no momento.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                <div class="row mt-4">
                    <div class="col-md-4 offset-md-8">
                        <label for="valor_total" class="form-label fw-bold">Valor Total (R$) *</label>
                        <input type="text" name="valor_total" id="valor_total" class="form-control fw-bold fs-5" value="{{ number_format($valorTotalGeral ?? 0, 2, '.', '') }}" readonly required>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success me-2">Salvar</button>
                    <a href="{{ route('venda.index') }}" class="btn btn-secondary">Voltar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@stop