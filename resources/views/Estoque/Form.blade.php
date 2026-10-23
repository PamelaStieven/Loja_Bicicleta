@extends('main')

@section('titulo', isset($estoque) && $estoque->exists ? 'Editar Estoque' : 'Cadastrar Produto no Estoque')

@section('conteudo')
<div class="container my-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                {{ isset($estoque) && $estoque->exists ? 'Editar Produto do Estoque' : 'Cadastro de Estoque' }}
            </h4>
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
                $action = isset($estoque) && $estoque->exists
                    ? route('estoque.update', $estoque->id)
                    : route('estoque.store');
            @endphp

            <form action="{{ $action }}" method="POST">
                @csrf

                @if (isset($estoque) && $estoque->exists)
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label for="produto" class="form-label">
                        Nome do Produto *
                    </label>

                    <input
                        type="text"
                        name="produto"
                        id="produto"
                        class="form-control"
                        value="{{ old('produto', $estoque->produto ?? '') }}"
                        placeholder="Digite o nome do produto"
                        maxlength="255"
                        required
                    >
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="quantidade" class="form-label">
                            Quantidade em Estoque *
                        </label>

                        <input
                            type="number"
                            name="quantidade"
                            id="quantidade"
                            class="form-control"
                            value="{{ old('quantidade', $estoque->quantidade ?? 0) }}"
                            min="0"
                            step="1"
                            required
                        >
                    </div>

                    <div class="col-md-6">
                        <label for="preco" class="form-label">
                            Preço Unitário (R$) *
                        </label>

                        <input
                            type="number"
                            name="preco"
                            id="preco"
                            class="form-control"
                            value="{{ old('preco', $estoque->preco ?? '') }}"
                            placeholder="0,00"
                            min="0"
                            step="0.01"
                            required
                        >
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        {{ isset($estoque) && $estoque->exists ? 'Salvar Alterações' : 'Cadastrar Produto' }}
                    </button>

                    <a href="{{ route('estoque.index') }}" class="btn btn-secondary">
                        Voltar
                    </a>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection