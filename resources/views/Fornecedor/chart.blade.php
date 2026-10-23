@extends('main')
@section('titulo', 'Relatório de Fornecedores')

@section('conteudo')
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Relatório e Gráfico de Fornecedores</h3>
        <div>
            <button onclick="window.print()" class="btn btn-secondary">Imprimir / PDF</button>
            <a href="{{ route('fornecedor.index') }}" class="btn btn-primary">Voltar</a>
        </div>
    </div>

    <!-- Bloco do Gráfico Larapex com altura mínima definida -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white">Visualização Gráfica</div>
        <div class="card-body text-center" style="min-height: 350px;">
            {!! $chart->container() !!}
        </div>
    </div>

    <!-- Tabela de Dados Detalhados -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">Tabela de Dados</div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome Fornecedor</th>
                        <th>Produto</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>Endereço</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($fornecedores as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->nome }}</td>
                        <td>{{ $item->produto }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->telefone }}</td>
                        <td>{{ $item->endereco }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Scripts obrigatórios do Larapex Charts carregados diretamente -->
<script src="{{ $chart->cdn() }}"></script>
{!! $chart->script() !!}
@endsection