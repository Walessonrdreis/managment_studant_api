<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Alunos - Sistema de Gerenciamento Estudantil</title>
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
            width: 90%;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            padding: 12px;
            text-align: left;
        }
        td {
            padding: 10px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f2f2f2;
        }
        .actions {
            display: flex;
            gap: 5px;
        }
        .btn {
            display: inline-block;
            padding: 6px 12px;
            background-color: #3490dc;
            color: white;
            text-decoration: none;
            border-radius: 3px;
            font-size: 14px;
        }
        .btn-info {
            background-color: #3490dc;
        }
        .btn-info:hover {
            background-color: #2779bd;
        }
        .navbar {
            margin-bottom: 20px;
        }
        .navbar a {
            text-decoration: none;
            color: #3490dc;
            margin-right: 15px;
        }
        .empty-message {
            text-align: center;
            padding: 20px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Lista de Alunos</h1>
            <p>Sistema de Gerenciamento Estudantil</p>
        </header>

        <div class="navbar">
            <a href="{{ url('/') }}">← Voltar para Home</a>
        </div>

        <div class="card">
            @if(isset($students) && count($students) > 0)
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome</th>
                            <th>Matrícula</th>
                            <th>Escola</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            <tr>
                                <td>{{ $student->id }}</td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->registration_number }}</td>
                                <td>{{ $student->school ? $student->school->name : 'N/A' }}</td>
                                <td class="actions">
                                    <a href="{{ url('/student/' . $student->id) }}" class="btn btn-info">Visualizar</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-message">
                    <p>Nenhum aluno cadastrado no momento.</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html> 