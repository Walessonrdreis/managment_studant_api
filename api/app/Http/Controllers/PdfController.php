<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aluno; // Certifique-se de que o modelo Aluno está correto
use Dompdf\Dompdf;

class PdfController extends Controller
{
    public function gerarPdf($aluno_id)
    {
        $aluno = Aluno::findOrFail($aluno_id); // Obtém os dados do aluno

        // Cria uma nova instância do Dompdf
        $dompdf = new Dompdf();

        // Configura o conteúdo HTML do PDF
        $html = view('pdf.aluno', compact('aluno'))->render(); // Crie uma view chamada pdf/aluno.blade.php

        // Carrega o HTML no Dompdf
        $dompdf->loadHtml($html);

        // (Opcional) Configura o tamanho e a orientação do papel
        $dompdf->setPaper('A4', 'landscape');

        // Renderiza o PDF
        $dompdf->render();

        // Envia o PDF para o navegador
        return $dompdf->stream('aluno_' . $aluno->matricula . '.pdf');
    }
}
