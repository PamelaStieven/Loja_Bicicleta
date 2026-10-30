<?php

namespace App\Charts;

use App\Models\Estoque;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use ArielMejiaDev\LarapexCharts\LineChart;

class EstoqueChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): LineChart
    {
        $estoque = Estoque::all();

        $labels = [];
        $quantidade = [];

        foreach ($estoque as $item) {
            $labels[] = $item->produto;
            $quantidade[] = (int) $item->quantidade;
        }

        if (empty($labels)) {
            $labels = ['Sem registros'];
            $quantidade = [0];
        }

        return $this->chart->lineChart()
            ->setTitle('Relatório de Estoque')
            ->setSubtitle('Quantidade de Produtos em Estoque')
            ->addData($quantidade, 'Quantidade')
            ->setXAxis($labels);
    }
}