@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/show-requerimento.css') }}">

@section('content')
<div class="detalhes-container">
    <x-btn-voltar style="grid-column: span 2;"/>
    <div class="historico">
        <h3>Histórico da Tramitação</h3>

        <ul class="timeline">
            @forelse($requerimento->historicos as $historico)
                <li class="timeline-item status-{{ Str::slug($historico->status) }}">
                    <div class="timeline-badge"></div>
                    <div class="timeline-panel">
                        <div class="timeline-heading">
                            <span class="badge-status">{{ $historico->status }}</span>
                            <span class="timeline-date">
                                {{ $historico->created_at->format('d/m/Y \à\s H:i') }}
                            </span>
                        </div>
                        <div class="timeline-body">
                            <p><strong>Responsável:</strong> {{ $historico->usuario->nome ?? 'Sistema' }}</p>
                            @if($historico->observacao)
                                <div class="timeline-observacao">
                                    <strong>Observação:</strong> {{ $historico->observacao }}
                                </div>
                            @endif
                            @if($historico->nome_documento_solicitado)
                                <div class="timeline-observacao">
                                    <strong>Documento Solicitado:</strong> {{ $historico->nome_documento_solicitado }}
                                </div>
                            @endif

                            {{-- DOCUMENTOS ANEXADOS NESTA TRAMITAÇÃO --}}
                            @include('requerimentos.partials.historico-documentos', ['documentos' => $historico->documentos])
                        </div>
                    </div>
                </li>
            @empty
                <p>Nenhum registro no histórico até o momento.</p>
            @endforelse
        </ul>
    </div>

    <div class="protocolo-info">

        <details class="card-painel info-aluno-accordion">
            <summary class="accordion-header">
                <div class="accordion-titulo-wrapper">
                    <h2 class="card-titulo">
                        <svg width="20" height="20" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Informações do Aluno
                    </h2>

                    <!-- Resumo visível mesmo quando colapsado -->
                    <div class="grid-3 preview-aluno">
                        <div class="info-grupo">
                            <span class="info-label">Nome Completo</span>
                            <span class="info-valor">{{ $requerimento->usuario->nome }}</span>
                        </div>
                        <div class="info-grupo">
                            <span class="info-label">Matrícula</span>
                            <span class="info-valor">{{ $requerimento->usuario->matricula }}</span>
                        </div>
                        <div class="info-grupo">
                            <span class="info-label">Telefone / WhatsApp</span>
                            <span class="info-valor">{{ $requerimento->usuario->telefone ?? 'Não informado' }}</span>
                        </div>
                    </div>
                </div>

                <svg class="icone-seta" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </summary>

            <!-- Conteúdo expandido (Oculto até o clique) -->
            <div class="accordion-body">
                <!-- Demais Informações do Aluno -->
                <div class="grid-3 grid-complementar">
                    <div class="info-grupo">
                        <span class="info-label">CPF</span>
                        <span class="info-valor">{{ $requerimento->usuario->cpf }}</span>
                    </div>
                    <div class="info-grupo">
                        <span class="info-label">Turma</span>
                        <span class="info-valor">{{ $requerimento->usuario->turma_codigo ?? 'Não informada' }}</span>
                    </div>
                    <div class="info-grupo">
                        <span class="info-label">E-mail Institucional</span>
                        <span class="info-valor">{{ $requerimento->usuario->email }}</span>
                    </div>
                    <div class="info-grupo">
                        <span class="info-label">E-mail Pessoal</span>
                        <span class="info-valor">{{ $requerimento->usuario->email_pessoal ?? 'Não informado' }}</span>
                    </div>
                </div>

                <hr class="divisor-secao">

                <!-- Seção de Endereço Residencial -->
                <h3 class="subtitulo-secao">Endereço Residencial</h3>
                @if($requerimento->usuario->endereco)
                    <div class="grid-3">
                        <div class="info-grupo">
                            <span class="info-label">Rua / Logradouro</span>
                            <span class="info-valor">{{ $requerimento->usuario->endereco->rua }}</span>
                        </div>
                        <div class="info-grupo">
                            <span class="info-label">Número</span>
                            <span class="info-valor">{{ $requerimento->usuario->endereco->numero }}</span>
                        </div>
                        <div class="info-grupo">
                            <span class="info-label">Bairro</span>
                            <span class="info-valor">{{ $requerimento->usuario->endereco->bairro }}</span>
                        </div>
                        <div class="info-grupo">
                            <span class="info-label">Cidade / UF</span>
                            <span class="info-valor">{{ $requerimento->usuario->endereco->cidade }} / {{ $requerimento->usuario->endereco->estado }}</span>
                        </div>
                        <div class="info-grupo">
                            <span class="info-label">CEP</span>
                            <span class="info-valor">{{ $requerimento->usuario->endereco->cep }}</span>
                        </div>
                    </div>
                @else
                    <p class="texto-vazio">Nenhum endereço cadastrado para este usuário.</p>
                @endif
            </div>
        </details>
        <div class="card-painel">
            <div class="card-header-flex">
                <div>
                    <span class="info-label">Nº Protocolo</span>
                    <h1 class="card-titulo" style="font-size: 1.5rem; color: #2563eb;">
                        {{ $requerimento->numero_protocolo }}
                    </h1>
                </div>
                <div>
                    @php
                        $statusClass = match($requerimento->status) {
                            'Aberto' => 'badge-Aberto',
                            'Em Análise' => 'badge-analise',
                            'Concluído' => 'badge-concluido',
                            'Encerrado' => 'badge-encerrado',
                            'Deferido' => 'badge-deferido',
                            'Indeferido' => 'badge-indeferido',
                            default => 'badge-analise'
                        };
                    @endphp
                    <span class="badge {{ $statusClass }}">
                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"/>
                        </svg>
                        {{ $requerimento->status }}
                    </span>
                </div>
            </div>

            <div class="grid-2">
                <div class="info-grupo">
                    <span class="info-label">Objeto do Requerimento</span>
                    <span class="info-valor">{{ $requerimento->objetoDoRequerimento }}</span>
                </div>
                <div class="info-grupo">
                    <span class="info-label">Data e Hora de Envio</span>
                    <span class="info-valor">{{ $requerimento->created_at->format('d/m/Y \à\s H:i') }}</span>
                </div>
            </div>

            <div class="info-grupo" style="margin-top: 0.75rem;">
                <span class="info-label">Motivo / Solicitação</span>
                <div class="box-motivo">
                    <span class="info-valor">{{ $requerimento->motivo }}</span>
                </div>
            </div>

            @if(session('success'))
                <div style="background-color: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 12px 16px; border-radius: 6px; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; font-size: 0.875rem;">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div style="background-color: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; padding: 12px 16px; border-radius: 6px; margin-bottom: 16px; font-size: 0.875rem;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif



        </div>

        <div class="card-painel">
            <h2 class="card-titulo">Ações</h2>
            <div>
                <nav class="acoes-nav">
                    <ul class="acoes-lista">
                        <li>
                            <button class='acoes-btn' type="button" data-modal-target="modal-deferir/indeferir" {{ $requerimento->status == "Em Análise" ? '' : 'disabled' }}>
                                    Deferir/Indeferir
                            </button>
                        </li>
                        <li>
                            <button class='acoes-btn' type="button" data-modal-target="modal-pendencia" {{ $requerimento->status == "Em Análise" ? '' : 'disabled' }}>
                                        Solicitar Alteração
                            </button>
                        </li>
                        <li>
                            <button class='acoes-btn' type="button" data-modal-target="modal-encaminhar" {{ $requerimento->status == "Em Análise" ? '' : 'disabled' }}>
                                        Encaminhar
                            </button>
                        </li>
                        <li>
                            <button class='acoes-btn' type="button" data-modal-target="modal-concluir" {{ ($requerimento->status == "Deferido" || $requerimento->status == "Indeferido") ? '' : 'disabled' }}>
                                        Concluir
                            </button>
                        </li>
                        <li>
                            <button class='acoes-btn' type="button" data-modal-target="modal-encaminhar" {{ $requerimento->status == "Aberto" ? '' : 'disabled' }}>
                                        Atender
                            </button>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

    </div>
</div>

{{-- Modal de Deferimento-conclusão --}}
@include('setor.requerimentos.partials.modal-deferido')

{{-- Modal de Pendencia --}}
@include('setor.requerimentos.partials.modal-pendencia')


{{-- Modal de encaminhamento --}}
@include('setor.requerimentos.partials.modal-encaminhar')

{{-- Modal de encaminhamento --}}
@include('setor.requerimentos.partials.modal-concluir')

<script src="{{ asset('js/show-requerimento.js') }}"></script>
@endsection
