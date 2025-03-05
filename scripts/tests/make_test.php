<?php

function createTestFile($name, $type) {
    $testDirectory = __DIR__ . '/../../tests/Unit'; // Diretório de testes
    $fileName = $testDirectory . '/' . $name . 'Test.php';

    // Verifica se o diretório de testes existe
    if (!is_dir($testDirectory)) {
        mkdir($testDirectory, 0755, true);
    }

    // Conteúdo do arquivo de teste
    $content = "<?php\n\n";
    $content .= "namespace Tests\\Unit;\n\n";
    $content .= "use PHPUnit\\Framework\\TestCase;\n";
    $content .= "use App\\$type\\$name;\n\n";
    $content .= "class {$name}Test extends TestCase {\n\n";
    $content .= "    public function testExample() {\n";
    $content .= "        // TODO: Implement test\n";
    $content .= "        \$this->assertTrue(true);\n";
    $content .= "    }\n";
    $content .= "}\n";

    // Cria o arquivo de teste
    file_put_contents($fileName, $content);
    echo "Arquivo de teste criado: $fileName\n";
}

// Menu para o usuário
echo "Gerador de Arquivos de Teste\n";
echo "Digite o nome da classe (sem o sufixo Test): ";
$name = trim(fgets(STDIN));

echo "Digite o tipo (Model, Controller, Service): ";
$type = trim(fgets(STDIN));

// Valida o tipo
if (!in_array($type, ['Model', 'Controller', 'Service'])) {
    echo "Tipo inválido. Use Model, Controller ou Service.\n";
    exit(1);
}

// Cria o arquivo de teste
createTestFile($name, $type);