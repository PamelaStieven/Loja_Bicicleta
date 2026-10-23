@extends('main')
@section('titulo', 'Controle de Estoque')

@section('conteudo')
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Lista de Estoque</h3>
        <div>
            <!-- Botão para o Relatório/Gráfico -->
            <a href="{{ route('estoque.chart') }}" class="btn btn-info text-white me-2">Gráfico</a>
            <a href="{{ route('estoque.create') }}" class="btn btn-success">Novo Produto</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Preço</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($estoques as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->produto }}</td>
                        <td>{{ $item->quantidade }}</td>
                        <td>R$ {{ number_format($item->preco, 2, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('estoque.edit', $item->id) }}" class="btn btn-warning btn-sm">Editar</a>
                            <form action="{{ route('estoque.destroy', $item->id) }}" method="POST" style="display:inline;">
                                @csrf 
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tens a certeza que queres apagar?')">Deletar</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Nenhum produto registado no estoque.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection