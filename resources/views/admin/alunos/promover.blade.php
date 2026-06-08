<h1>Promover aluno a representante</h1>

<p>Digite a matrícula do aluno:</p>

<form method="POST" action="/admin/alunos/promover">
    @csrf

    <input type="text" name="matricula" placeholder="Matrícula">

    <button type="submit">Promover</button>
</form>

@if ($errors->any())
    <p style="color:red">{{ $errors->first() }}</p>
@endif

@if (session('success'))
    <p style="color:green">{{ session('success') }}</p>
@endif