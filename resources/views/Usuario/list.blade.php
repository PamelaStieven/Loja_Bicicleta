@extends('main')
@section('titulo', 'Listagem de Usuários')

@section('conteudo')
<br><br>
    <div class="row">
        <h3>Listagem de Usuários</h3>
        
        <form action="{{ route('usuario.index') }}" method="GET" class="d-flex gap-2 my-3">
            <select name="tipo" class="form-select w-auto">
                <option value="nome" {{ request('tipo') == 'nome' ? 'selected' : '' }}>Nome</option>
                <option value="email" {{ request('tipo') == 'email' ? 'selected' : '' }}>Email</option>
                <option value="telefone" {{ request('tipo') == 'telefone' ? 'selected' : '' }}>Telefone</option>
                <option value="cpf" {{ request('tipo') == 'cpf' ? 'selected' : '' }}>CPF</option>
            </select>

            <input 
                type="text" 
                name="valor" 
                class="form-control" 
                placeholder="Digite para pesquisar..." 
                value="{{ request('valor') }}"
            >

            <button type="submit" class="btn btn-primary">Buscar</button>
            <!-- Corrigido para limpar a busca de usuario -->
            <a href="{{ route('usuario.index') }}" class="btn btn-secondary">Limpar</a>
            <!-- Botão para cadastrar novo usuario -->
            <a href="{{ route('usuario.create') }}" class="btn btn-success">Novo</a>
        </form>
    </div>

    <div class="row mt-4">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Email</th>
                    <th scope="col">Telefone</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">CPF</th>
                    <th scope="col" class="text-center" colspan="2">Ações</th>             
                </tr>
            </thead>
            <tbody>
                @forelse ($dados as $item)
                    <tr>
                        <th scope="row">{{ $item->id }}</th>
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
                            <a class="btn btn-warning btn-sm" title="Editar" href="{{ route('usuario.edit', $item->id) }}">Editar</a>
                        </td>
                        <td class="text-center" style="width: 80px;">
                            <form action="{{ route('usuario.destroy', $item->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Excluir"
                                    onclick="return confirm('Deseja realmente excluir este usuário?')">Deletar</button>
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
@stop