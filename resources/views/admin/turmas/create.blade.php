<h1>Criar Turma</h1>

<p>Preencha os dados da nova turma:</p>

<form method="POST" action="/admin/turmas">
    @csrf

    <div>
        <label>Nome da turma</label>
        <input type="text" name="nome" placeholder="Ex: 3º Informática A">
    </div>

    <br>

    <button type="submit">Criar turma</button>
</form>

<hr>

@if (session('success'))
    <div style="color: green; margin-top: 15px;">
        {{ session('success') }}
    </div>
@endif