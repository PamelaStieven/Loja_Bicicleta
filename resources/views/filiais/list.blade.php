@extends('main')
@section('titulo', 'Listagem de Filiais')

@section('conteudo')
<div class="container my-4">
<div class="row mb-3">
    <h3>Listagem de Filiais</h3>
  <form action="{{ route('filiais.search') }}" method="POST">
    @csrf
    <div class="row align-items-end g-2">
        <div class="col-md-3">
            <label for="tipo" class="form-label">Tipo</label>
            <select name="tipo" id="tipo" class="form-select">
                <option value="nome" {{ request('tipo') == 'nome' ? 'selected' : '' }}>Nome</option>
                <option value="cnpj" {{ request('tipo') == 'cnpj' ? 'selected' : '' }}>CNPJ</option>
                <option value="cidade" {{ request('tipo') == 'cidade' ? 'selected' : '' }}>Cidade</option>
                <option value="bairro" {{ request('tipo') == 'bairro' ? 'selected' : '' }}>Bairro</option>
                <option value="rua" {{ request('tipo') == 'rua' ? 'selected' : '' }}>Rua</option>
                <option value="numero" {{ request('tipo') == 'numero' ? 'selected' : '' }}>Número</option>
                <option value="telefone" {{ request('tipo') == 'telefone' ? 'selected' : '' }}>Telefone</option>
                <option value="horario_funcionamento" {{ request('tipo') == 'horario_funcionamento' ? 'selected' : '' }}>Horário de Funcionamento</option>

            </select>
        </div>

        <div class="col-md-4">
            <label for="valor" class="form-label">Valor</label>
            <input type="text" name="valor" id="valor" class="form-control" placeholder="Pesquisar..." value="{{ request('valor') }}">
        </div>

        <div class="col-md-5 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Buscar</button>
            <a href="{{ route('filiais.index') }}" class="btn btn-secondary">Limpar</a>
            <a href="{{ route('filiais.create') }}" class="btn btn-success ms-auto">Novo</a>
        </div>
    </div>
</form>
</div>
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
<div class="row mt-4">
    <table class="table table-striped table-hover shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>CNPJ</th>
                <th>Cidade</th>
                <th>Bairro</th>
                <th>Rua</th>
                <th>Número</th>
                <th>Telefone</th>
                <th>Horário de Funcionamento</th>
                <th class="text-center">Ações</th>
                <th class="text-center">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dados as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->nome }}</td>
                    <td>{{ $item->cnpj ?? '-' }}</td>
                    <td>{{ $item->cidade ?? '-' }}</td>
                    <td>{{ $item->bairro ?? '-' }}</td>
                    <td>{{ $item->rua ?? '-' }}</td>
                    <td>{{ $item->numero ?? '-' }}</td>
                    <td>{{ $item->telefone ?? '-' }}</td>
                    <td>{{ $item->horario_funcionamento ?? '-' }}</td>
                    <td class="text-center">
                        <a
                            href="{{ route('filiais.edit', $item->id) }}"
                            class="btn btn-warning btn-sm"
                            title="Editar"
                        >
                            Editar
                        </a>
                        <td class="text-center">
                        
                            <form
                                action="{{ route('filiais.destroy', $item->id) }}"
                                method="POST"
                                class="d-inline"
                            >
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="btn btn-danger btn-sm"
                                    title="Excluir"
                                    onclick="return confirm('Deseja realmente excluir?')"
                                >
                                    Deletar
                                </button>
                            </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">
                        Nenhum acessório encontrado.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
</div>
@endsection
