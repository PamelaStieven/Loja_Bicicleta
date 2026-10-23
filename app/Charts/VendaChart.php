<?php

namespace App\Charts;

use App\Models\Venda;
use ArielMejiaDev\LarapexCharts\BarChart;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class VendaChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): BarChart
    {
        // Carrega as vendas com o usuário e os itens para calcular a quantidade total
        $vendas = Venda::with(['usuario', 'itens'])->orderBy('id', 'asc')->get();

        $labels = [];
        $valores = [];

        foreach ($vendas as $item) {
            // Calcula o total de itens da venda (soma as quantidades dos itens ou conta os registros)
            $totalQtd = $item->itens->sum('quantidade') > 0 
                ? $item->itens->sum('quantidade') 
                : $item->itens->count();

            // Pega o nome do cliente ou usa o ID da venda como fallback
            $nomeCliente = $item->usuario->nome ?? $item->usuario->name ?? "Venda #{$item->id}";

            // Formata a label: "Nome do Cliente (X itens)"
            $labels[] = "{$nomeCliente} ({$totalQtd} " . ($totalQtd == 1 ? 'item' : 'itens') . ")";

            $valores[] = (float) $item->valor_total;
        }

        if (empty($labels)) {
            $labels = ['Sem registros'];
            $valores = [0.0];
        }

        return $this->chart->barChart()
            ->setTitle('Relatório de Vendas')
            ->setSubtitle('Valor Total (R$) por Venda')
            ->addData( $valores, 'Valor Total (R$)')
            ->setColors(['#35ffee'])
            ->setXAxis($labels);
    }
}