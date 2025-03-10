<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes da Disciplina - Sistema de Gerenciamento Estudantil</title>
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
        .profile-section {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 20px;
        }
        .info-item {
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }
        .info-label {
            font-weight: bold;
            color: #3490dc;
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
        .navbar {
            margin-bottom: 20px;
        }
        .navbar a {
            text-decoration: none;
            color: #3490dc;
            margin-right: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Detalhes da Disciplina</h1>
            <p>Sistema de Gerenciamento Estudantil</p>
        </header>

        <div class="navbar">
            <a href="{{ url('/') }}">← Voltar para Home</a>
            <a href="{{ url('/subjects') }}">← Voltar para Lista de Disciplinas</a>
        </div>

        <div class="card">
            @if(isset($subject))
                <div class="profile-section">
                    <div class="info-item">
                        <span class="info-label">Nome:</span> {{ $subject->name }}
                    </div>
                    <div class="info-item">
                        <span class="info-label">Descrição:</span> {{ $subject->description ?? 'N/A' }}
                    </div>
                    <div class="info-item">
                        <span class="info-label">Escola:</span> {{ $subject->school ? $subject->school->name : 'N/A' }}
                    </div>
                </div>

                @if(isset($subject->students) && count($subject->students) > 0)
                    <h2>Alunos Matriculados</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nome</th>
                                <th>Matrícula</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subject->students as $student)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $student->name }}</td>
                                    <td>{{ $student->registration_number }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>Nenhum aluno matriculado nesta disciplina no momento.</p>
                @endif

                @if(isset($subject->classrooms) && count($subject->classrooms) > 0)
                    <h2>Salas de Aula</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nome</th>
                                <th>Capacidade</th>
                                <th>Professor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subject->classrooms as $classroom)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $classroom->name }}</td>
                                    <td>{{ $classroom->capacity ?? 'N/A' }}</td>
                                    <td>{{ $classroom->teacher ? $classroom->teacher->name : 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>Nenhuma sala de aula associada a esta disciplina no momento.</p>
                @endif
            @else
                <p>Disciplina não encontrada.</p>
            @endif
        </div>
    </div>
</body>
</html> 