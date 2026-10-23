<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class MonthlyUsersChart
{
    public function build(): \ArielMejiaDev\LarapexCharts\LineChart
    {
        return (new LarapexChart)->lineChart()
            ->setTitle('Sales during 2021.')
            ->setSubtitle('Physical sales vs Digital sales.')
            ->addData([40, 93, 35, 42, 18, 82], 'Physical sales')
            ->addData([70, 29, 77, 28, 55, 45], 'Digital sales')
            ->setXAxis(['January', 'February', 'March', 'April', 'May', 'June']);
    }
}
