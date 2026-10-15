<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SDP - IFBA Seabra')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
</head>

<body class="flex flex-col min-h-screen bg-zinc-100">



    @mobile
        @include('layouts.partials.headerMobile')
    @else
        @include('layouts.partials.header')
    @endmobile

    <main class="main-content flex-1 container" style="padding-top: 32px;">
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="footer-inner">

            <div class="footer-brand">
                <img src="{{ asset('img/logoVertical.png') }}" alt="Logo IFBA" class="footer-logo">
                <div>
                    <div class="footer-brand-name">SDP</div>
                    <div class="footer-brand-sub">Sistema de Protocolos e Requerimentos</div>
                </div>
            </div>
            <div class="footer-links">
                <a href="{{ route('report') }}" class="footer-link ">Reportar</a>
            </div>

        </div>

        <div class="footer-bottom">
            SDP &mdash; IFBA Campus Seabra &copy; {{ date('Y') }}
        </div>
    </footer>
    <script>
    function toggleDropdownSetores(event) {
        event.stopPropagation();
        const menu = document.getElementById('menuSetores');
        const arrow = event.currentTarget.querySelector('.dropdown-arrow');

        menu.classList.toggle('show');
        if (arrow) {
            arrow.classList.toggle('rotated');
        }
    }

    // Fecha o dropdown ao clicar fora
    window.addEventListener('click', function() {
        const menu = document.getElementById('menuSetores');
        if (menu && menu.classList.contains('show')) {
            menu.classList.remove('show');
            const arrow = document.querySelector('.dropdown-toggle-btn .dropdown-arrow');

        }
    });
    </script>
</body>
</html>
