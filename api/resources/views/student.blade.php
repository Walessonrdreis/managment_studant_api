<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dados do Aluno</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
        }
        .header img {
            max-width: 100px;
        }
        .info {
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
    </style>
    <script>
        function printPDF() {
            const studentId = {{ $student->id }}; // Supondo que você tenha o ID do aluno disponível
            window.open(`/gerar-pdf-aluno/${studentId}`, '_blank'); // Abre o PDF em uma nova aba
        }
    </script>
</head>
<body>
    <div class="header">
        <img src="{{ $student->school->logo ?? 'default_logo.png' }}" alt="Logo da Escola">
        <h1>{{ $student->school->name }}</h1>
    </div>
    <div class="info">
        <p>Nome do Aluno: {{ $student->name }}</p>
        <p>Matrícula: {{ $student->registration_number }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>Aula</th>
                <th>Data</th>
                <th>Dia</th>
                <th>Hora</th>
                <th>Professor(a)</th>
                <th>Disciplina</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($student->appointments as $aula)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $aula->date }}</td>
                    <td>{{ \Carbon\Carbon::parse($aula->date)->translatedFormat('l') }}</td>
                    <td>{{ $aula->time }}</td>
                    <td>{{ $aula->teacher->name }}</td>
                    <td>{{ $aula->subject->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <button onclick="printPDF()">Imprimir PDF</button>
</body>
</html>
