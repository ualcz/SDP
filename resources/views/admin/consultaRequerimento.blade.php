@extends('layouts.app')

@section('title', 'Dashboard - SDP')
@section('tag', 'Administração')

@section('content')
<link rel="stylesheet" href="{{ asset('css/consultaRequerimento.css') }}">

{{-- Filtro de Pesquisa por Aluno ou Setor --}}
<div class="dash-filter-card">
    <form method="GET" action="" class="dash-filter-form">
        <div style="flex: 2; min-width: 220px;">
            <input type="text" name="aluno" value="{{ request('aluno') }}" placeholder="Buscar por aluno..." class="input-filtro">
        </div>
        <div style="flex: 1; min-width: 180px;">
            <select name="setor" class="input-filtro">
                <option value="">Todos os setores</option>
                @foreach($setores as $s)
                    <option value="{{ $s->id }}" {{ request('setor') == $s->id ? 'selected' : '' }}>
                        {{ $s->setor_sigla }} &mdash; {{ $s->setor_nome }}
                    </option>
                @endforeach
            </select>
        </div>
        <div style="display: flex; gap: 8px;">
            <button type="submit" class="btn-filtrar">
                Buscar
            </button>
            @if(request()->filled('aluno') || request()->filled('setor'))
                <a href="{{ route('admin.dashboard') }}" class="btn-limpar">
                    Limpar
                </a>
            @endif
        </div>
    </form>
</div>

<section class="dash-section">
    <h3 class="dash-section-title">Requerimentos recentes</h3>

    @if($requerimentos->isEmpty())
        <p style="color: #64748b; font-size: 0.875rem; margin: 0; padding: 12px 0;">
            @if(request()->filled('aluno') || request()->filled('setor'))
                Nenhum requerimento encontrado para esta busca.
            @else
                Nenhum requerimento cadastrado.
            @endif
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
                        <th style="width: 120px; text-align: center;">Setor</th>
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
                            <td style="text-align: center;">
                                <span class="badge badge-setor" title="{{ $requerimento->setor_nome }}">
                                    {{ $requerimento->setor?->setor_sigla ?? $requerimento->setor_sigla }}
                                </span>
                            </td>
                            <td class="req-col-objeto {{ $requerimento->status ?? "Aberto" }}" style="text-align: center;">
                                <span>
                                    {{ $requerimento->status ?? '-'}}
                                </span>
                            </td>
                            <td style="text-align: center; width: 120px;">
                                <span class="badge badge-setor border">
                                    <a href="{{ route('admin.historico', $requerimento->id) }}">Visualizar</a>
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</section>
@endsection
