@extends('main')
@section('titulo', isset($estoque->id) ? 'Editar Estoque' : 'Novo Produto em Estoque')

@section('conteudo')
<div class="container my-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">{{ isset($estoque->id) ? 'Editar Produto' : 'Novo Produto' }}</h4>
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

            <form action="{{ isset($estoque->id) ? route('estoque.update', $estoque->id) : route('estoque.store') }}" method="POST">
                @csrf
                @if(isset($estoque->id))
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <div class="col-md-12">
                        <label for="produto" class="form-label">Nome do Produto *</label>
                        <input type="text" name="produto" id="produto" class="form-control" value="{{ old('produto', $estoque->produto ?? '') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="quantidade" class="form-label">Quantidade *</label>
                        <input type="number" name="quantidade" id="quantidade" class="form-control" value="{{ old('quantidade', $estoque->quantidade ?? '') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="preco" class="form-label">Preço (R$) *</label>
                        <input type="number" step="0.01" name="preco" id="preco" class="form-control" value="{{ old('preco', $estoque->preco ?? '') }}" required>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success me-2">Salvar</button>
                    <a href="{{ route('estoque.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection