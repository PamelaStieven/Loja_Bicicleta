@extends('main')
@section('titulo', 'Listagem de Vendas')

@section('conteudo')
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h3>Vendas Registradas</h3>
    </div>

    <form action="{{ route('venda.search') }}" method="POST" class="d-flex gap-2 my-3">
        @csrf
        <select name="tipo" class="form-select w-auto">
            <option value="usuario" {{ old('tipo', request('tipo')) == 'usuario' ? 'selected' : '' }}>Usuário</option>
            <option value="funcionario" {{ old('tipo', request('tipo')) == 'funcionario' ? 'selected' : '' }}>Funcionário</option>
            <option value="quantidade" {{ old('tipo', request('tipo')) == 'quantidade' ? 'selected' : '' }}>Quantidade de Itens</option>
            <option value="data" {{ old('tipo', request('tipo')) == 'data' ? 'selected' : '' }}>Data (AAAA-MM-DD)</option>
        </select>

        <input 
            type="text" 
            name="valor" 
            class="form-control" 
            placeholder="Digite para pesquisar..." 
            value="{{ old('valor', request('valor')) }}"
        >

        <button type="submit" class="btn btn-primary">Buscar</button>
        <a href="{{ route('venda.index') }}" class="btn btn-secondary">Limpar</a>
        <a href="{{ route('venda.create') }}" class="btn btn-success ">Novo</a>
        <a href="{{ route('venda.chart') }}" class="btn btn-info">Gráfico</a>
        <a href="{{ route('venda.report') }}" class="btn btn-warning">Relatórios</a>
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
                <th>Qtd de Itens</th>
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
                    
                    <td>{{ $item->itens->sum('quantidade') }}</td>
                    
                    <td>R$ {{ number_format($item->valor_total, 2, ',', '.') }}</td>
                    <td>{{ $item->data_venda ? \Carbon\Carbon::parse($item->data_venda)->format('d/m/Y') : '-' }}</td>
                    <td class="text-center">
                        <a 
                            href="{{ route('venda.edit', $item->id) }}" 
                            class="btn btn-warning btn-sm" 
                            title="Editar"
                        >
                            Editar
                        </a>

                        <a 
                            href="{{ route('venda.itens', $item->id) }}" 
                            class="btn btn-info btn-sm text-white" 
                            title="Ver Itens"
                        >
                            Itens
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
                    <td colspan="7" class="text-center">Nenhuma venda registrada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@stop