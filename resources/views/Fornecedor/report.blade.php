<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>{{ $titulo ?? 'Relatório de Fornecedores' }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 0;
            padding: 10px;
        }
        h3 {
            text-align: center;
            margin-bottom: 5px;
            font-size: 16px;
            color: #111;
        }
        .date-info {
            text-align: right;
            font-size: 10px;
            margin-bottom: 15px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #bbb;
            padding: 6px 8px;
            text-align: left;
            vertical-align: middle;
        }
        th {
            background-color: #343a40;
            color: #fff;
            font-weight: bold;
            font-size: 11px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>

    <h3>{{ $titulo ?? 'Relatório de Fornecedores' }}</h3>
    <div class="date-info">Emitido em: {{ $date ?? date('d/m/Y') }}</div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 35%;">Fornecedor</th>
                <th style="width: 30%;">Produto</th>
                <th style="width: 30%;">Email</th>
                <th style="width: 30%;">Telefone</th>
                <th style="width: 30%;">Endereço</th>
                <th style="width: 15%;" class="text-center">Data do Fornecimento</th>
                <th style="width: 15%;" class="text-right">Valor Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($dados as $item)
               <tr>
                    <td><strong>#{{ $item->id }}</strong></td>
                    <td>{{ $item->nome ?? $item->razao_social ?? 'N/A' }}</td>
                    <td>{{ $item->produto ?? $item->produtos ?? 'N/A' }}</td>
                    <td>{{ $item->email ?? 'N/A' }}</td>
                    <td>{{ $item->telefone ?? $item->fone ?? 'N/A' }}</td>
                    <td>{{ $item->endereco ?? 'N/A' }}</td>
                    <td class="text-center">
                        @php
                            $data = $item->data_fornecimento ?? $item->created_at ?? $item->data ?? null;
                        @endphp
                        {{ $data ? \Carbon\Carbon::parse($data)->format('d/m/Y') : '-' }}
                    </td>  
                    <td class="text-end text-right fw-bold">
                        @php
                            $valor = $item->valor_total ?? $item->valor ?? $item->preco ?? 0;
                        @endphp
                        R$ {{ number_format($valor, 2, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Nenhum fornecimento encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>