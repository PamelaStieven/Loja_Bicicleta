<?php

namespace App\Charts;

use App\Models\Estoque;
use ArielMejiaDev\LarapexCharts\LineChart;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class EstoqueChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): LineChart
    {
        $estoques = Estoque::orderBy('produto', 'asc')->get();

        $labels = [];
        $quantidades = [];

        foreach ($estoques as $item) {
            $labels[] = $item->produto;
            $quantidades[] = (int) $item->quantidade;
        }

        if (empty($labels)) {
            $labels = ['Sem registros'];
            $quantidades = [0];
        }

        return $this->chart->lineChart()
            ->setTitle('Relatório de Estoque')
            ->setSubtitle('Quantidade de Produtos em Estoque')
            ->addData('Quantidade', $quantidades)
            ->setXAxis($labels);
    }
}