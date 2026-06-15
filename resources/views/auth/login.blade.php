<!DOCTYPE html>
<html lang="pt-BR">
<head>

    <meta charset="UTF-8">

    <title>Login SCAAE</title>

</head>

<body>

<h1>SCAAE</h1>

<h3>Entrar</h3>

@if($errors->any())

    <div style="color:red;">

        {{ $errors->first() }}

    </div>

@endif

<form method="POST" action="/login">

    @csrf

    <label>

        Matrícula (SUAP) ou Email (Admin)

    </label>

    <br>

    <input
        type="text"
        name="login"
        required
    >

    <br><br>

    <label>

        Senha

    </label>

    <br>

    <input
        type="password"
        name="password"
        required
    >

    <br><br>

    <button type="submit">

        Entrar

    </button>

</form>

</body>
</html>