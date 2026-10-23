@extends('main')
@section('titulo', isset($dado->id) ? 'Editar  Filial' : 'Novo Filial')

@section('conteudo')
<div class="container my-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">{{ isset($dado->id) ? 'Editar Filial' : 'Novo Filial' }}</h4>
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

            <form action="{{ isset($dado->id) ? route('filiais.update', $dado->id) : route('filiais.store') }}" method="POST">
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
                        <label for="cnpj" class="form-label">Cnpj *</label>
                        <input type="text" name="cnpj" id="cnpj" class="form-control" value="{{ old('cnpj', $dado->cnpj ?? '') }}" placeholder="00.000.000/0000-00" required>
                    </div>

                    <div class="col-md-3">
                        <label for="cidade" class="form-label">Cidade *</label>
                        <input type="text" name="cidade" id="cidade" class="form-control" value="{{ old('cidade', $dado->cidade ?? '') }}" required>
                    </div>

                    <div class="col-md-3">
                        <label for="bairro" class="form-label">Bairro *</label>
                        <input type="text" name="bairro" id="bairro" class="form-control" value="{{ old('bairro', $dado->bairro ?? '') }}" required>
                    </div>

                    <div class="col-md-3">
                        <label for="rua" class="form-label">Rua *</label>
                        <input type="text" name="rua" id="rua" class="form-control" value="{{ old('rua', $dado->rua ?? '') }}" required>
                    </div>

                    <div class="col-md-3">
                        <label for="numero" class="form-label">Número *</label>
                        <input type="text" name="numero" id="numero" class="form-control" value="{{ old('numero', $dado->numero ?? '') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label for="telefone" class="form-label">Telefone *</label>
                        <input type="text" name="telefone" id="telefone" class="form-control" value="{{ old('telefone', $dado->telefone ?? '') }}" placeholder="(00) 00000-0000" required>
                    </div>
                    <div class="col-md-3">
                        <label for="horario_funcionamento" class="form-label">Horário de Funcionamento *</label>
                        <input type="text" name="horario_funcionamento" id="horario_funcionamento" class="form-control" value="{{ old('horario_funcionamento', $dado->horario_funcionamento ?? '') }}" required>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success me-2">Salvar</button>
                    <a href="{{ route('filiais.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
                        <input type="number" name="quantidade" id="quantidade" class="form-control" value="{{ old('quantidade', $dado->quantidade ?? '') }}">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop