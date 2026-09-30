@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/show-requerimento.css') }}?v={{ filemtime(public_path('css/show-requerimento.css')) }}">

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
    @if(auth()->user()->isAdmin() || auth()->user()->isServidor())
        <div class="card-painel info-aluno">
            <h2 class="card-titulo" style="border-bottom: 1px solid #e5e7eb; padding-bottom: 0.5rem; margin-bottom: 1rem;">
                Informações do Aluno
            </h2>
            <div class="grid-2">
                <div class="info-grupo">
                    <span class="info-label">Nome Completo</span>
                    <span class="info-valor">{{ $requerimento->usuario?->nome ?? 'Não informado' }}</span>
                </div>
                <div class="info-grupo">
                    <span class="info-label">Matrícula</span>
                    <span class="info-valor">{{ $requerimento->usuario?->matricula ?? 'Não informada' }}</span>
                </div>
                <div class="info-grupo">
                    <span class="info-label">CPF</span>
                    <span class="info-valor">{{ $requerimento->usuario?->cpf ?? 'Não informado' }}</span>
                </div>
                <div class="info-grupo">
                    <span class="info-label">Turma</span>
                    <span class="info-valor">{{ $requerimento->usuario?->turma_codigo ?? 'Não informada' }}</span>
                </div>
                <div class="info-grupo">
                    <span class="info-label">E-mail Institucional</span>
                    <span class="info-valor">{{ $requerimento->usuario?->email ?? 'Não informado' }}</span>
                </div>
                <div class="info-grupo">
                    <span class="info-label">E-mail Pessoal</span>
                    <span class="info-valor">{{ $requerimento->usuario?->email_pessoal ?? 'Não informado' }}</span>
                </div>
                <div class="info-grupo">
                    <span class="info-label">Telefone / WhatsApp</span>
                    <span class="info-valor">{{ $requerimento->usuario?->telefone ?? 'Não informado' }}</span>
                </div>
            </div>

            <h3 style="margin-top: 1.25rem;">Endereço Residencial</h3>
            @if($requerimento->usuario?->endereco)
                <div class="grid-2">
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
                <p style="color: #6b7280; font-size: 0.875rem;">Nenhum endereço cadastrado.</p>
            @endif
        </div>
    @endif
    </div>
</div>

<script src="{{ asset('js/show-requerimento.js') }}?v={{ filemtime(public_path('js/show-requerimento.js')) }}"></script>
@endsection
