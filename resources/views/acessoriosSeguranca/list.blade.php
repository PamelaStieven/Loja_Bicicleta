@extends('main')
@section('titulo', 'Listagem de Acessórios de Segurança')

@section('conteudo')
<div class="container my-4">
<div class="row mb-3">
    <h3>Listagem de Acessórios de Segurança</h3>
  <form action="{{ route('acessoriosSeguranca.search') }}" method="POST">
    @csrf
    <div class="row align-items-end g-2">
        <div class="col-md-3">
            <label for="tipo" class="form-label">Tipo</label>
            <select name="tipo" id="tipo" class="form-select">
                <option value="nome" {{ request('tipo') == 'nome' ? 'selected' : '' }}>Nome</option>
                <option value="marca" {{ request('tipo') == 'marca' ? 'selected' : '' }}>Marca</option>
                <opcache_compile_filetion value="quantidade" {{ request('tipo') == 'quantidade' ? 'selected' : '' }}>Quantidade</option>
                <option value="tamanho" {{ request('tipo') == 'tamanho' ? 'selected' : '' }}>Tamanho</option>
                <option value="estoque" {{ request('tipo') == 'estoque' ? 'selected' : '' }}>Estoque</option>
                <option value="preco" {{ request('tipo') == 'preco' ? 'selected' : '' }}>Preço</option>
            </select>
        </div>

        <div class="col-md-4">
            <label for="valor" class="form-label">Valor</label>
            <input type="text" name="valor" id="valor" class="form-control" placeholder="Pesquisar..." value="{{ request('valor') }}">
        </div>

        <div class="col-md-5 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Buscar</button>
            <a href="{{ route('acessoriosSeguranca.index') }}" class="btn btn-secondary">Limpar</a>
            <a href="{{ route('acessoriosSeguranca.create') }}" class="btn btn-success ms-auto">Novo</a>
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
                <th>Marca</th>
                <th>Quantidade</th>
                <th>Tamanho</th>
                <th>Preço</th>
                <th>Estoque</th>
                <th class="text-center">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dados as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->nome }}</td>
                    <td>{{ $item->marca ?? '-' }}</td>
                    <td>{{ $item->quantidade ?? '-' }}</td>
                    <td>{{ $item->tamanho ?? '-' }}</td>
                    <td>R$ {{ number_format($item->preco, 2, ',', '.') }}</td>
                    <td>{{ $item->estoque ?? '-' }}</td>
                    <td class="text-center">
                        <a
                            href="{{ route('acessoriosSeguranca.edit', $item->id) }}"
                            class="btn btn-warning btn-sm"
                            title="Editar"
                        >
                            Editar
                        </a>
                        <form
                            action="{{ route('acessoriosSeguranca.destroy', $item->id) }}"
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
