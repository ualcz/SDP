<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SDP - IFBA Seabra')</title>

</head>
<body>

    <header>
        <div class="logo">
            SDP IFBA
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="fa-solid fa-right-from-bracket"></i> Sair
            </button>
        </form>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        SDP - Sistema de Protocolos · IFBA Campus Seabra &copy; {{ date('Y') }}
    </footer>

</body>
</html>
