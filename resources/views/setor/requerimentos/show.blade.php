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
                        </div>
                    </div>
                </li>
            @empty
                <p>Nenhum registro no histórico até o momento.</p>
            @endforelse
        </ul>
    </div>

    <div class="protocolo-info">
        @if(session('sucesso'))
            <div class="alert-sucesso">
                {{ session('sucesso') }}
            </div>
        @endif

        {{-- CABEÇALHO DO REQUERIMENTO --}}
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
                            'Aberto' => 'badge-aberto',
                            'Em Análise' => 'badge-analise',
                            'Concluído' => 'badge-concluido',
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

            {{-- FORMULÁRIO PARA ATUALIZAR STATUS --}}
            <hr style="border: 0; border-top: 1px solid #e5e7eb; margin: 1.25rem 0 1rem 0;">
            <form action="{{ route('setor.requerimentos.atualizarStatus', [$setor->id, $requerimento->id]) }}" method="POST">
                @csrf
                @method('PATCH')

                <label for="status" class="info-label">Atualizar Status do Requerimento</label>
                <div class="form-status">
                    <select name="status" id="status" class="select-status" required onchange="toggleMensagemIndeferido()">
                        <option value="Aberto" {{ $requerimento->status == 'Aberto' ? 'selected' : '' }}>Aberto</option>
                        <option value="Em Análise" {{ $requerimento->status == 'Em Análise' ? 'selected' : '' }}>Em Análise</option>
                        <option value="Indeferido" {{ $requerimento->status == 'Indeferido' ? 'selected' : '' }}>Indeferido (Devolver ao aluno)</option>
                        <option value="Concluído" {{ $requerimento->status == 'Concluído' ? 'selected' : '' }}>Concluído</option>
                    </select>

                    <button type="submit" class="btn-atualizar">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 14h-2v-2h2v2zm0-4h-2V7h2v5z"/>
                        </svg>
                        Atualizar Status
                    </button>
                </div>

                <div id="campo-mensagem" style="display: {{ $requerimento->status == 'Indeferido' ? 'block' : 'none' }}; margin-top: 15px;">
                    <label for="observacao" class="info-label">Instrução de correção / Motivo do Indeferimento:</label>
                    <textarea
                        name="observacao"
                        id="observacao"
                        class="form-control"
                        rows="3"
                        placeholder="Descreva o motivo ou o que o aluno precisa corrigir..."
                        {{ $requerimento->status == 'Indeferido' ? 'required' : '' }}
                    >{{ old('observacao') }}</textarea>
                </div>
            </form>
        </div>

        {{-- DADOS DO ALUNO --}}
        <div class="card-painel">
            <h2 class="card-titulo" style="border-bottom: 1px solid #e5e7eb; padding-bottom: 0.5rem; margin-bottom: 1rem;">
                <svg width="20" height="20" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Informações do Aluno
            </h2>
            <div class="grid-2">
                <div class="info-grupo">
                    <span class="info-label">Nome Completo</span>
                    <span class="info-valor">{{ $requerimento->usuario->nome }}</span>
                </div>
                <div class="info-grupo">
                    <span class="info-label">Matrícula</span>
                    <span class="info-valor">{{ $requerimento->usuario->matricula }}</span>
                </div>
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
                <div class="info-grupo">
                    <span class="info-label">Telefone / WhatsApp</span>
                    <span class="info-valor">{{ $requerimento->usuario->telefone ?? 'Não informado' }}</span>
                </div>
            </div>
        </div>

        {{-- ENDEREÇO DO ALUNO --}}
        <div class="card-painel">
            <h2 class="card-titulo" style="border-bottom: 1px solid #e5e7eb; padding-bottom: 0.5rem; margin-bottom: 1rem;">
                <svg width="20" height="20" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Endereço Residencial
            </h2>
            @if($requerimento->usuario->endereco)
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
                <p style="color: #6b7280; font-size: 0.875rem;">Nenhum endereço cadastrado para este usuário.</p>
            @endif
        </div>

    </div>
</div>
<script src="{{ asset('js/show-requerimento.js') }}">
</script>
@endsection
