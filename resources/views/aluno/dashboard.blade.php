<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard</title>

    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>

<div class="background-verde"></div>

<div class="container">

    <div class="logo-area">
        <img src="{{ asset('img/logo-ifba.png') }}" alt="Logo IFBA">
    </div>

    <div class="dashboard-card">

        <h1>Dashboard</h1>

        <p class="bem-vindo">
            Bem-vindo,
            <strong>{{ auth()->user()->nome }}</strong>
        </p>

        <div class="acoes">

            <a href="{{ route('suap.sync') }}" class="botao">
                <i class="fa-solid fa-arrows-rotate"></i>
                Sincronizar dados
            </a>

        </div>

    </div>

</div>

<script src="{{ asset('js/dashboard.js') }}"></script>

</body>
</html>