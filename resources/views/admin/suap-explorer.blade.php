<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Explorador SUAP</title>

    <style>
        body{
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 40px;
        }

        .container{
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,.08);
        }

        input{
            width: 70%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
        }

        button{
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            background: #2563eb;
            color: white;
            cursor: pointer;
        }

        button:hover{
            background: #1d4ed8;
        }

        pre{
            background: #f8fafc;
            padding: 20px;
            border-radius: 12px;
            overflow: auto;
            max-height: 500px;
        }

        .erro{
            color: red;
            margin-bottom: 20px;
        }

        ul{
            color: #475569;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Explorador do SUAP</h1>

    <p>
        Digite um endpoint da API do SUAP para descobrir quais dados estão disponíveis.
    </p>

    @if($errors->any())
        <div class="erro">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="/admin/suap/explorador">
        @csrf

        <input
            type="text"
            name="endpoint"
            placeholder="Ex: minhas-informacoes/meus-dados/"
            value="{{ $endpoint ?? '' }}"
        >

        <button type="submit">
            Consultar
        </button>
    </form>

    <br>

    <strong>Sugestões para testar:</strong>

    <ul>
        <li>minhas-informacoes/meus-dados/</li>
        <li>ensino/</li>
        <li>ensino/diarios/</li>
        <li>ensino/turmas/</li>
        <li>ensino/matriculas/</li>
        <li>comum/</li>
        <li>rh/</li>
    </ul>

    @isset($resultado)

        <hr>

        <p>
            <strong>Status HTTP:</strong>
            {{ $status }}
        </p>

        <pre>{{ json_encode($resultado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>

    @endisset

</div>

</body>
</html>