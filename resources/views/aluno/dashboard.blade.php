<h1>Dashboard do aluno</h1>

<p>Bem-vindo, {{ auth()->user()->nome }}</p>

<a href="{{ route('suap.sync') }}">
    Sincronizar dados com o banco
</a>