@extends('main')
@section('titulo', isset($dado->id) ? 'Editar Bicicleta' : 'Nova Bicicleta')

@section('conteudo')
<div class="container my-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">{{ isset($dado->id) ? 'Editar Bicicleta' : 'Nova Bicicleta' }}</h4>
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

            <form action="{{ isset($dado->id) ? route('bicicleta.update', $dado->id) : route('bicicleta.store') }}" method="POST">
                @csrf
                @if(isset($dado->id))
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <!-- Marca -->
                    <div class="col-md-6">
                        <label for="marca" class="form-label">Marca *</label>
                        <input type="text" name="marca" id="marca" class="form-control" value="{{ old('marca', $dado->marca ?? '') }}" required>
                    </div>

                    <!-- Modelo -->
                    <div class="col-md-6">
                        <label for="modelo" class="form-label">Modelo *</label>
                        <input type="text" name="modelo" id="modelo" class="form-control" value="{{ old('modelo', $dado->modelo ?? '') }}" required>
                    </div>

                    <!-- Preço -->
                    <div class="col-md-4">
                        <label for="preco" class="form-label">Preço (R$) *</label>
                        <input type="number" step="0.01" name="preco" id="preco" class="form-control" value="{{ old('preco', $dado->preco ?? '') }}" required>
                    </div>

                    <!-- Cor -->
                    <div class="col-md-4">
                        <label for="cor" class="form-label">Cor</label>
                        <input type="text" name="cor" id="cor" class="form-control" value="{{ old('cor', $dado->cor ?? '') }}">
                    </div>

                    <!-- Aro -->
                    <div class="col-md-2">
                        <label for="aro" class="form-label">Aro</label>
                        <input type="number" name="aro" id="aro" class="form-control" value="{{ old('aro', $dado->aro ?? '') }}">
                    </div>

                    <!-- Quantidade -->
                    <div class="col-md-2">
                        <label for="quantidade" class="form-label">Quantidade</label>
                        <input type="number" name="quantidade" id="quantidade" class="form-control" value="{{ old('quantidade', $dado->quantidade ?? '') }}">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success me-2">Salvar</button>
                    <a href="{{ route('bicicleta.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@stop