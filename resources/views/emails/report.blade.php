<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mensagem </title>
</head>
<body>
    <h1>Mensagem de Contato</h1>
    <form action="
    {{-- {{ route('send.report') }} --}}
     " method="POST">
        @csrf
        <h2>calma ae</h2>

        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required value="{{ $aluno->nome ?? '' }}"><br><br>

        <label for='img'>Imagem:</label>
        <input type="file" id="img" name="img" accept="image/*"><br><br>

        <label for="mensagem">Mensagem:</label><br>
        <textarea id="mensagem" name="mensagem" rows="4" cols="50" required></textarea><br><br>

        <button type="submit">Enviar</button>

</body>
</html>
