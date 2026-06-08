//exemplo
<h2>Login Representante</h2>

<form method="POST" action="/login/representante">
    @csrf

    <input type="hidden" name="role" value="representante">

    <input type="email" name="email" placeholder="Email">

    <input type="password" name="password" placeholder="Senha">

    <button type="submit">Entrar</button>
</form>