<h1>Dashboard do admin</h1>

<p>Bem-vindo, {{ auth()->user()->nome }}</p>

<hr>

<h3>Turmas</h3>
<a href="/admin/turmas/create">Criar turma</a>

<h3>Professores</h3>
<a href="/admin/professores/create">Adicionar professor</a>

<h3>Representantes</h3>
<a href="/admin/alunos/promover">
    Promover aluno a representante
</a>