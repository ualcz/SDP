<nav class="navbar">

    <div class="navbar-esquerda">

        <a href="#" class="nav-botao ativo">
            Início
        </a>

        <a href="#" class="nav-botao">
            Calendário
        </a>

        <a href="#" class="nav-botao">
            Disciplinas
        </a>

        <a href="#" class="nav-botao">
            Turmas
        </a>

        <a href="#" class="nav-botao">
            Configurações
        </a>

    </div>


    <div class="navbar-direita">

        <div class="usuario-logado">

            <strong>
                {{ auth()->user()->nome }}
            </strong>

            <span>
                {{ ucfirst(auth()->user()->role) }}
            </span>

        </div>

    </div>

</nav>