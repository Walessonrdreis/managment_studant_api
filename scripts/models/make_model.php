<?php

$modelName = readline("Digite o nome do modelo: ");

if (!$modelName) {
    echo "Nome do modelo não pode ser vazio.\n";
    exit(1);
}

$modelDir = __DIR__ . '/../../api/app/Models';

if (!is_dir($modelDir)) {
    mkdir($modelDir, 0755, true);
}

$modelFile = $modelDir . "/{$modelName}.php";

if (file_exists($modelFile)) {
    echo "O modelo '{$modelName}' já existe!\n";
    exit(1);
}

$modelContent = "<?php\n\nnamespace App\Models;\n\nuse Illuminate\Database\Eloquent\Model;\n\nclass {$modelName} extends Model {\n    // Definições do modelo aqui\n}\n";

file_put_contents($modelFile, $modelContent);

echo "Modelo '{$modelName}' criado com sucesso em '{$modelFile}'.\n";