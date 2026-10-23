@extends('main')
@section('titulo', 'Relatório de vendas')

@section('conteudo')
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Relatório e Gráfico de Vendas</h3>
        <div>
            <button onclick="window.print()" class="btn btn-secondary">Imprimir / PDF</button>
            <a href="{{ route('venda.index') }}" class="btn btn-primary">Voltar</a>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white">Visualização Gráfica</div>
        <div class="card-body text-center" style="min-height: 350px;">
            {!! $chart->container() !!}
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">Tabela de Dados</div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuário</th>
                        <th>Funcionário</th>
                        <th>Qtd de Itens</th>
                        <th>Valor Total</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vendas as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->usuario->nome ?? '-' }}</td>
                        <td>{{ $item->funcionario->nome ?? '-' }}</td>
                        <td>{{ $item->itens->sum('quantidade') }}</td>                        
                        <td>R$ {{ number_format($item->valor_total, 2, ',', '.') }}</td>
                        <td>{{ $item->data_venda ? \Carbon\Carbon::parse($item->data_venda)->format('d/m/Y') : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="{{ $chart->cdn() }}"></script>
{!! $chart->script() !!}
@endsection