@extends('main')
@section('titulo', 'Listagem de Bicicletas')

@section('conteudo')
<div class="container my-4">
    <div class="row mb-3">
        <h3>Listagem de Bicicletas</h3>

        <!-- Formulário de Pesquisa Enfileirado no Topo -->
        <form action="{{ route('bicicleta.search') }}" method="POST">
            @csrf
            <div class="row align-items-end g-2">
                <div class="col-md-3">
                    <label for="tipo" class="form-label">Tipo</label>
                    <select name="tipo" id="tipo" class="form-select">
                        <option value="marca" {{ request('tipo') == 'marca' ? 'selected' : '' }}>Marca</option>
                        <option value="modelo" {{ request('tipo') == 'modelo' ? 'selected' : '' }}>Modelo</option>
                        <option value="cor" {{ request('tipo') == 'cor' ? 'selected' : '' }}>Cor</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="valor" class="form-label">Valor</label>
                    <input type="text" name="valor" id="valor" class="form-control" placeholder="Pesquisar..." value="{{ request('valor') }}">
                </div>

                <div class="col-md-5 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                    <a href="{{ route('bicicleta.index') }}" class="btn btn-secondary">Limpar</a>
                    <a href="{{ route('bicicleta.create') }}" class="btn btn-success ms-auto">Novo</a>
                </div>
            </div>
        </form>
    </div>

    <div class="row mt-4">
        <table class="table table-striped table-hover shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Preço</th>
                    <th>Cor</th>
                    <th>Aro</th>
                    <th>Qtd</th>
                    <th class="text-center" colspan="2">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dados as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->marca }}</td>
                        <td>{{ $item->modelo }}</td>
                        <td>R$ {{ number_format($item->preco, 2, ',', '.') }}</td>
                        <td>{{ $item->cor ?? '-' }}</td>
                        <td>{{ $item->aro ?? '-' }}</td>
                        <td>{{ $item->quantidade ?? '-' }}</td>
                        <td class="text-center" style="width: 80px;">
                            <a href="{{ route('bicicleta.edit', $item->id) }}" class="btn btn-warning btn-sm" title="Editar">Editar</a>
                        </td>
                        <td class="text-center" style="width: 80px;">
                            <form action="{{ route('bicicleta.destroy', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Excluir" onclick="return confirm('Deseja realmente excluir?')">Deletar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">Nenhuma bicicleta encontrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@stop