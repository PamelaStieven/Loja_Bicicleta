@extends('main')
@section('titulo', 'Formulário de Usuário')

@section('conteudo')
<div class="container my-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">{{ isset($dado->id) ? 'Editar Usuário' : 'Novo Usuário' }}</h4>
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

            @if(isset($dado->id))
                <form action="{{ route('usuario.update', $dado->id) }}" method="POST">
                @method('PUT')
            @else
                <form action="{{ route('usuario.store') }}" method="POST">
            @endif
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nome" class="form-label">Nome *</label>
                        <input type="text" name="nome" id="nome" class="form-control" value="{{ old('nome', $dado->nome ?? '') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">E-mail *</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $dado->email ?? '') }}" required>
                    </div>

                    <div class="col-md-4">
                        <label for="telefone" class="form-label">Telefone</label>
                        <input type="text" name="telefone" id="telefone" class="form-control" value="{{ old('telefone', $dado->telefone ?? '') }}">
                    </div>

                    <div class="col-md-4">
                        <label for="tipo" class="form-label">Tipo *</label>
                        <select name="tipo" id="tipo" class="form-select" required>
                            <option value="usuario" {{ old('tipo', $dado->tipo ?? '') == 'usuario' ? 'selected' : '' }}>Usuário</option>
                            <option value="funcionario" {{ old('tipo', $dado->tipo ?? '') == 'funcionario' ? 'selected' : '' }}>Funcionário</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="cpf" class="form-label">CPF</label>
                        <input type="text" name="cpf" id="cpf" class="form-control" value="{{ old('cpf', $dado->cpf ?? '') }}">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success me-2">Salvar</button>
                    <a href="{{ route('usuario.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@stop