<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gerenciamento Estudantil</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #333;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }
        header {
            background-color: #3490dc;
            color: white;
            text-align: center;
            padding: 1em;
            margin-bottom: 2em;
            border-radius: 5px;
        }
        .card {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            padding: 20px;
        }
        h1, h2 {
            color: #3490dc;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin-bottom: 10px;
        }
        a {
            color: #3490dc;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 3px;
            transition: background-color 0.3s;
        }
        a:hover {
            background-color: #e8f1fa;
        }
        .view-description {
            color: #666;
            font-style: italic;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Sistema de Gerenciamento Estudantil</h1>
            <p>Acesse as diferentes seções do sistema</p>
        </header>

        <div class="card">
            <h2>Views Disponíveis</h2>
            <ul>
                <li>
                    <a href="{{ url('/student') }}">Visualização de Aluno</a>
                    <span class="view-description">- Template padrão para visualização de aluno</span>
                </li>
                <li>
                    <a href="{{ url('/students') }}">Lista de Alunos</a>
                    <span class="view-description">- Visualizar todos os alunos cadastrados</span>
                </li>
                <li>
                    <a href="{{ url('/teachers') }}">Lista de Professores</a>
                    <span class="view-description">- Visualizar todos os professores cadastrados</span>
                </li>
                <li>
                    <a href="{{ url('/subjects') }}">Lista de Disciplinas</a>
                    <span class="view-description">- Visualizar todas as disciplinas cadastradas</span>
                </li>
                <li>
                    <a href="{{ url('/classrooms') }}">Lista de Salas de Aula</a>
                    <span class="view-description">- Visualizar todas as salas de aula cadastradas</span>
                </li>
                <li>
                    <a href="{{ url('/schools') }}">Lista de Escolas</a>
                    <span class="view-description">- Visualizar todas as escolas cadastradas</span>
                </li>
            </ul>
        </div>

        <div class="card">
            <h2>Exemplo de Visualização Individual</h2>
            <p>Para visualizar um aluno específico, use o formato:</p>
            <code>{{ url('/student/ID') }}</code> (substitua ID pelo número de identificação do aluno)
        </div>
    </div>
</body>
</html> 