@extends('layouts.app')

@section('title', 'Dashboard - SDP')
@section('tag', 'Administração')

@section('content')
<link rel="stylesheet" href="https://cloudflare.com">
<link rel="stylesheet" href="{{ asset('css/consultaRequerimento.css') }}?v={{ filemtime(public_path('css/consultaRequerimento.css')) }}">
<!-- <div class="btn-requerimentoResponsavel">
    <a href="#"
    class="btn-requerimentoPais">
        <h3>+ Adicionar requerimento dos pais</h3>
    </a>
</div> -->
<button
    onclick="abrirModalResponsavel()"
   class="btn-adicionar-responsavel">
    <span>Adicionar requerimento dos pais</span>
</button>


{{-- Filtro Avançado de Pesquisa --}}
<div class="dash-filter-card">
    <form method="GET" action="" class="dash-filter-form">
        <div class="filter-grid">

            <div class="filter-group">
                <label class="filter-label">Aluno</label>
                <input type="text" name="aluno" value="{{ request('aluno') }}" placeholder="Nome do aluno..." class="input-filtro">
            </div>

            <div class="filter-group">
                <label class="filter-label">Turma</label>
                <input type="text" name="turma" value="{{ request('turma') }}" placeholder="Código da turma..." class="input-filtro">
            </div>

            <div class="filter-group">
                <label class="filter-label">Setor</label>
                <select name="setor" class="input-filtro">
                    <option value="">Todos</option>
                    @foreach($setores as $s)
                        <option value="{{ $s->id }}" {{ request('setor') == $s->id ? 'selected' : '' }}>
                            {{ $s->setor_sigla }}
                        </option>
                    @endforeach
                </select>
            </div>

            @php
                $statusSelecionado = request()->has('status') ? request('status') : 'Aberto';
            @endphp

            <div class="filter-group">
                <label class="filter-label">Status</label>
                <select name="status" class="input-filtro">
                    <option value="" {{ $statusSelecionado === '' || $statusSelecionado === null ? 'selected' : '' }}>Todos</option>
                    <option value="Aberto" {{ $statusSelecionado === 'Aberto' ? 'selected' : '' }}>Aberto</option>
                    <option value="Em Análise" {{ $statusSelecionado === 'Em Análise' ? 'selected' : '' }}>Em Análise</option>
                    <option value="Despacho" {{ $statusSelecionado === 'Despacho' ? 'selected' : '' }}>Despacho</option>
                    <option value="Indeferido" {{ $statusSelecionado === 'Indeferido' ? 'selected' : '' }}>Indeferido</option>
                    <option value="Concluído" {{ $statusSelecionado === 'Concluído' ? 'selected' : '' }}>Concluído</option>
                    <option value="Encerrado" {{ $statusSelecionado === 'Encerrado' ? 'selected' : '' }}>Encerrado</option>
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

                @if(request()->anyFilled(['aluno', 'turma', 'setor', 'status', 'data_inicio', 'data_fim']))
                    <a href="{{ route('admin.consultar-requerimentos') }}" class="btn-limpar">
                       <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12"/>
                        </svg>
                    </a>
                @endif
            </div>
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

        <div style="margin-top: 1rem;">
            {{ $requerimentos->links() }}
        </div>
    @endif
</section>
 <!-- Modal de requerimento dos pais -->
<div
    id="modalRequerimentoResponsavel"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4 backdrop-blur-sm"
>
    <div class="w-full max-w-2xl overflow-hidden rounded-xl bg-white shadow-2xl">

        <!-- Cabeçalho -->
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
            <h2 class="text-lg font-semibold text-gray-800">
                Adicionar requerimento dos pais
            </h2>

            <button
                type="button"
                onclick="fecharModalResponsavel()"
                class="rounded-md p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-700"
                aria-label="Fechar modal"
            >
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Corpo -->
        <div class="max-h-[75vh] overflow-y-auto p-6">

            <form action="#" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Dados do responsável -->
                <div class="mb-6">
                    <h3 class="mb-3 text-sm font-semibold text-green-700">
                        Dados do responsável
                    </h3>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                Nome completo
                            </label>
                            <input
                                type="text"
                                name="nome_responsavel"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm outline-none transition focus:border-green-600 focus:ring-2 focus:ring-green-100"
                                required
                            >
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                Vínculo com o estudante
                            </label>
                            <select
                                name="vinculo_responsavel"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100"
                                required
                            >
                                <option value="">Selecione</option>
                                <option value="Pai">Pai</option>
                                <option value="Mãe">Mãe</option>
                                <option value="Responsável legal">Responsável legal</option>
                                <option value="Outro">Outro</option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                E-mail
                            </label>
                            <input
                                type="email"
                                name="email_responsavel"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100"
                                required
                            >
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                Telefone
                            </label>
                            <input
                                type="text"
                                name="telefone_responsavel"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100"
                            >
                        </div>
                    </div>
                </div>

                <!-- Estudante -->
                <div class="mb-6">
                    <h3 class="mb-3 text-sm font-semibold text-green-700">
                        Dados do estudante
                    </h3>

                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Buscar estudante (SUAP)
                    </label>

                    <input
                        type="search"
                        id="buscaEstudante"
                        placeholder="Digite o nome ou matrícula..."
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100"
                    >

                    <div id="resultadoEstudantes" class="mt-2"></div>

                    <input type="hidden" name="matricula_estudante">
                    <input type="hidden" name="nome_estudante">
                </div>

                <!-- Requerimento -->
                <div class="mb-6">
                    <h3 class="mb-3 text-sm font-semibold text-green-700">
                        Dados do requerimento
                    </h3>

                    <div class="mb-4">
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Data de recebimento do e-mail
                        </label>
                        <input
                            type="date"
                            name="data_recebimento_email"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100"
                            required
                        >
                    </div>

                    <div class="mb-4">
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Observação
                        </label>
                        <textarea
                            name="observacao"
                            rows="3"
                            class="w-full resize-y rounded-lg border border-gray-300 px-3 py-2 text-sm outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100"
                            required
                        ></textarea>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            E-mail do requerimento enviado pelo responsável (em formato PDF)                      </label>
                        <input
                            type="file"
                            name="arquivo"
                            accept=".pdf,application/pdf"
                            class="block w-full rounded-lg border border-gray-300 p-2 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-green-50 file:px-3 file:py-2 file:font-medium file:text-green-700 hover:file:bg-green-100"
                            required
                        >
                        <p class="mt-1 text-xs text-gray-500">
                            Somente arquivos PDF.
                        </p>
                    </div>
                </div>

                <!-- Rodapé -->
                <div class="flex justify-end gap-3 border-t border-gray-200 pt-4">
                    <button
                        type="button"
                        onclick="fecharModalResponsavel()"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100"
                    >
                        Cancelar
                    </button>

                    <button
                        type="submit"
                        class="rounded-lg bg-green-700 px-4 py-2 text-sm font-medium text-white transition hover:bg-green-800"
                    >
                        Registrar requerimento
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    function abrirModalResponsavel() {
        const modal = document.getElementById('modalRequerimentoResponsavel');

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    function fecharModalResponsavel() {
        const modal = document.getElementById('modalRequerimentoResponsavel');

        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    // Fechar ao clicar fora da modal
    document.getElementById('modalRequerimentoResponsavel')
        .addEventListener('click', function (event) {
            if (event.target === this) {
                fecharModalResponsavel();
            }
        });

    // Fechar com ESC
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            fecharModalResponsavel();
        }
    });
</script>
@endsection
