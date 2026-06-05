//exemplo
<h2>Login Aluno</h2>

<form method="POST" action="/login">
    @csrf

    <input type="hidden" name="role" value="aluno">

    <input type="email" name="email" placeholder="Email">

    <input type="password" name="password" placeholder="Senha">

    <button type="submit">Entrar</button>
</form>