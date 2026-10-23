@extends('main')
@section('titulo', 'Controle de Estoque')

@section('conteudo')
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h3>Lista de Estoque</h3>
    </div>

    <form action="{{ route('estoque.search') }}" method="POST" class="d-flex gap-2 my-3">
        @csrf
        <select name="tipo" class="form-select w-auto">
            <option value="produto" {{ old('tipo', request('tipo')) == 'produto' ? 'selected' : '' }}>Produto</option>
            <option value="quantidade" {{ old('tipo', request('tipo')) == 'quantidade' ? 'selected' : '' }}>Quantidade</option>
            <option value="preco" {{ old('tipo', request('tipo')) == 'preco' ? 'selected' : '' }}>Preço</option>
        </select>

        <input 
            type="text" 
            name="valor" 
            class="form-control" 
            placeholder="Digite para pesquisar..." 
            value="{{ old('valor', request('valor')) }}"
        >

        <button type="submit" class="btn btn-primary">Buscar</button>
        <a href="{{ route('estoque.index') }}" class="btn btn-secondary">Limpar</a>
        <a href="{{ route('estoque.create') }}" class="btn btn-success">Novo</a>
        <a href="{{ route('estoque.chart') }}" class="btn btn-info">Gráfico</a>
    </form>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped table-hover shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Produto</th>
                <th>Quantidade</th>
                <th>Preço</th>
                <th class="text-center">Ações</th>
                <th class="text-center">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($estoques as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->produto }}</td>
                    <td>{{ $item->quantidade }}</td>
                    <td>R$ {{ number_format($item->preco, 2, ',', '.') }}</td>
                    <td class="text-center">
                        <a 
                            href="{{ route('estoque.edit', $item->id) }}" 
                            class="btn btn-warning btn-sm" 
                            title="Editar"
                        >
                            Editar
                        </a>
                    </td>
                    <td class="text-center">
                        <form action="{{ route('estoque.destroy', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tens a certeza que queres apagar?')">Deletar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Nenhum produto registado no estoque.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@stop