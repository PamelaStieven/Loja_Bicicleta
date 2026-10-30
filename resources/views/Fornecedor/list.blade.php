@extends('main')
@section('titulo', 'Listagem de Fornecedores')

@section('conteudo')
<div class="container my-4">
<div class="row mb-3">
    <h3>Listagem de Fornecedores</h3>
  <form action="{{ route('fornecedor.search') }}" method="POST">
    @csrf
    <div class="row align-items-end g-2">
        <div class="col-md-3">
            <label for="tipo" class="form-label">Tipo</label>
            <select name="tipo" id="tipo" class="form-select">
                <option value="nome" {{ request('tipo') == 'nome' ? 'selected' : '' }}>Nome</option>
                <option value="produto" {{ request('tipo') == 'produto' ? 'selected' : '' }}>Produto</option>
                <option value="email" {{ request('tipo') == 'email' ? 'selected' : '' }}>EMAIL</option>
                <option value="telefone" {{ request('tipo') == 'telefone' ? 'selected' : '' }}>Telefone</option>
                <option value="endereco" {{ request('tipo') == 'endereco' ? 'selected' : '' }}>Endereço</option>
                <option value="cnpj" {{ request('tipo') == 'cnpj' ? 'selected' : '' }}>CNPJ</option>
                <option value="prazo_entrega" {{ request('tipo') == 'prazo_entrega' ? 'selected' : '' }}>Prazo de Entrega</option>
                <option value="valor" {{ request('tipo') == 'valor' ? 'selected' : '' }}>Valor</option>
                <option value="status" {{ request('tipo') == 'status' ? 'selected' : '' }}>Status</option>
                <option value="link_site" {{ request('tipo') == 'link_site' ? 'selected' : '' }}>Link do Site</option>
                <option value="redes_sociais" {{ request('tipo') == 'redes_sociais' ? 'selected' : '' }}>Redes Sociais</option>
            </select>
        </div>

        <div class="col-md-4">
            <label for="valor" class="form-label">Valor</label>
            <input type="text" name="valor" id="valor" class="form-control" placeholder="Pesquisar..." value="{{ request('valor') }}">
        </div>

        <div class="col-md-5 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Buscar</button>
            
            <a href="{{ route('fornecedor.index') }}" class="btn btn-secondary">Limpar</a>
            <a href="{{ route('fornecedor.create') }}" class="btn btn-success ms-auto">Novo</a>
            <a href="{{ route('fornecedor.report') }}" class="btn btn-info text-white">Relatório</a>
            <a href="{{ route('fornecedor.chart') }}" class="btn btn-warning"> Gráfico </a>
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
                <th>Produto</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Endereço</th>
                <th>CNPJ</th>
                <th>Prazo de Entrega</th>
                <th>Valores</th>
                <th>Status</th>
                <th>Link do Site</th>
                <th>Redes Sociais</th>
                <th class="text-center">Ações Editar</th>
                <th class="text-center">Ações Excluir</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dados as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->nome }}</td>
                    <td>{{ $item->produto }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->telefone }}</td>
                    <td>{{ $item->endereco }}</td>
                    <td>{{ $item->cnpj }}</td>
                    <td>{{ $item->prazo_entrega }}</td>
                    <td>R$ {{ number_format($item->valor, 2, ',', '.') }}</td>
                    <td>{{ $item->status }}</td>
                    <td>
                        @if($item->link_site)
                            <a href="{{ $item->link_site }}" target="_blank">{{ $item->link_site }}</a>
                        @else
                            -
                        @endif
                    </td>   
                    <td>{{ $item->redes_sociais }}</td>
                    <td class="text-center">
                        <a
                            href="{{ route('fornecedor.edit', $item->id) }}"
                            class="btn btn-warning btn-sm"
                            title="Editar"
                        >
                            Editar
                        </a>
                    </td>
                    <td>
                        <form
                            action="{{ route('fornecedor.destroy', $item->id) }}"
                            method="POST"
                            class="d-inline"
                        >
                            @csrf
                            @method('DELETE')
                            <button
                                type="submit"
                                class="btn btn-danger btn-sm"
                                title="Excluir"
                                onclick="return confirm('Deseja realmente excluir este fornecedor?')"
                            >
                                Deletar
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">
                        Nenhum fornecedor encontrado.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
</div>
@endsection