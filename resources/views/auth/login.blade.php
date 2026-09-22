<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SDP IFBA Seabra</title>
    @vite(['resources/css/login.css', 'resources/js/login.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>

    <div class="login-wrapper">

        <!-- Logo / Marca -->
        <a href="{{ route('home') }}" class="login-brand">
            <img src="{{ asset('img/logoVertical.png') }}" alt="Logo IFBA">
            <div class="login-brand-text">
                <span class="login-brand-title">SDP</span>
                <span class="login-brand-subtitle">Sistema de Protocolos</span>
            </div>
        </a>

        <!-- Card de Login -->
        <div class="login-card">

            <h1>Acesse sua conta</h1>
            <p class="login-subtitulo">
                Informe sua matrícula (SUAP) e senha.
            </p>

            <!-- Erros -->
            @if($errors->any())
                <div class="login-erro">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <!-- Formulário -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Matrícula -->
                <div class="campo">
                    <label for="login">Matrícula (SUAP)</label>
                    <div class="input-icon">
                        <i class="fa-solid fa-user icon-prefix"></i>
                        <input
                            type="text"
                            id="login"
                            name="login"
                            value="{{ old('login') }}"
                            placeholder="Digite sua matrícula"
                            required
                            autofocus
                        >
                    </div>
                </div>

                <!-- Senha -->
                <div class="campo">
                    <label for="senha">Senha</label>
                    <div class="input-icon">
                        <i class="fa-solid fa-lock icon-prefix"></i>
                        <input
                            type="password"
                            id="senha"
                            name="password"
                            placeholder="Digite sua senha"
                            required
                        >
                        <i class="fa-solid fa-eye mostrar-senha" id="toggleSenha" title="Mostrar/ocultar senha"></i>
                    </div>
                </div>

                <!-- Botão de Entrar -->
                <button type="submit" class="btn-entrar">
                    <span>Entrar</span>
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                </button>
            </form>
        </div>

    </div>


</body>

</html>