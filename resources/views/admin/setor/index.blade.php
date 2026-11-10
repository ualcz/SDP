@extends('layouts.app')

@section('title', 'Modelos de Requerimentos - SDP')
@section('tag', 'Administração')

@section('content')
<style>
    .admin-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        padding: 24px;
        margin-bottom: 24px;
    }
    .tabela-modelos {
        border-collapse: collapse;
        width: 100%;
        margin-top: 15px;
    }
    .tabela-modelos th, .tabela-modelos td {
        border: 1px solid #e5e7eb;
        padding: 10px 14px;
        text-align: left;
    }
    .tabela-modelos th {
        background: #f9fafb;
        font-weight: 600;
        color: #374151;
        font-size: 0.875rem;
    }
    .tabela-modelos tr:hover {
        background-color: #f9fafb;
    }
    .badge-ativo {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    .badge-inativo {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
    .btn-acao {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 0 12px;
        height: 32px;
        box-sizing: border-box;
        border-radius: 6px;
        font-size: 0.8125rem;
        font-weight: 600;
        text-decoration: none;
        background-color: #2563eb;
        color: #fff;
        transition: background-color 0.2s;
    }
    .btn-acao:hover {
        background-color: #1d4ed8;
    }
    .btn-icon-preview {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        background-color: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
        text-decoration: none;
        transition: all 0.15s;
        box-sizing: border-box;
    }
    .btn-icon-preview:hover {
        background-color: #dbeafe;
        color: #1e40af;
        border-color: #93c5fd;
    }
    .btn-voltar {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.875rem;
        border: 1px solid #d1d5db;
        color: #374151;
        text-decoration: none;
        background: #fff;
    }
    .btn-voltar:hover {
        background: #f3f4f6;
    }

    .remetentes-list {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        margin-top: 12px;
        margin-bottom: 12px;
    }

    .remetente-item {
        padding: 12px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        background: #f9fafb;
    }

    .remetentes-edit {
        max-height: 0;
        opacity: 0;
        overflow: hidden;
        transform: translateY(-10px);
        transition: max-height 0.3s ease-out, opacity 0.2s ease-out, transform 0.2s ease-out;
        visibility: hidden;
        margin-top: 12px;
    }

    .remetentes-edit.is-open {
        max-height: 500px;
        opacity: 1;
        transform: translateY(0);
        visibility: visible;
        transition: max-height 0.4s ease-in, opacity 0.3s ease-in, transform 0.3s ease-in;
    }

</style>

<div class="admin-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 700; margin: 0; color: #111827;">Setores</h2>
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.setores.create') }}" class="btn-acao">
                    ＋ Novo Setor
                </a>
            @endif
            <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('servidor.dashboard') }}" class="btn-voltar">
                &larr; Voltar ao Dashboard
            </a>
        </div>
    </div>

    @if(session('success'))
        <div style="background: #e6f4ea; color: #137333; padding: 12px 16px; margin: 16px 0; border: 1px solid #ceead6; border-radius: 6px;">
            {{ session('success') }}
        </div>
    @endif

    <table class="tabela-modelos">
        <thead>
            <tr>
                <th style="width: 100px;">Sigla</th>
                <th>Setor</th>
                <th>Título Formulário</th>
                <th>E-mail Setor</th>
                <th style="width: 170px; text-align: center;">Requerimentos</th>
                <th style="width: 100px; text-align: center;">Status</th>
                <th style="width: 180px; text-align: center;">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($modelos as $modelo)
                <tr>
                    <td><strong>{{ $modelo->setor_sigla }}</strong></td>
                    <td>{{ $modelo->setor_nome }}</td>
                    <td>{{ $modelo->titulo }}</td>
                    <td>{{ $modelo->email ?: '—' }}</td>
                    <td style="text-align: center;">
                        <span style="font-weight: 600; color: #2563eb;">{{ $modelo->assuntos_ativos_count }}</span>
                        <span style="color: #9ca3af;">/</span>
                        <span>{{ $modelo->assuntos_count }}</span>
                    </td>
                    <td style="text-align: center;">
                        @if($modelo->ativo)
                            <span class="badge-ativo">Ativo</span>
                        @else
                            <span class="badge-inativo">Inativo</span>
                        @endif
                    </td>
                    <td style="text-align: center; vertical-align: middle;">
                        <div style="display: flex; gap: 6px; justify-content: center; align-items: center;">
                            <a href="{{ route('admin.setores.preview-aluno', $modelo->id) }}"
                               target="_blank"
                               class="btn-icon-preview"
                               title="Visualizar como Aluno em nova aba">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </a>
                            <a href="{{ route('admin.setores.edit', $modelo->id) }}" class="btn-acao">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                                Editar
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 24px; color: #6b7280;">Nenhum setor cadastrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="admin-card">
                @php
                    $selecionados = old('responsaveis', $reportRecipientIds ?? []);
                    $usuariosOrdenados = $usuarios->sortByDesc(function ($usuario) use ($selecionados) {
                        return in_array($usuario->id, $selecionados);
                    });
                @endphp
    <h2 style="font-size: 1.25rem; font-weight: 500; margin:0; margin-bottom: 1rem; color: #111827; ">Gerenciamento de Notificações</h2>
    <p>Destinatários Atuais:</p>
    <div class="remetentes-list">
        @php
            $destinatariosAtuais = $destinatariosAtuais ?? collect();
            @endphp

        @if($destinatariosAtuais->count() > 0)
            @foreach ($destinatariosAtuais as $destinatario)
                <div class='remetente-item'>
                    <h3 style="margin-bottom: 10px; font-weight: 700; color: #111827; font-size: 1.25rem;">{{ $destinatario->nome }}</h3>
                    <p style="font-size: 1rem;">{{ $destinatario->email }}</p>
                    <p style="color:#717274; font-size: 0.875rem;">{{ $destinatario->matricula ?? '_' }}</p>
                </div>
            @endforeach
        @else
        <p style="color: #64748b; font-size: 0.875rem;">Nenhum responsável configurado no momento.</p>
        @endif
    </div>
    <div style="display: flex; justify-content: flex-end; margin-top: 12px;">
        <button class="btn-acao" onclick="editarDestinatarios()">Editar Destinatários</button>
    </div>
    <div class='remetentes-edit'>
        <p class="mb-4">Selecione os servidores ou administradores que receberão as notificações <br>por e-mail sempre que um aluno enviar uma sugestão ou reclamação pelo sistema.</p>
        <form action="{{ route('admin.notifications.update') }}" method="POST">
            @csrf
             @if(auth()->user()->role === 'admin')
            <div class="form-group col-span-12">

                <input type="text" id="buscar-usuario" placeholder="🔍 Digite para buscar um usuário..."
                    style="width: 100%; padding: 8px 12px; margin-bottom: 8px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem;">

                <div id="lista-responsaveis" style="max-height: 200px; overflow-y: auto; border: 1px solid #cbd5e1; border-radius: 6px; padding: 8px; background-color: #f8fafc;">
                    @foreach($usuariosOrdenados as $usuario)
                        @php $checked = in_array($usuario->id, $selecionados); @endphp
                        <label class="usuario-item" style="display: flex; align-items: center; gap: 8px; padding: 6px 8px; border-radius: 4px; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='transparent'">
                            <input type="checkbox" name="responsaveis[]" value="{{ $usuario->id }}" {{ $checked ? 'checked' : '' }} style="width: 16px; height: 16px; accent-color: #2563eb;">
                            <span style="font-size: 0.875rem; color: #1e293b;" class="usuario-texto">
                                <strong>{{ $usuario->nome }}</strong> <span style="color: #64748b;">({{ $usuario->email }})</span>
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>
            @endif
            <div style="display: flex; justify-content: flex-end; margin-top: 16px;">
                <button type="submit" class="btn-acao">Salvar</button>
            </div>
        </form>

    </div>
</div>
<script>
    document.getElementById('buscar-usuario').addEventListener('input', function() {
        const termo = this.value.toLowerCase();
        const itens = document.querySelectorAll('.usuario-item');

        itens.forEach(item => {
            const texto = item.querySelector('.usuario-texto').textContent.toLowerCase();
            if (texto.includes(termo)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    });

    function editarDestinatarios() {
        const editSection = document.querySelector('.remetentes-edit');
        editSection.classList.toggle('is-open');
    }
</script>
@endsection
