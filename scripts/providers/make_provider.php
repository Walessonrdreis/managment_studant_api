<?php

$providerName = readline("Digite o nome do ServiceProvider (ex: MyCustomProvider): ");

if (!$providerName) {
    echo "Nome do ServiceProvider não pode ser vazio.\n";
    exit(1);
}

$providerDir = __DIR__ . '/../../api/app/Providers';

if (!is_dir($providerDir)) {
    mkdir($providerDir, 0755, true);
}

$providerFile = $providerDir . "/{$providerName}.php";

if (file_exists($providerFile)) {
    echo "O ServiceProvider '{$providerName}' já existe!\n";
    exit(1);
}

$providerContent = "<?php\n\nnamespace App\Providers;\n\nuse Illuminate\Support\ServiceProvider;\n\nclass {$providerName} extends ServiceProvider {\n    /**\n     * Register any application services.\n     */\n    public function register(): void\n    {\n        //\n    }\n\n    /**\n     * Bootstrap any application services.\n     */\n    public function boot(): void\n    {\n        //\n    }\n}\n";

file_put_contents($providerFile, $providerContent);

echo "ServiceProvider '{$providerName}' criado com sucesso em '{$providerFile}'.\n";
