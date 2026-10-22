@extends('main')
@section('titulo', 'Itens da Venda #' . $venda->id)

@section('conteudo')
<div class="container my-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Detalhes da Venda #{{ $venda->id }}</h4>
            <a href="{{ route('venda.index') }}" class="btn btn-light btn-sm">Voltar</a>
        </div>
        <div class="card-body">
            <p><strong>Cliente:</strong> {{ $venda->usuario->nome ?? '-' }}</p>
            <p><strong>Funcionário:</strong> {{ $venda->funcionario->nome ?? '-' }}</p>
            <p><strong>Data da Venda:</strong> {{ \Carbon\Carbon::parse($venda->data_venda)->format('d/m/Y') }}</p>
            
            <hr>

            <h5 class="mt-4 mb-3">Produtos nesta Venda:</h5>
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Preço Unitário</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalGeral = 0; @endphp
                    @forelse($venda->itens as $detalhe)
                        @php 
                            $subtotal = $detalhe->quantidade * $detalhe->valor_total;
                            $totalGeral += $subtotal;
                            $nomeProduto = $detalhe->bicicleta->modelo ?? $detalhe->acessorio->nome ?? 'Produto';
                        @endphp
                        <tr>
                            <td>{{ $nomeProduto }}</td>
                            <td>{{ $detalhe->quantidade }}</td>
                            <td>R$ {{ number_format($detalhe->valor_total, 2, ',', '.') }}</td>
                            <td>R$ {{ number_format($subtotal, 2, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Nenhum item registrado para esta venda.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="text-end mt-3">
                <h4>Valor Total: R$ {{ number_format($venda->valor_total, 2, ',', '.') }}</h4>
            </div>
        </div>
    </div>
</div>
@endsection