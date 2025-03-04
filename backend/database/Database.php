<?php
require_once __DIR__ . '/../config/Config.php';

/**
 * Classe responsável por gerenciar a conexão com o banco de dados
 * Implementa o padrão Singleton para garantir uma única instância da conexão
 */
class Database {
    private static $instance = null;
    private $config;
    private $conn;
    private $connected = false;
    private $lastError = null;

    /**
     * Construtor privado para implementar Singleton
     * Carrega as configurações do banco de dados
     */
    private function __construct() {
        $this->config = Config::getInstance();
    }

    /**
     * Implementação do padrão Singleton
     * @return Database
     */
    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Obtém a conexão com o banco de dados
     * @return PDO
     * @throws Exception
     */
    public function getConnection(): PDO {
        try {
            if (!$this->connected || $this->conn === null) {
                $config = $this->config;
                
                // Construir DSN com todas as opções possíveis
                $dsn = sprintf(
                    "mysql:host=%s;port=%d;dbname=%s;charset=%s",
                    $config->get('database.host'),
                    $config->get('database.port', 3306),
                    $config->get('database.name'),
                    $config->get('database.charset', 'utf8mb4')
                );

                // Adicionar socket se configurado
                if ($socket = $config->get('database.unix_socket')) {
                    $dsn .= ";unix_socket=" . $socket;
                }

                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_PERSISTENT => true,
                    PDO::MYSQL_ATTR_INIT_COMMAND => sprintf(
                        "SET NAMES %s COLLATE %s",
                        $config->get('database.charset', 'utf8mb4'),
                        $config->get('database.collation', 'utf8mb4_unicode_ci')
                    )
                ];
                
                $this->conn = new PDO(
                    $dsn, 
                    $config->get('database.user'), 
                    $config->get('database.pass'), 
                    $options
                );
                
                $this->connected = true;
                $this->lastError = null;
            }
            return $this->conn;
        } catch(PDOException $e) {
            $this->connected = false;
            $this->lastError = $e->getMessage();
            throw new Exception("Erro na conexão com o banco de dados: " . $e->getMessage());
        }
    }

    /**
     * Verifica se a conexão está ativa
     * @return bool
     */
    public function isConnected(): bool {
        if (!$this->connected || $this->conn === null) {
            return false;
        }
        try {
            $this->conn->query('SELECT 1');
            return true;
        } catch (PDOException $e) {
            $this->connected = false;
            $this->lastError = $e->getMessage();
            return false;
        }
    }

    /**
     * Obtém o último erro ocorrido
     * @return string|null
     */
    public function getLastError(): ?string {
        return $this->lastError;
    }

    /**
     * Fecha a conexão com o banco de dados
     */
    public function closeConnection(): void {
        $this->conn = null;
        $this->connected = false;
    }

    /**
     * Previne a clonagem do objeto (padrão Singleton)
     */
    private function __clone() {}

    /**
     * Previne a deserialização do objeto (padrão Singleton)
     */
    public function __wakeup() {}
}
?> 