<h2>Cadastro de Aluno</h2>

<form method="POST" action="/register/aluno">
    @csrf

    <input name="nome" placeholder="Nome">

    <input name="email" type="email" placeholder="Email">

    <input name="password" type="password" placeholder="Senha">

    <input name="password_confirmation" type="password" placeholder="Confirmar senha">

    <input name="matricula" placeholder="Matrícula">

    <input name="codigo_turma" placeholder="Código da turma">

    <button>Cadastrar</button>
</form>