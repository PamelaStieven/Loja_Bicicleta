<?php

namespace App\Charts;

use App\Models\Fornecedor;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class FornecedorChart
{
    public function build(): \ArielMejiaDev\LarapexCharts\PieChart
    {
        $fornecedores = Fornecedor::all();

        $Produtos = [];
        $Fornecedores = [];

        foreach ($fornecedores as $item) {
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
