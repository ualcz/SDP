@extends('layouts.app')

@section('title', 'Meus Requerimentos - SDP')
@section('tag', 'Aluno')

@section('content')
<link rel="stylesheet" href="{{ asset('css/meusRequerimentos.css') }}">

<div class="req-container">

    {{-- Filtro de Pesquisa com Campo Único --}}
    <div class="req-filter-card">
        <form method="GET" action="{{ route('requerimentos.aluno.meusRequerimentos') }}" class="req-filter-form">
            <input
                type="text"
                name="busca"
                value="{{ request('busca') }}"
                placeholder="Buscar por objeto do requerimento, protocolo ou status..."
                class="req-input"
            />

            <button type="submit" class="req-btn-filtrar">
                Buscar
            </button>

            @if(request()->filled('busca'))
                <a href="{{ route('requerimentos.aluno.meusRequerimentos') }}" class="req-btn-limpar">
                    Limpar
                </a>
            @endif
        </form>
    </div>

    {{-- Conteúdo / Tabela ou Cards --}}
    @if($requerimentos->isEmpty())
        <div class="req-empty-card">
            <p class="req-empty-title">Nenhum requerimento encontrado.</p>
            <p style="margin-top: 4px;">
                @if(request()->filled('busca'))
                    Tente buscar por outro termo.
                @else
                    Você ainda não realizou nenhum requerimento.
                @endif
            </p>
        </div>
    @else

        {{-- Visualização em Tabela (Desktop) --}}
        <div class="req-table-card">
            <div class="req-table-scroll">
                <table class="req-table">
                    <thead>
                        <tr>
                            <th class="req-col-data">Data</th>
                            <th class="req-col-protocolo">Protocolo</th>
                            <th class="req-col-setor">Setor</th>
                            <th class="req-col-objeto">Objeto do Requerimento</th>
                            <th class="req-col-acoes">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requerimentos as $requerimento)
                            <tr>
                                <td class="req-col-data">
                                    {{ isset($requerimento->created_at) && $requerimento->created_at ? $requerimento->created_at->format('d/m/Y H:i') : (isset($requerimento['created_at']) && $requerimento['created_at'] ? \Carbon\Carbon::parse($requerimento['created_at'])->format('d/m/Y H:i') : date('d/m/Y')) }}
                                </td>
                                <td class="req-col-protocolo">
                                    <span class="req-badge-protocolo">
                                        #{{ $requerimento['numero_protocolo'] ?? $requerimento->numero_protocolo ?? 'S/N' }}
                                    </span>
                                </td>
                                <td class="req-col-setor">
                                    <span class="req-text-setor">
                                        {{ $requerimento->setor?->setor_sigla ?? $requerimento->setor_sigla ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="req-col-objeto">
                                    {{ $requerimento['objetoDoRequerimento'] ?? $requerimento->objetoDoRequerimento }}
                                </td>
                                <td class="req-col-acoes">
                                    <a
                                        href="{{ route('requerimentos.gerar-comprovante', ['numero_protocolo' => $requerimento->numero_protocolo]) }}"
                                        target="_blank"
                                        class="req-btn-imprimir"
                                    >
                                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                        </svg>
                                        Imprimir comprovante
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Visualização em Cards (Celular) --}}
        <div class="req-mobile-cards">
            @foreach($requerimentos as $requerimento)
                <div class="req-card">
                    <div class="req-card-top">
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <span class="req-badge-protocolo">
                                #{{ $requerimento['numero_protocolo'] ?? $requerimento->numero_protocolo ?? 'S/N' }}
                            </span>
                            <span class="req-text-setor">
                                {{ $requerimento->setor?->setor_sigla ?? $requerimento->setor_sigla ?? 'N/A' }}
                            </span>
                        </div>
                        <span class="req-card-date">
                            {{ isset($requerimento->created_at) && $requerimento->created_at ? $requerimento->created_at->format('d/m/Y H:i') : (isset($requerimento['created_at']) && $requerimento['created_at'] ? \Carbon\Carbon::parse($requerimento['created_at'])->format('d/m/Y H:i') : date('d/m/Y')) }}
                        </span>
                    </div>

                    <div class="req-card-objeto">
                        {{ $requerimento['objetoDoRequerimento'] ?? $requerimento->objetoDoRequerimento }}
                    </div>

                    <div class="req-card-actions">
                        <a
                            href="{{ route('requerimentos.gerar-comprovante', ['numero_protocolo' => $requerimento->numero_protocolo]) }}"
                            target="_blank"
                            class="req-card-btn-imprimir"
                        >
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            Imprimir comprovante
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

    @endif

</div>
@endsection
