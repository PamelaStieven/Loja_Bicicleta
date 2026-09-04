@extends('main')
@section('titulo', 'Listagem de Vendas')

@section('conteudo')
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Vendas Registradas</h3>
        <a href="{{ route('venda.create') }}" class="btn btn-primary">+ Nova Venda</a>
    </div>

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
                <th>Ações</th>
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
                    <td>
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