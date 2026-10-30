@extends('main')

@section('titulo', 'Itens da Venda #' . $venda->id)

@section('conteudo')

<div class="container my-4">

    <div class="card shadow-sm">

        {{-- CABEÇALHO --}}
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h4 class="mb-0">
                Detalhes da Venda #{{ $venda->id }}
            </h4>

            <a
                href="{{ route('venda.index') }}"
                class="btn btn-light btn-sm"
            >
                Voltar
            </a>

        </div>

        <div class="card-body">

            {{-- DADOS DA VENDA --}}
            <p>
                <strong>Cliente:</strong>
                {{ $venda->usuario->nome ?? '-' }}
            </p>

            <p>
                <strong>Funcionário:</strong>
                {{ $venda->funcionario->nome ?? '-' }}
            </p>

            <p>
                <strong>Data da venda:</strong>

                {{ $venda->data_venda
                    ? \Carbon\Carbon::parse($venda->data_venda)->format('d/m/Y')
                    : '-'
                }}
            </p>

            <hr>

            <h5 class="mt-4 mb-3">
                Produtos desta venda
            </h5>

            {{-- TABELA DE ITENS --}}
            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead class="table-dark">

                        <tr>
                            <th>Produto</th>
                            <th>Tipo</th>
                            <th>Quantidade</th>
                            <th>Preço unitário</th>
                            <th>Subtotal</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse ($venda->itens as $detalhe)

                            @php

                                $precoUnitario = (float) ($detalhe->preco_unitario ?? 0);

                                $quantidade = (int) $detalhe->quantidade;

                                $subtotal = $quantidade * $precoUnitario;

                                if ($detalhe->bicicleta_id) {

                                    $nomeProduto = $detalhe->bicicleta->modelo
                                        ?? $detalhe->bicicleta->nome
                                        ?? 'Bicicleta não identificada';

                                    $tipoProduto = 'Bicicleta';

                                } else {

                                    $nomeProduto = $detalhe->acessorio->nome
                                        ?? 'Acessório não identificado';

                                    $tipoProduto = 'Acessório';
                                }

                            @endphp

                            <tr>

                                <td>
                                    {{ $nomeProduto }}
                                </td>

                                <td>
                                    {{ $tipoProduto }}
                                </td>

                                <td>
                                    {{ $quantidade }}
                                </td>

                                <td>
                                    R$ {{ number_format($precoUnitario, 2, ',', '.') }}
                                </td>

                                <td>
                                    R$ {{ number_format($subtotal, 2, ',', '.') }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5" class="text-center">
                                    Nenhum item registrado para esta venda.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- TOTAL --}}
            <div class="text-end mt-3">

                <h4>
                    Valor total:
                    R$ {{ number_format($venda->valor_total, 2, ',', '.') }}
                </h4>

            </div>

            {{-- AÇÕES --}}
            <div class="d-flex justify-content-end gap-2 mt-3">

                <a
                    href="{{ route('venda.edit', $venda->id) }}"
                    class="btn btn-warning"
                >
                    Editar venda
                </a>

                <a
                    href="{{ route('venda.index') }}"
                    class="btn btn-secondary"
                >
                    Voltar à listagem
                </a>

            </div>

        </div>

    </div>

</div>

@endsection