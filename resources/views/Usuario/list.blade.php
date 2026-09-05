@extends('main')
@section('titulo', 'Listagem de Usuários')

@section('conteudo')
<div class="container my-4">
<div class="row mb-3">
    <h3>Listagem de Usuários</h3>
    <form action="{{ route('usuario.index') }}" method="GET">
        <div class="row align-items-end g-2">
            <div class="col-md-3">
                <label for="tipo" class="form-label">Tipo</label>
                <select name="tipo" id="tipo" class="form-select">
                    <option value="nome" {{ request('tipo') == 'nome' ? 'selected' : '' }}>Nome</option>
                    <option value="email" {{ request('tipo') == 'email' ? 'selected' : '' }}>Email</option>
                    <option value="telefone" {{ request('tipo') == 'telefone' ? 'selected' : '' }}>Telefone</option>
                    <option value="cpf" {{ request('tipo') == 'cpf' ? 'selected' : '' }}>CPF</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="valor" class="form-label">Valor</label>
                <input
                    type="text"
                    name="valor"
                    id="valor"
                    class="form-control"
                    placeholder="Digite para pesquisar..."
                    value="{{ request('valor') }}">
            </div>
            <div class="col-md-5 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Buscar</button>
                <a href="{{ route('usuario.index') }}" class="btn btn-secondary">Limpar</a>
                <a href="{{ route('usuario.create') }}" class="btn btn-success ms-auto">Novo</a>
            </div>
        </div>
    </form>
</div>
<div class="row mt-4">
    <table class="table table-striped table-hover shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Tipo</th>
                <th>CPF</th>
                <th class="text-center" colspan="2">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($dados as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->nome }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->telefone }}</td>
                    <td>
                        <span class="badge {{ $item->tipo == 'funcionario' ? 'bg-primary' : 'bg-success' }}">
                            {{ ucfirst($item->tipo ?? 'cliente') }}
                        </span>
                    </td>
                    <td>{{ $item->cpf ?? '-' }}</td>
                    <td class="text-center" style="width: 80px;">
                        <a href="{{ route('usuario.edit', $item->id) }}" class="btn btn-warning btn-sm" title="Editar">Editar</a>
                    </td>
                    <td class="text-center" style="width: 80px;">
                        <form action="{{ route('usuario.destroy', $item->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button
                                type="submit"
                                class="btn btn-danger btn-sm"
                                title="Excluir"
                                onclick="return confirm('Deseja realmente excluir este usuário?')">
                                Deletar
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Nenhum usuário encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
</div>
@endsection
