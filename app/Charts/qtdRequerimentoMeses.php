<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use App\Models\Requerimento;

class qtdRequerimentoMeses
{
   public function build(): \ArielMejiaDev\LarapexCharts\LineChart
    {
        $dados = Requerimento::selectRaw('objetoDoRequerimento, MONTH(created_at) as mes, COUNT(*) as total')
            ->groupBy('objetoDoRequerimento', 'mes')
            ->orderBy('mes')
            ->get();

        $objetos = $dados->groupBy('objetoDoRequerimento');

        $meses = [
        'Janeiro', 'Fevereiro', 'Março', 'Abril',
        'Maio', 'Junho', 'Julho', 'Agosto',
        'Setembro', 'Outubro', 'Novembro', 'Dezembro'
        ];

        $series = [];

        foreach ($objetos as $registros) {
            $primeiro = $registros->first();
            if (!$primeiro) continue;

            $nomeObjeto = (string) $primeiro->objetoDoRequerimento;

            $valores = [];
            foreach (range(1, 12) as $m) {
                $registroMes = $registros->firstWhere('mes', $m);
                $valores[] = $registroMes ? $registroMes->total : 0;
            }

            $series[] = [
                'name' => $nomeObjeto,
                'data' => $valores
            ];
        }

        $chart = (new LarapexChart)->lineChart()
        ->setTitle('Requerimentos por mês')
        ->setSubtitle('Cada linha representa um objeto')
        ->setDataset($series)
        ->setXAxis($meses);

        return $chart;
    }
}