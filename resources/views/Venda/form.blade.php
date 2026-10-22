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
                                <option value="{{ $usuario->id }}" {{ (isset($venda) && $venda->usuario_id == $usuario->id) ? 'selected' : '' }}>
                                    {{ $usuario->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Funcionário *</label>
                        <select name="funcionario_id" class="form-select" required>
                            <option value="">Selecione o funcionário...</option>
                            @foreach($funcionarios ?? [] as $func)
                                <option value="{{ $func->id }}" {{ (isset($venda) && $venda->funcionario_id == $func->id) ? 'selected' : '' }}>
                                    {{ $func->nome }}
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
                        <select id="tipo_produto" class="form-select">
                            <option value="bicicleta">Bicicleta</option>
                            <option value="acessorio">Acessório</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Selecione o Item</label>
                        <select id="produto_select" class="form-select">
                            <!-- Preenchido via JS ou foreach -->
                            <option value="">Selecione...</option>
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
                        {{-- Linhas inseridas dinamicamente pelo usuário --}}
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
@endsection