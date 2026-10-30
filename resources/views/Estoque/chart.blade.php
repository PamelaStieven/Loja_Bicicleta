@extends('main')
@section('titulo', 'Relatório de Estoque')

@section('conteudo')
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Relatório e Gráfico de Estoque</h3>
        <div>
            <button onclick="window.print()" class="btn btn-secondary">Imprimir / PDF</button>
            <a href="{{ route('estoque.index') }}" class="btn btn-primary">Voltar</a>
        </div>
    </div>


    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white">Visualização Gráfica de Quantidade em Estoque</div>
        <div class="card-body text-center" style="min-height: 350px;"> {{--HTML--}}
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
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Pre Uçonitário</th>
                        <th>Valor Total em Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($estoques as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->produto }}</td>
                        <td>{{ $item->quantidade }}</td>
                        <td>R$ {{ number_format($item->preco, 2, ',', '.') }}</td>
                        <td>R$ {{ number_format($item->preco * $item->quantidade, 2, ',', '.') }}</td>
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