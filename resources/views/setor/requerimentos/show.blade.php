@extends('layouts.app')

<style>
    .detalhes-container {
        max-width: 1200px;
        margin: 0 auto 2rem auto;
        padding: 0 1rem;
    }

    .card-painel {
        background-color: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .card-header-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 1rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .card-titulo {
        font-size: 1.125rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .grid-2 {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1rem;
    }

    .info-grupo {
        margin-bottom: 0.75rem;
    }

    .info-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #4b5563;
        display: block;
        margin-bottom: 0.25rem;
    }

    .info-valor {
        font-size: 0.95rem;
        color: #1f2937;
        font-weight: 500;
    }

    /* Badges de Status (Cores e Padrões da Dashboard) */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: 9999px;
    }
    .badge-aberto { background-color: #fef3c7; color: #d97706; }
    .badge-analise { background-color: #ede9fe; color: #7c3aed; }
    .badge-concluido { background-color: #d1fae5; color: #059669; }

    /* Formulário de Atualização */
    .form-status {
        display: flex;
        gap: 0.75rem;
        align-items: center;
        flex-wrap: wrap;
        margin-top: 0.75rem;
    }

    .select-status {
        padding: 0.5rem 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        background-color: #ffffff;
        color: #374151;
        min-width: 180px;
        outline: none;
    }

    .btn-atualizar {
        background-color: #2563eb;
        color: #ffffff;
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }

    .btn-atualizar:hover {
        background-color: #1d4ed8;
    }

    .alert-sucesso {
        background-color: #d1fae5;
        border-left: 4px solid #059669;
        color: #065f46;
        padding: 0.875rem 1rem;
        border-radius: 0.375rem;
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .box-motivo {
        background-color: #f9fafb;
        padding: 0.875rem;
        border-radius: 0.375rem;
        border: 1px solid #e5e7eb;
        margin-top: 0.25rem;
    }
</style>

@section('content')
<div class="detalhes-container">
    <x-btn-voltar />

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
                <select name="status" id="status" class="select-status" required>
                    <option value="Aberto" {{ $requerimento->status == 'Aberto' ? 'selected' : '' }}>Aberto</option>
                    <option value="Em Análise" {{ $requerimento->status == 'Em Análise' ? 'selected' : '' }}>Em Análise</option>
                    <option value="Concluído" {{ $requerimento->status == 'Concluído' ? 'selected' : '' }}>Concluído</option>
                </select>
                <button type="submit" class="btn-atualizar">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Atualizar Status
                </button>
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
@endsection
