@extends('layouts.app')

@section('title', 'Painel Administrativo - SDP')
@section('tag', 'Administração')

@section('content')
<style>
    table {
        border-collapse: collapse;
    }

    th,
    td {
        border: 1px solid #000;
        padding: 6px;
        text-align: left;
    }
</style>

<div>
    @foreach([
        ['label' => 'Total', 'value' => $totalRequerimentos],
        ['label' => 'Em análise', 'value' => $emAnalise],
        ['label' => 'Deferidos', 'value' => $deferidos],
        ['label' => 'Indeferidos', 'value' => $indeferidos],
    ] as $metric)
        <div>
            <p>{{ $metric['label'] }}: {{ $metric['value'] }}</p>
        </div>
    @endforeach
</div>

<div style="margin: 15px 0;">
    <a href="{{ route('admin.modelos.index') }}" style="display: inline-block; padding: 6px 12px; border: 1px solid #000; text-decoration: none; color: #000;">
        ⚙️ Gerenciar Modelos e Assuntos de Requerimentos
    </a>
</div>

<section>
    <h2>Requerimentos recentes</h2>

    @if($requerimentos->isEmpty())
        <p>Nenhum requerimento cadastrado.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Solicitante</th>
                    <th>Requerimento</th>
                    <th>Situação</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requerimentos as $requerimento)
                    @php($situacao = $requerimento->situação ?: 'Em análise')
                    <tr>
                        <td>{{ $requerimento->usuario?->nome ?? 'Usuário removido' }}</td>
                        <td>{{ $requerimento->objetoDoRequerimento }}</td>
                        <td>{{ $situacao }}</td>
                        <td>{{ $requerimento->created_at?->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</section>
@endsection