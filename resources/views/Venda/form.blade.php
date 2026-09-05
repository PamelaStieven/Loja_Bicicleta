@extends('main')
@section('titulo', isset($venda) ? 'Editar Venda' : 'Nova Venda')

@section('conteudo')
<div class="container my-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">{{ isset($venda) ? 'Editar Venda #' . $venda->id : 'Registrar Nova Venda' }}</h4>
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

            <form action="{{ isset($venda) ? route('venda.update', $venda->id) : route('venda.store') }}" method="POST">
                @csrf
                @if(isset($venda))
                    @method('PUT')
                @endif

                <div class="row g-3">
                    {{-- Usuário --}}
                    <div class="col-md-6">
                        <label for="usuario_id" class="form-label">Usuário *</label>
                        <select name="usuario_id" id="usuario_id" class="form-select" required>
                            <option value="">Selecione o Usuário...</option>
                            @foreach($usuarios as $user)
                                <option value="{{ $user->id }}" {{ (old('usuario_id', $venda->usuario_id ?? '') == $user->id) ? 'selected' : '' }}>
                                    {{ $user->nome }} ({{ $user->cpf ?? 'Sem CPF' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Funcionário --}}
                    <div class="col-md-6">
                        <label for="funcionario_id" class="form-label">Funcionário *</label>
                        <select name="funcionario_id" id="funcionario_id" class="form-select" required>
                            <option value="">Selecione o Funcionário...</option>
                            @foreach($funcionarios as $funcionario)
                                <option value="{{ $funcionario->id }}" {{ (old('funcionario_id', $venda->funcionario_id ?? '') == $funcionario->id) ? 'selected' : '' }}>
                                    {{ $funcionario->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Bicicleta --}}
                    <div class="col-md-6">
                        <label for="bicicleta_id" class="form-label">Bicicleta (Opcional)</label>
                        <select name="bicicleta_id" id="bicicleta_id" class="form-select">
                            <option value="">Selecione se for bicicleta...</option>
                            @foreach($bicicletas as $bici)
                                <option value="{{ $bici->id }}" {{ (old('bicicleta_id', $venda->bicicleta_id ?? '') == $bici->id) ? 'selected' : '' }}>
                                    {{ $bici->modelo ?? $bici->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Acessório --}}
                    <div class="col-md-6">
                        <label for="acessorio_id" class="form-label">Acessório (Opcional)</label>
                        <select name="acessorio_id" id="acessorio_id" class="form-select">
                            <option value="">Selecione se for acessório...</option>
                            @foreach($acessorios as $acess)
                                <option value="{{ $acess->id }}" {{ (old('acessorio_id', $venda->acessorio_id ?? '') == $acess->id) ? 'selected' : '' }}>
                                    {{ $acess->nome }} - R$ {{ $acess->preco }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Quantidade --}}
                    <div class="col-md-4">
                        <label for="quantidade" class="form-label">Quantidade *</label>
                        <input type="number" name="quantidade" id="quantidade" class="form-control" value="{{ old('quantidade', $venda->quantidade ?? 1) }}" min="1" required>
                    </div>

                    {{-- Valor Total --}}
                    <div class="col-md-4">
                        <label for="valor_total" class="form-label">Valor Total (R$) *</label>
                        <input type="number" step="0.01" name="valor_total" id="valor_total" class="form-control" value="{{ old('valor_total', $venda->valor_total ?? '') }}" required>
                    </div>

                    {{-- Data --}}
                    <div class="col-md-4">
                        <label for="data_venda" class="form-label">Data da Venda *</label>
                        <input type="date" name="data_venda" id="data_venda" class="form-control" value="{{ old('data_venda', isset($venda) ? \Carbon\Carbon::parse($venda->data_venda)->format('Y-m-d') : date('Y-m-d')) }}" required>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success me-2">{{ isset($venda) ? 'Atualizar Venda' : 'Salvar Venda' }}</button>
                    <a href="{{ route('venda.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@stop