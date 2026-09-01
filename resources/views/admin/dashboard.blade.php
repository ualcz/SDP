<!DOCTYPE html>

<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

```
<title>Dashboard do Admin</title>

<link rel="stylesheet" href="{{ asset('css/dashboardadmin.css') }}">
```

</head>

<body>

```
<div class="dashboard">

    <h1>Dashboard do Admin</h1>

    <p>
        Bem-vindo, {{ auth()->user()->nome }}
    </p>

    <hr>

    <section class="dashboard-section">
        <h3>Turmas</h3>

        <a href="/admin/turmas/create">
            ➕ Criar Turma
        </a>

        <a href="/admin/turmas/listar">
            📋 Listar Turmas
        </a>
    </section>

    <hr>

    <section class="dashboard-section">
        <h3>Professores</h3>

        <a href="/admin/professores/create">
            ➕ Criar Professor
        </a>
    </section>

    <hr>

    <section class="dashboard-section">
        <h3>Alunos</h3>

        <a href="/admin/alunos/promover">
            ⭐ Promover Aluno
        </a>
    </section>

</div>
```

</body>

</html>
