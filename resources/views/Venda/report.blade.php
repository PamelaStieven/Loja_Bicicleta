<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>{{ $titulo ?? 'Relatório de Vendas' }}</title>
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

    <h3>{{ $titulo ?? 'Relatório de Vendas' }}</h3>
    <div class="date-info">Emitido em: {{ $date ?? date('d/m/Y') }}</div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 35%;">Cliente</th>
                <th style="width: 30%;">Funcionário</th>
                <th style="width: 15%;" class="text-center">Data da Venda</th>
                <th style="width: 15%;" class="text-right">Valor Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($dados as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->usuario->nome ?? 'N/A' }}</td>
                    <td>{{ $item->funcionario->nome ?? 'N/A' }}</td>
                    <td class="text-center">{{ $item->data_venda ? \Carbon\Carbon::parse($item->data_venda)->format('d/m/Y') : '-' }}</td>
                    <td class="text-right">R$ {{ number_format($item->valor_total ?? 0, 2, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Nenhuma venda encontrada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>