@extends('main')
@section('titulo', isset($dado->id) ? 'Editar Acessório' : 'Novo Acessório')

@section('conteudo')
<div class="container my-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">{{ isset($dado->id) ? 'Editar Acessório' : 'Novo Acessório' }}</h4>
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

            <form action="{{ isset($dado->id) ? route('acessorios.update', $dado->id) : route('acessorios.store') }}" method="POST">
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
                        <label for="preco" class="form-label">Preço (R$) *</label>
                        <input type="number" step="0.01" name="preco" id="preco" class="form-control" value="{{ old('preco', $dado->preco ?? '') }}" required>
                    </div>

                    <div class="col-md-3">
                        <label for="quantidade" class="form-label">Quantidade</label>
                        <input type="number" name="quantidade" id="quantidade" class="form-control" value="{{ old('quantidade', $dado->quantidade ?? '') }}">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success me-2">Salvar</button>
                    <a href="{{ route('acessorios.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@stop