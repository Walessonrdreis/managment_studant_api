<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Aluno - Sistema de Gerenciamento Estudantil</title>
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
        .info-item {
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }
        .info-label {
            font-weight: bold;
            color: #3490dc;
        }
        .navbar {
            margin-bottom: 20px;
        }
        .navbar a {
            text-decoration: none;
            color: #3490dc;
            margin-right: 15px;
        }
        .btn {
            display: inline-block;
            padding: 6px 12px;
            background-color: #3490dc;
            color: white;
            text-decoration: none;
            border-radius: 3px;
            font-size: 14px;
            cursor: pointer;
        }
    </style>
    <script>
        @if(isset($student))
        function printPDF() {
            const studentId = {{ $student->id }};
            window.open(`/gerar-pdf-aluno/${studentId}`, '_blank');
        }
        @endif
    </script>
</head>
<body>
    <div class="container">
        <header>
            <h1>Detalhes do Aluno</h1>
            <p>Sistema de Gerenciamento Estudantil</p>
        </header>

        <div class="navbar">
            <a href="{{ url('/') }}">← Voltar para Home</a>
            <a href="{{ url('/students') }}">← Voltar para Lista de Alunos</a>
        </div>

        <div class="card">
            @if(isset($student))
                <div class="info-item">
                    <span class="info-label">Nome:</span> {{ $student->name }}
                </div>
                <div class="info-item">
                    <span class="info-label">Matrícula:</span> {{ $student->registration_number }}
                </div>
                <div class="info-item">
                    <span class="info-label">Escola:</span> {{ $student->school ? $student->school->name : 'N/A' }}
                </div>

                @if($student->appointments && count($student->appointments) > 0)
                    <h2>Aulas Agendadas</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Data</th>
                                <th>Dia da Semana</th>
                                <th>Horário</th>
                                <th>Professor(a)</th>
                                <th>Disciplina</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($student->appointments as $aula)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $aula->date }}</td>
                                    <td>{{ \Carbon\Carbon::parse($aula->date)->translatedFormat('l') }}</td>
                                    <td>{{ $aula->time }}</td>
                                    <td>{{ $aula->teacher ? $aula->teacher->name : 'N/A' }}</td>
                                    <td>{{ $aula->subject ? $aula->subject->name : 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>Este aluno não possui aulas agendadas no momento.</p>
                @endif
                
                <button class="btn" onclick="printPDF()">Imprimir PDF</button>
            @else
                <p>Aluno não encontrado ou nenhum ID de aluno fornecido.</p>
            @endif
        </div>
    </div>
</body>
</html>
