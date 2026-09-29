@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/show-requerimento.css') }}">

@section('content')
<div class="detalhes-container">
    <x-btn-voltar style="grid-column: span 2;" />

    <div class="historico">
        <h3>Histórico da Tramitação</h3>

        <ul class="timeline">
            @forelse($historicos as $historico)
                <li class="timeline-item status-{{ Str::slug($historico->status) }}">
                    <div class="timeline-badge"></div>
                    <div class="timeline-panel">
                        <div class="timeline-heading">
                            <span class="badge-status">{{ $historico->status }}</span>
                            <span class="timeline-date">
                                {{ $historico->created_at->format('d/m/Y \\à\\s H:i') }}
                            </span>
                        </div>
                        <div class="timeline-body">
                            <p><strong>Responsável:</strong> {{ $historico->usuario?->nome ?? 'Sistema' }}</p>
                            @if($historico->observacao)
                                <div class="timeline-observacao">
                                    <strong>Observação:</strong> {{ $historico->observacao }}
                                </div>
                            @endif
                            @if($historico->solicita_novo_documento)
                                <div class="timeline-observacao">
                                    <strong>Documento solicitado:</strong> {{ $historico->nome_documento_solicitado ?? 'Documento corrigido' }}
                                </div>
                            @endif
                        </div>
                    </div>
                </li>
            @empty
                <p>Nenhum registro no histórico até o momento.</p>
            @endforelse
        </ul>
    </div>

    <div class="protocolo-info">
        <div class="card-painel">
            <div class="card-header-flex">
                <div>
                    <span class="info-label">Nº Protocolo</span>
                    <h1 class="card-titulo" style="font-size: 1.5rem; color: #2563eb;">
                        {{ $requerimento->numero_protocolo }}
                    </h1>
                </div>
                @php
                    $statusClass = match($requerimento->status) {
                        'Aberto' => 'badge-Aberto',
                        'Em Análise' => 'badge-analise',
                        'Concluído' => 'badge-concluido',
                        'Indeferido' => 'badge-indeferido',
                        default => 'badge-analise'
                    };
                @endphp
                <span class="badge {{ $statusClass }}">
                    {{ $requerimento->status }}
                </span>
            </div>

            <div class="grid-2">
                <div class="info-grupo">
                    <span class="info-label">Objeto do Requerimento</span>
                    <span class="info-valor">{{ $requerimento->objetoDoRequerimento }}</span>
                </div>
                <div class="info-grupo">
                    <span class="info-label">Data e Hora de Envio</span>
                    <span class="info-valor">{{ $requerimento->created_at->format('d/m/Y \\à\\s H:i') }}</span>
                </div>
                <div class="info-grupo">
                    <span class="info-label">Aluno</span>
                    <span class="info-valor">{{ $requerimento->usuario?->nome ?? 'Não informado' }}</span>
                </div>
                <div class="info-grupo">
                    <span class="info-label">Setor</span>
                    <span class="info-valor">{{ $requerimento->setor?->setor_sigla ?? 'Não informado' }}</span>
                </div>
            </div>

            <div class="info-grupo" style="margin-top: 0.75rem;">
                <span class="info-label">Motivo / Solicitação</span>
                <div class="box-motivo">
                    <span class="info-valor">{{ $requerimento->motivo }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
