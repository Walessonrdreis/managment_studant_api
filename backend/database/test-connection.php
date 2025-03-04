<?php
require_once __DIR__ . '/Database.php';

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    if ($db->isConnected()) {
        echo "Conexão estabelecida com sucesso!\n";
        
        // Testar consulta
        $result = $conn->query("SELECT VERSION() as version")->fetch();
        echo "Versão do MySQL: " . $result['version'] . "\n";
        
        // Verificar banco de dados atual
        $result = $conn->query("SELECT DATABASE() as db")->fetch();
        echo "Banco de dados atual: " . $result['db'] . "\n";
        
        // Testar tabela de disciplinas
        $result = $conn->query("SELECT COUNT(*) as total FROM disciplinas")->fetch();
        echo "Total de disciplinas: " . $result['total'] . "\n";
    } else {
        echo "Não foi possível estabelecer conexão.\n";
        echo "Último erro: " . $db->getLastError() . "\n";
    }
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
} 