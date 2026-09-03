<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SDP - IFBA Seabra')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href=".\resources\css\header.css">



</head>

<body class='flex flex-col min-h-screen'>

    <header class="p-4 border-b border-gray-300 mb-10">
        <div class="container mx-auto px-4 p-4 grid grid-cols-2 gap-4 items-center">

            <div class="align-items-center">
                <img src="{{ asset('img/logoVertical.png') }}" alt="Logo IFBA" class="w-12 h-auto inline">
                <div class="logo inline">
                    Sistema de Protocolos
                </div>
            </div>

            <div class="flex gap-10 items-end justify-end font-semibold text-gray-700">

                <a href="/">Novo Protocolo</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="fa-solid fa-right-from-bracket"></i> Sair
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="flex-1 container pt-10">
        @yield('content')
    </main>

    <footer class="bg-zinc-700 text-white font-bold p-4 text-center align-self-end">
        SDP - Sistema de Protocolos · IFBA Campus Seabra &copy; {{ date('Y') }}
    </footer>

</body>

</html>
