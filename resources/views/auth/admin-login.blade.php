//exemplo

<h2>Login ADM</h2>

<form method="POST" action="/login/admin">
    @csrf

    <input type="hidden" name="role" value="admin">

    <input type="email" name="email">

    <input type="password" name="password">

    <button>Entrar</button>
</form>