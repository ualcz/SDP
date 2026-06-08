<h1>Dashboard do Admin</h1>

<p>Bem-vindo, {{ auth()->user()->nome }}</p>

<hr>

<h3>Turmas</h3>

<a href="/admin/turmas/create">
    ➕ Criar Turma
</a>

<br><br>

<a href="/admin/turmas/listar">
    📋 Listar Turmas
</a>

<hr>

<h3>Professores</h3>

<a href="/admin/professores/create">
    ➕ Criar Professor
</a>

<hr>

<h3>Alunos</h3>

<a href="/admin/alunos/promover">
    ⭐ Promover Aluno
</a>