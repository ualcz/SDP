<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use App\Models\Requerimento;

class RequerimentoPorSetor
{
    public function build(): \ArielMejiaDev\LarapexCharts\PieChart
    {
        $dados = Requerimento::with('setor')
            ->selectRaw('setor_id, COUNT(*) as total')
            ->groupBy('setor_id')
            ->get();

        $valores = $dados->pluck('total')->toArray();
        $labels  = $dados->map(function ($item) {
            return $item->setor->setor_sigla ?? 'Setor ' . $item->setor_id;
        })->toArray();

        return (new LarapexChart)
            ->pieChart()
            ->setTitle('Requerimentos por setor')
            ->addData($valores)
            ->setLabels($labels);
    }
}
