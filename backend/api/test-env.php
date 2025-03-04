<?php
// Desativar cache do navegador
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Definir tipo de conteúdo como texto para facilitar a leitura
header('Content-Type: text/plain; charset=utf-8');

// Exibir erros para diagnóstico
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "=== TESTE DE VARIÁVEIS DE AMBIENTE E CONFIGURAÇÃO DO SERVIDOR ===\n\n";

// Verificar versão do PHP
echo "Versão do PHP: " . phpversion() . "\n\n";

// Verificar extensões carregadas
echo "Extensões PHP carregadas:\n";
$extensions = ['pdo', 'pdo_mysql', 'mysqli', 'curl', 'gd', 'json', 'mbstring', 'xml', 'zip'];
foreach ($extensions as $ext) {
    echo "- $ext: " . (extension_loaded($ext) ? 'Carregada' : 'Não carregada') . "\n";
}
echo "\n";

// Verificar variáveis de ambiente
echo "Variáveis de ambiente:\n";
$envVars = ['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASSWORD', 'SERVER_NAME', 'SERVER_ALIAS'];
foreach ($envVars as $var) {
    $value = getenv($var);
    echo "- $var: " . ($value ? ($var == 'DB_PASSWORD' ? '******' : $value) : 'não definido') . "\n";
}
echo "\n";

// Verificar diretórios e permissões
echo "Diretórios e permissões:\n";
$directories = [
    '/var/www/html/src/logs',
    '/var/www/html/src/frontend/uploads/logos'
];
foreach ($directories as $dir) {
    echo "- $dir: ";
    if (file_exists($dir)) {
        echo "Existe, ";
        echo "Permissões: " . substr(sprintf('%o', fileperms($dir)), -4) . ", ";
        echo "Gravável: " . (is_writable($dir) ? 'Sim' : 'Não');
    } else {
        echo "Não existe";
    }
    echo "\n";
}
echo "\n";

// Verificar configuração do Apache
echo "Configuração do Apache:\n";
echo "- SERVER_SOFTWARE: " . $_SERVER['SERVER_SOFTWARE'] . "\n";
echo "- DOCUMENT_ROOT: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "- SERVER_NAME: " . $_SERVER['SERVER_NAME'] . "\n";
echo "- SERVER_ADDR: " . $_SERVER['SERVER_ADDR'] . "\n";
echo "- SERVER_PORT: " . $_SERVER['SERVER_PORT'] . "\n";
echo "- HTTPS: " . (isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : 'off') . "\n";
echo "\n";

// Verificar configuração do mod_rewrite
echo "Configuração do mod_rewrite:\n";
echo "- mod_rewrite carregado: " . (in_array('mod_rewrite', apache_get_modules()) ? 'Sim' : 'Não') . "\n";
echo "\n";

// Verificar caminhos de arquivos importantes
echo "Caminhos de arquivos importantes:\n";
echo "- Diretório atual: " . __DIR__ . "\n";
echo "- Diretório raiz: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "- Caminho do script: " . $_SERVER['SCRIPT_FILENAME'] . "\n";
echo "\n";

// Verificar acesso ao banco de dados
echo "Teste rápido de acesso ao banco de dados:\n";
try {
    $host = getenv('DB_HOST') ?: 'db';
    $dbname = getenv('DB_NAME') ?: 'student_management_system';
    $user = getenv('DB_USER') ?: 'admin';
    $password = getenv('DB_PASSWORD') ?: 'Desbr@v@dor&s123';
    
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $password);
    echo "- Conexão com o banco de dados: Sucesso\n";
} catch (PDOException $e) {
    echo "- Conexão com o banco de dados: Falha - " . $e->getMessage() . "\n";
}

echo "\n=== FIM DO TESTE ===\n";
?> 