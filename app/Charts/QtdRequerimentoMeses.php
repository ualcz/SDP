<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use App\Models\Requerimento;

class QtdRequerimentoMeses
{
   public function build(): \ArielMejiaDev\LarapexCharts\LineChart
{
    $dados = Requerimento::selectRaw("
            CASE
                WHEN objetoDoRequerimento LIKE 'Outros:%'
                THEN 'Outros'
                ELSE objetoDoRequerimento
            END as objeto,
            MONTH(created_at) as mes,
            COUNT(*) as total
        ")
        ->groupBy('objeto', 'mes')
        ->orderBy('mes')
        ->get();

    $objetos = $dados->groupBy('objeto');

    $meses = [
        'Jan', 'Fev', 'Mar', 'Abr',
        'Mai', 'Jun', 'Jul', 'Ago',
        'Set', 'Out', 'Nov', 'Dez'
    ];

    $series = [];

    foreach ($objetos as $registros) {
        $primeiro = $registros->first();

        if (!$primeiro) {
            continue;
        }

        $nomeObjeto = $primeiro->objeto;

        $valores = [];

        foreach (range(1, 12) as $m) {
            $registroMes = $registros->firstWhere('mes', $m);
            $valores[] = $registroMes ? $registroMes->total : 0;
        }

        $series[] = [
            'name' => $nomeObjeto,
            'data' => $valores,
        ];
    }

    return (new LarapexChart)->lineChart()
        ->setTitle('Requerimentos por mês')
        ->setSubtitle('Cada linha representa um objeto')
        ->setDataset($series)
        ->setXAxis($meses)
        ->setHeight(350);
}
}
