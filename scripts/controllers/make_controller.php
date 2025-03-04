<?php

$controllerName = readline("Digite o nome do controlador: ");

if (!$controllerName) {
    echo "Nome do controlador não pode ser vazio.\n";
    exit(1);
}

$controllerDir = __DIR__ . '/../../api/app/Http/Controllers';

if (!is_dir($controllerDir)) {
    mkdir($controllerDir, 0755, true);
}

$controllerFile = $controllerDir . "/{$controllerName}Controller.php";

if (file_exists($controllerFile)) {
    echo "O controlador '{$controllerName}Controller' já existe!\n";
    exit(1);
}

$controllerContent = "<?php\n\nnamespace App\Http\Controllers;\n\nclass {$controllerName}Controller {\n    // Métodos do controlador aqui\n}\n";

file_put_contents($controllerFile, $controllerContent);

echo "Controlador '{$controllerName}Controller' criado com sucesso em '{$controllerFile}'.\n";