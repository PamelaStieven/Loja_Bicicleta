@extends('main')
@section('titulo', 'Listagem de Vendas')

@section('conteudo')
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Vendas Registradas</h3>
        <a href="{{ route('venda.create') }}" class="btn btn-primary">+ Nova Venda</a>
    </div>

    {{-- Barra de Busca --}}
    <form action="{{ route('venda.index') }}" method="GET" class="d-flex gap-2 my-3">
        <select name="tipo" class="form-select w-auto">
            <option value="usuario" {{ request('tipo') == 'usuario' ? 'selected' : '' }}>Usuário</option>
            <option value="funcionario" {{ request('tipo') == 'funcionario' ? 'selected' : '' }}>Funcionário</option>
            <option value="item" {{ request('tipo') == 'item' ? 'selected' : '' }}>Item</option>
            <option value="quantidade" {{ request('tipo') == 'quantidade' ? 'selected' : '' }}>Quantidade</option>
            <option value="data" {{ request('tipo') == 'data' ? 'selected' : '' }}>Data</option>
        </select>

        <input 
            type="text" 
            name="valor" 
            class="form-control" 
            placeholder="Digite para pesquisar..." 
            value="{{ request('valor') }}"
        >

        <button type="submit" class="btn btn-primary">Buscar</button>
        <a href="{{ route('venda.index') }}" class="btn btn-secondary">Limpar</a>
    </form>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped table-hover shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Usuário</th>
                <th>Funcionário</th>
                <th>Item</th>
                <th>Qtd</th>
                <th>Valor Total</th>
                <th>Data</th>
                <th class="text-center">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dados as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->usuario->nome ?? '-' }}</td>
                    <td>{{ $item->funcionario->nome ?? '-' }}</td>
                    <td>
                        @if($item->bicicleta)
                            Bicicleta: {{ $item->bicicleta->modelo ?? $item->bicicleta->nome ?? '' }}
                        @elseif($item->acessorio)
                            Acessório: {{ $item->acessorio->nome }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $item->quantidade }}</td>
                    <td>R$ {{ number_format($item->valor_total, 2, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->data_venda)->format('d/m/Y') }}</td>
                    <td class="text-center">
                        <a 
                            href="{{ route('venda.edit', $item->id) }}" 
                            class="btn btn-warning btn-sm" 
                            title="Editar"
                        >
                            Editar
                        </a>
                        <form action="{{ route('venda.destroy', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Excluir venda?')">Excluir</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Nenhuma venda registrada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@stop