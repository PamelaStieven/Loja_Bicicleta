@extends('main')

@section('titulo', 'Listagem de Vendas')

@section('conteudo')

<div class="container my-4">

    <div class="d-flex justify-content-between align-items-center mb-2">

        <h3>Vendas Registradas</h3>

    </div>

    {{-- PESQUISA --}}
    <form
        action="{{ route('venda.search') }}"
        method="POST"
        class="d-flex flex-wrap gap-2 my-3"
    >

        @csrf

        <select name="tipo" class="form-select w-auto">

            <option
                value="usuario"
                {{ request('tipo') === 'usuario' ? 'selected' : '' }}
            >
                Cliente
            </option>

            <option
                value="funcionario"
                {{ request('tipo') === 'funcionario' ? 'selected' : '' }}
            >
                Funcionário
            </option>

            <option
                value="quantidade"
                {{ request('tipo') === 'quantidade' ? 'selected' : '' }}
            >
                Quantidade
            </option>

            <option
                value="data"
                {{ request('tipo') === 'data' ? 'selected' : '' }}
            >
                Data (AAAA-MM-DD)
            </option>

        </select>

        <input
            type="text"
            name="valor"
            class="form-control"
            placeholder="Digite para pesquisar..."
            value="{{ request('valor') }}"
        >

        <button type="submit" class="btn btn-primary">
            Buscar
        </button>

        <a
            href="{{ route('venda.index') }}"
            class="btn btn-secondary"
        >
            Limpar
        </a>

        <a
            href="{{ route('venda.create') }}"
            class="btn btn-success"
        >
            Nova venda
        </a>

        <a
            href="{{ route('venda.chart') }}"
            class="btn btn-info"
        >
            Gráfico
        </a>

        <a
            href="{{ route('venda.report') }}"
            class="btn btn-warning"
        >
            Relatórios
        </a>

    </form>

    {{-- MENSAGEM DE SUCESSO --}}
    @if (session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif

    {{-- MENSAGENS DE ERRO --}}
    @if ($errors->any())

        <div class="alert alert-danger">

            <strong>Não foi possível concluir a operação:</strong>

            <ul class="mb-0 mt-2">

                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach

            </ul>

        </div>

    @endif

    {{-- TABELA DE VENDAS --}}
    <div class="table-responsive">

        <table class="table table-striped table-hover shadow-sm">

            <thead class="table-dark">

                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Funcionário</th>
                    <th>Qtd. de itens</th>
                    <th>Valor total</th>
                    <th>Data</th>
                    <th class="text-center">Editar</th>
                    <th class="text-center">Itens</th>
                    <th class="text-center">Excluir</th>
                </tr>

            </thead>

            <tbody>

                @forelse ($dados as $item)

                    <tr>

                        <td>
                            {{ $item->id }}
                        </td>

                        <td>
                            {{ $item->usuario->nome ?? '-' }}
                        </td>

                        <td>
                            {{ $item->funcionario->nome ?? '-' }}
                        </td>

                        <td>
                            {{ $item->itens->sum('quantidade') }}
                        </td>

                        <td>
                            R$ {{ number_format($item->valor_total, 2, ',', '.') }}
                        </td>

                        <td>
                            {{ $item->data_venda
                                ? \Carbon\Carbon::parse($item->data_venda)->format('d/m/Y')
                                : '-'
                            }}
                        </td>

                        {{-- EDITAR --}}
                        <td class="text-center">

                            <a
                                href="{{ route('venda.edit', $item->id) }}"
                                class="btn btn-warning btn-sm"
                            >
                                Editar
                            </a>

                        </td>

                        {{-- VISUALIZAR ITENS --}}
                        <td class="text-center">

                            <a
                                href="{{ route('venda.itens', $item->id) }}"
                                class="btn btn-info btn-sm text-white"
                            >
                                Itens
                            </a>

                        </td>

                        {{-- EXCLUIR --}}
                        <td class="text-center">

                            <form
                                action="{{ route('venda.destroy', $item->id) }}"
                                method="POST"
                                class="d-inline"
                                onsubmit="return confirm('Tem certeza que deseja excluir esta venda?');"
                            >

                                @csrf

                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-danger btn-sm"
                                >
                                    Excluir
                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="9" class="text-center py-4">
                            Nenhuma venda registrada.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection