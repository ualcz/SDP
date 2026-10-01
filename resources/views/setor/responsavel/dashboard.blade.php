@extends('layouts.app')

@section('title', $setor->setor_sigla.' - SDP')
@section('tag', 'Administração')

@section('content')
<link rel="stylesheet" href="{{ asset('css/consultaRequerimento.css') }}">

<x-btn-voltar />

<div style="display: flex; justify-content: flex-end; margin-bottom: 16px;">
    <a href="{{ route('admin.setores.edit', $setor->id) }}" class="btn-editar-setor" style="display:inline-flex; align-items:center; gap:8px; padding:10px 16px; border:1px solid #1d4ed8; border-radius:8px; background:linear-gradient(135deg, #2563eb, #1d4ed8); box-shadow:0 4px 10px rgba(37,99,235,0.22); color:#fff; font-size:0.875rem; font-weight:700; text-decoration:none;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path d="M12 20h9"></path>
            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
        </svg>
        Editar setor
    </a>
</div>

<div class="dash-filter-card">
    <form method="GET" action="{{ route('setor.responsavel.dashboard', $setor->id) }}" class="dash-filter-form">
        <div class="filter-grid" style="display:grid; grid-template-columns: 1.5fr 1.2fr 1.2fr 1.2fr 1fr 1fr auto; gap:1rem; align-items:end;">
            <div class="filter-group">
                <label class="filter-label">Aluno</label>
                <input type="text" name="aluno" value="{{ request('aluno') }}" placeholder="Nome do aluno..." class="input-filtro">
            </div>

            <div class="filter-group">
                <label class="filter-label">Turma</label>
                <input type="text" name="turma" value="{{ request('turma') }}" placeholder="Código da turma..." class="input-filtro">
            </div>

            <div class="filter-group">
                <label class="filter-label">Status</label>
                <select name="status" class="input-filtro">
                    <option value="">Todos</option>
                    <option value="Aberto" {{ request('status') == 'Aberto' ? 'selected' : '' }}>Aberto</option>
                    <option value="Em Análise" {{ request('status') == 'Em Análise' ? 'selected' : '' }}>Em Análise</option>
                    <option value="Indeferido" {{ request('status') == 'Indeferido' ? 'selected' : '' }}>Indeferido</option>
                    <option value="Concluído" {{ request('status') == 'Concluído' ? 'selected' : '' }}>Concluído</option>
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label">Data Inicial</label>
                <input type="date" name="data_inicio" value="{{ request('data_inicio') }}" class="input-filtro">
            </div>

            <div class="filter-group">
                <label class="filter-label">Data Final</label>
                <input type="date" name="data_fim" value="{{ request('data_fim') }}" class="input-filtro">
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn-filtrar">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>

                @if(request()->anyFilled(['aluno', 'turma', 'status', 'data_inicio', 'data_fim']))
                    <a href="{{ route('setor.responsavel.dashboard', $setor->id) }}" class="btn-limpar">Limpar</a>
                @endif
            </div>
        </div>
    </form>
</div>

<section class="dash-section">
    <h3 class="dash-section-title">Requerimentos do setor</h3>

    @if($requerimentos->isEmpty())
        <p style="color: #64748b; font-size: 0.875rem; margin: 0; padding: 12px 0;">
            Nenhum requerimento encontrado para este setor.
        </p>
    @else
        <div style="overflow-x: auto;">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th style="width: 140px;">Data e Hora</th>
                        <th>Solicitante</th>
                        <th>Turma</th>
                        <th>Requerimento</th>
                        <th style="text-align: center;">Status</th>
                        <th style="text-align: center;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requerimentos as $requerimento)
                        <tr>
                            <td>{{ $requerimento->id ?? 'Usuário removido' }}</td>
                            <td style="color: #64748b; font-size: 0.8125rem;">
                                {{ $requerimento->created_at?->format('d/m/Y H:i') }}
                            </td>
                            <td>{{ $requerimento->usuario?->nome ?? 'Usuário removido' }}</td>
                            <td>{{ $requerimento->usuario?->turma_codigo ?? 'Usuário removido' }}</td>
                            <td>{{ $requerimento->objetoDoRequerimento }}</td>
                            <td class="{{ $requerimento->status ?? 'Aberto' }}" style="text-align: center;">
                                <span>{{ $requerimento->status ?? 'Aberto' }}</span>
                            </td>
                            <td style="text-align: center; width: 120px;">
                                <span class="badge badge-setor border">
                                    <a href="{{ route('setor.requerimentos.show', ['setor' => $setor->id, 'requerimento' => $requerimento->id]) }}">Atender</a>
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1rem;">
            {{ $requerimentos->links() }}
        </div>
    @endif
</section>
@endsection
