@extends('main')
@section('titulo', isset($dado->id) ? 'Editar Fornecedor' : 'Novo Fornecedor')

@section('conteudo')
<div class="container my-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">{{ isset($dado->id) ? 'Editar Fornecedor' : 'Novo Fornecedor' }}</h4>
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

            <form action="{{ isset($dado->id) ? route('fornecedor.update', $dado->id) : route('fornecedor.store') }}" method="POST">
                @csrf
                @if(isset($dado->id))
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nome" class="form-label">Nome *</label>
                        <input type="text" name="nome" id="nome" class="form-control" value="{{ old('nome', $dado->nome ?? '') }}" required>
                    </div>

                    <div class="col-md-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $dado->email ?? '') }}" required>
                    </div>

                    <div class="col-md-3">
                        <label for="produto" class="form-label">Produto *</label>
                        <input type="text" name="produto" id="produto" class="form-control" value="{{ old('produto', $dado->produto ?? '') }}" required>
                    </div>

                    <div class="col-md-3">
                        <label for="telefone" class="form-label">Telefone *</label>
                        <input type="text" name="telefone" id="telefone" class="form-control" value="{{ old('telefone', $dado->telefone ?? '') }}" placeholder="(00) 00000-0000" required>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="endereco" class="form-label">Endereço *</label>
                        <input type="text" name="endereco" id="endereco" class="form-control" value="{{ old('endereco', $dado->endereco ?? '') }}" required>
                    </div><!-- Fechamento da div do endereço corrigido aqui -->

                    <div class="col-md-3">
                        <label for="cnpj" class="form-label">CNPJ *</label>
                        <input type="text" name="cnpj" id="cnpj" class="form-control" value="{{ old('cnpj', $dado->cnpj ?? '') }}" placeholder="00.000.000/0000-00" required>
                    </div>
                 
                    <div class="col-md-3">
                        <label for="prazo_entrega" class="form-label">Prazo de Entrega (dias)</label>
                        <input type="number" name="prazo_entrega" id="prazo_entrega" class="form-control" value="{{ old('prazo_entrega', $dado->prazo_entrega ?? '') }}" min="0">
                    </div>
                
                    <div class="col-md-3">
                        <label for="valor" class="form-label">Valor (R$)</label>
                        <input type="number" name="valor" id="valor" class="form-control" value="{{ old('valor', $dado->valor ?? '') }}" min="0" step="0.01">
                    </div>  

                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Selecione um status</option>
                            <option value="Ativo" {{ old('status', $dado->status ?? '') == 'Ativo' ? 'selected' : '' }}>Ativo</option>
                            <option value="Inativo" {{ old('status', $dado->status ?? '') == 'Inativo' ? 'selected' : '' }}>Inativo</option>
                        </select>   
                    </div>

                    <div class="col-md-3">
                        <label for="link_site" class="form-label">Link do Site</label>
                        <input type="url" name="link_site" id="link_site" class="form-control" value="{{ old('link_site', $dado->link_site ?? '') }}" placeholder="https://www.exemplo.com">
                    </div>

                    <div class="col-md-3">
                        <label for="redes_sociais" class="form-label">Redes Sociais</label>
                        <input type="text" name="redes_sociais" id="redes_sociais" class="form-control" value="{{ old('redes_sociais', $dado->redes_sociais ?? '') }}" placeholder="@exemplo">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success me-2">Salvar</button>
                    <a href="{{ route('fornecedor.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@stop