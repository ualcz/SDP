//exemplo

<h2>Login Professor</h2>

<form method="POST" action="/login/professor">
    @csrf

    <input type="hidden" name="role" value="professor">

    <input type="email" name="email">

    <input type="password" name="password">

    <button>Entrar</button>
</form>