@extends('layouts.app')

@section('title', 'Modelos de Requerimentos - SDP')
@section('tag', 'Administração')

@section('content')
<style>
    table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 15px;
    }
    th, td {
        border: 1px solid #ccc;
        padding: 8px;
        text-align: left;
    }
    th {
        background: #f5f5f5;
    }
    .status-ativo {
        color: green;
        font-weight: bold;
    }
    .status-inativo {
        color: red;
        font-weight: bold;
    }
</style>

<div>
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2>Modelos de Requerimentos</h2>
        <a href="{{ route('admin.dashboard') }}">← Voltar ao Painel</a>
    </div>

    @if(session('success'))
        <div style="background: #e6f4ea; color: #137333; padding: 10px; margin: 10px 0; border: 1px solid #ceead6;">
            {{ session('success') }}
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Sigla</th>
                <th>Setor</th>
                <th>Título</th>
                <th>E-mail</th>
                <th>Assuntos (Ativos/Total)</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($modelos as $modelo)
                <tr>
                    <td><strong>{{ $modelo->setor_sigla }}</strong></td>
                    <td>{{ $modelo->setor_nome }}</td>
                    <td>{{ $modelo->titulo }}</td>
                    <td>{{ $modelo->email ?: '—' }}</td>
                    <td>{{ $modelo->assuntos_ativos_count }} / {{ $modelo->assuntos_count }}</td>
                    <td>
                        @if($modelo->ativo)
                            <span class="status-ativo">Ativo</span>
                        @else
                            <span class="status-inativo">Inativo</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.modelos.edit', $modelo->id) }}">Editar / Gerenciar Assuntos</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Nenhum modelo cadastrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
