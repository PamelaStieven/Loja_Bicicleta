<?php

namespace App\Charts;

use App\Models\Venda;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class VendaChart
{
    public function build(): \ArielMejiaDev\LarapexCharts\PieChart
    {
        $vendas = Venda::all();

        $Produtos = [];
        $Fornecedores = [];

        foreach ($vendas as $item) {
            $Fornecedores[] = $item->nome_fantasia;
            $Produtos[] = $item->produto;
        }

        return (new LarapexChart)->pieChart()
            ->setTitle('Acessórios Disponíveis')
            ->setSubtitle('Semestre 2026.2')
            ->addData($Produtos)
            ->setLabels($Fornecedores);
    }
}
