<h1>Lista de Turmas</h1>

<a href="/admin/turmas/create">← Voltar</a>

<hr>

@foreach ($turmas as $turma)
    <div style="margin-bottom:10px; padding:10px; border:1px solid #ccc;">
        <strong>Nome:</strong> {{ $turma->nome }} <br>
        <strong>Código:</strong> {{ $turma->codigo_acesso }}
    </div>
@endforeach

@if ($turmas->isEmpty())
    <p>Nenhuma turma cadastrada.</p>
@endif