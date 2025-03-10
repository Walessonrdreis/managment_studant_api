<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes da Escola - Sistema de Gerenciamento Estudantil</title>
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
        .school-logo {
            max-width: 150px;
            max-height: 150px;
            object-fit: contain;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Detalhes da Escola</h1>
            <p>Sistema de Gerenciamento Estudantil</p>
        </header>

        <div class="navbar">
            <a href="{{ url('/') }}">← Voltar para Home</a>
            <a href="{{ url('/schools') }}">← Voltar para Lista de Escolas</a>
        </div>

        <div class="card">
            @if(isset($school))
                @if($school->logo)
                    <div style="text-align: center;">
                        <img src="{{ asset('storage/' . $school->logo) }}" alt="Logo {{ $school->name }}" class="school-logo">
                    </div>
                @endif
                
                <div class="profile-section">
                    <div class="info-item">
                        <span class="info-label">Nome:</span> {{ $school->name }}
                    </div>
                    <div class="info-item">
                        <span class="info-label">Endereço:</span> {{ $school->address ?? 'N/A' }}
                    </div>
                    <div class="info-item">
                        <span class="info-label">Telefone:</span> {{ $school->phone ?? 'N/A' }}
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email:</span> {{ $school->email ?? 'N/A' }}
                    </div>
                </div>

                @if(isset($school->students) && count($school->students) > 0)
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
                            @foreach($school->students as $student)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $student->name }}</td>
                                    <td>{{ $student->registration_number }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>Nenhum aluno matriculado nesta escola no momento.</p>
                @endif

                @if(isset($school->teachers) && count($school->teachers) > 0)
                    <h2>Professores</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nome</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($school->teachers as $teacher)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $teacher->name }}</td>
                                    <td>{{ $teacher->email ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>Nenhum professor associado a esta escola no momento.</p>
                @endif

                @if(isset($school->subjects) && count($school->subjects) > 0)
                    <h2>Disciplinas</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nome</th>
                                <th>Descrição</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($school->subjects as $subject)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $subject->name }}</td>
                                    <td>{{ $subject->description ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>Nenhuma disciplina associada a esta escola no momento.</p>
                @endif
            @else
                <p>Escola não encontrada.</p>
            @endif
        </div>
    </div>
</body>
</html> 