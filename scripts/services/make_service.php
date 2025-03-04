<?php

$serviceName = readline("Digite o nome do serviço: ");

if (!$serviceName) {
    echo "Nome do serviço não pode ser vazio.\n";
    exit(1);
}

$serviceDir = __DIR__ . '/../../api/app/Services';

if (!is_dir($serviceDir)) {
    mkdir($serviceDir, 0755, true);
}

$serviceFile = $serviceDir . "/{$serviceName}Service.php";

if (file_exists($serviceFile)) {
    echo "O serviço '{$serviceName}' já existe!\n";
    exit(1);
}

$serviceContent = "<?php\n\nnamespace App\Services;\n\nclass {$serviceName} {\n    // Métodos do serviço aqui\n}\n";

file_put_contents($serviceFile, $serviceContent);

echo "Serviço '{$serviceName}' criado com sucesso em '{$serviceFile}'.\n";
