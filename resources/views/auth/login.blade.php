<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - IFBA</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>

    <div class="background-verde"></div>

    <div class="container">

        <!-- Logo -->
        <div class="logo-area">
        <img src="{{ asset('img/logo-ifba.png') }}" alt="Logo IFBA">
        </div>

        <!-- Card Login -->
        <div class="login-card">

            <h1>Login</h1>

            <p class="subtitulo">
                Informe sua matrícula (SUAP) ou e-mail (Administrador) e sua senha.
            </p>

            <!-- Exibição de erros Laravel -->
            @if($errors->any())
                <div class="erro-login">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Formulário Laravel -->
            <form method="POST" action="/login">

                @csrf

                <!-- Matrícula ou E-mail -->
                <div class="campo">
                    <label for="login">
                        Matrícula (SUAP) ou E-mail (Administrador)
                    </label>

                    <div class="input-icon">
                        
                        <i class="fa-solid fa-user"></i>
                              
                        <input
                            type="text"
                            id="login"
                            name="login"
                            placeholder="Digite sua matrícula ou e-mail"
                            required>
                        
                    </div>
                </div>

                <!-- Senha -->
                <div class="campo">
                    <label for="senha">Senha</label>

                    <div class="input-icon">

                        <i class="fa-solid fa-lock"></i>

                        <input
                            type="password"
                            id="senha"
                            name="password"
                            placeholder="Digite sua senha"
                            required>

                        <i
                            class="fa-solid fa-eye mostrar"
                            id="toggleSenha">
                        </i>

                    </div>
                </div>

                <!-- Botão -->
                <button type="submit">
                    Entrar
                    <i class="fa-solid fa-right-to-bracket"></i>
                </button>

            </form>

            <a href="#" class="esqueceu">
                Esqueceu sua senha?
            </a>

        </div>

    </div>

<div class="background-verde">
    <div class="creditos">
    </div>
</div>

</div>

    <script src="{{ asset('js/login.js') }}"></script>

</body>

</html>