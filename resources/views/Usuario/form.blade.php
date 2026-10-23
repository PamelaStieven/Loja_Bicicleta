@extends('main')

@section('titulo', isset($dado->id) ? 'Editar Usuário' : 'Cadastrar Usuário')

@section('conteudo')
<div class="container my-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                {{ isset($dado->id) ? 'Editar Usuário' : 'Novo Usuário' }}
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
                $action = isset($dado->id)
                    ? route('usuario.update', $dado->id)
                    : route('usuario.store');
            @endphp

            <form action="{{ $action }}" method="POST">
                @csrf

                @if (isset($dado->id))
                    @method('PUT')
                @endif

                <div class="row g-3">

                    <div class="col-md-6">
                        <label for="nome" class="form-label">
                            Nome completo *
                        </label>
                        <input
                            type="text"
                            name="nome"
                            id="nome"
                            class="form-control"
                            value="{{ old('nome', $dado->nome ?? '') }}"
                            maxlength="255"
                            required
                        >
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">
                            E-mail *
                        </label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            class="form-control"
                            value="{{ old('email', $dado->email ?? '') }}"
                            maxlength="255"
                            required
                        >
                    </div>

                    <div class="col-md-6">
                        <label for="telefone" class="form-label">
                            Telefone *
                        </label>
                        <input
                            type="text"
                            name="telefone"
                            id="telefone"
                            class="form-control"
                            value="{{ old('telefone', $dado->telefone ?? '') }}"
                            maxlength="20"
                            required
                        >
                    </div>

                    <div class="col-md-6">
                        <label for="tipo" class="form-label">
                            Tipo de usuário *
                        </label>
                        <select
                            name="tipo"
                            id="tipo"
                            class="form-select"
                            required
                        >
                            <option value="">Selecione o tipo...</option>

                            <option
                                value="cliente"
                                {{ old('tipo', $dado->tipo ?? '') == 'cliente' ? 'selected' : '' }}
                            >
                                Cliente
                            </option>

                            <option
                                value="funcionario"
                                {{ old('tipo', $dado->tipo ?? '') == 'funcionario' ? 'selected' : '' }}
                            >
                                Funcionário
                            </option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="cpf" class="form-label">
                            CPF *
                        </label>
                        <input
                            type="text"
                            name="cpf"
                            id="cpf"
                            class="form-control"
                            value="{{ old('cpf', $dado->cpf ?? '') }}"
                            maxlength="14"
                            required
                        >
                    </div>

                    <div class="col-md-6">
                        <label for="data_nascimento" class="form-label">
                            Data de nascimento *
                        </label>
                        <input
                            type="date"
                            name="data_nascimento"
                            id="data_nascimento"
                            class="form-control"
                            value="{{ old('data_nascimento', isset($dado->data_nascimento) ? \Illuminate\Support\Carbon::parse($dado->data_nascimento)->format('Y-m-d') : '') }}"
                            required
                        >
                    </div>

                </div>

                <hr class="my-4">

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        {{ isset($dado->id) ? 'Salvar Alterações' : 'Cadastrar Usuário' }}
                    </button>

                    <a href="{{ route('usuario.index') }}" class="btn btn-secondary">
                        Cancelar
                    </a>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection