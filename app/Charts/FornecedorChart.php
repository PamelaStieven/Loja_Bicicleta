<?php

namespace App\Charts;

use App\Models\Fornecedor;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use ArielMejiaDev\LarapexCharts\LineChart;

class FornecedorChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): LineChart
    {
        $fornecedores = Fornecedor::all();

        $nomes = [];
        $valores = [];
        $prazo_entrega = [];

        foreach ($fornecedores as $item) {
            $nomes[] = $item->nome;
            $valores[] = (float) $item->valor;
            $prazo_entrega[] = (int) $item->prazo_entrega; 
        }

        if (empty($nomes)) {
            $nomes = ['Sem registros'];
            $valores = [0.0];
            $prazo_entrega = [0];
        }

        return $this->chart->lineChart()
            ->setTitle('Relatório de Fornecedores')
            ->setSubtitle('Valores e Prazos de Entrega por Fornecedor')
            ->addData($valores, 'Valor (R$)')              // Array primeiro, Nome depois
            ->addData($prazo_entrega, 'Prazo de Entrega') // Array primeiro, Nome depois
            ->setXAxis($nomes);
    }
}