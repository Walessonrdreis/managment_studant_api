<?php

class Migration {
    protected $db;
    protected static $migrations = [];
    protected static $migrationsTable = 'migrations';

    public function __construct() {
        $this->db = new Database();
    }

    public static function register($migration) {
        self::$migrations[] = $migration;
    }

    public function createMigrationsTable() {
        $conn = $this->db->getConnection();
        
        $sql = "CREATE TABLE IF NOT EXISTS " . self::$migrationsTable . " (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255) NOT NULL,
            batch INT NOT NULL,
            executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";

        try {
            $conn->exec($sql);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Erro ao criar tabela de migrations: " . $e->getMessage());
        }
    }

    public function getExecutedMigrations() {
        $conn = $this->db->getConnection();
        
        try {
            $stmt = $conn->query("SELECT migration FROM " . self::$migrationsTable);
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar migrations executadas: " . $e->getMessage());
        }
    }

    public function migrate() {
        $this->createMigrationsTable();
        
        $executedMigrations = $this->getExecutedMigrations();
        $newMigrations = [];
        $conn = $this->db->getConnection();

        // Pegar o último batch
        $lastBatchQuery = $conn->query("SELECT MAX(batch) as last_batch FROM " . self::$migrationsTable);
        $lastBatch = (int)$lastBatchQuery->fetch(PDO::FETCH_ASSOC)['last_batch'];
        $currentBatch = $lastBatch + 1;

        try {
            $conn->beginTransaction();

            foreach (self::$migrations as $migration) {
                $migrationName = get_class($migration);
                if (!in_array($migrationName, $executedMigrations)) {
                    echo "Executando migration: " . $migrationName . PHP_EOL;
                    
                    $migration->up();
                    
                    $stmt = $conn->prepare("INSERT INTO " . self::$migrationsTable . " (migration, batch) VALUES (?, ?)");
                    $stmt->execute([$migrationName, $currentBatch]);
                    
                    $newMigrations[] = $migrationName;
                }
            }

            $conn->commit();
            return $newMigrations;

        } catch (Exception $e) {
            $conn->rollBack();
            throw new Exception("Erro ao executar migrations: " . $e->getMessage());
        }
    }

    public function rollback($steps = 1) {
        $conn = $this->db->getConnection();
        
        try {
            // Pegar os últimos batches para rollback
            $stmt = $conn->prepare("SELECT DISTINCT batch FROM " . self::$migrationsTable . " ORDER BY batch DESC LIMIT ?");
            $stmt->execute([$steps]);
            $batches = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if (empty($batches)) {
                return [];
            }

            $conn->beginTransaction();
            
            $rolledBack = [];
            foreach ($batches as $batch) {
                $stmt = $conn->prepare("SELECT migration FROM " . self::$migrationsTable . " WHERE batch = ?");
                $stmt->execute([$batch]);
                $migrations = $stmt->fetchAll(PDO::FETCH_COLUMN);

                foreach ($migrations as $migrationName) {
                    foreach (self::$migrations as $migration) {
                        if (get_class($migration) === $migrationName) {
                            echo "Revertendo migration: " . $migrationName . PHP_EOL;
                            
                            $migration->down();
                            
                            $stmt = $conn->prepare("DELETE FROM " . self::$migrationsTable . " WHERE migration = ?");
                            $stmt->execute([$migrationName]);
                            
                            $rolledBack[] = $migrationName;
                            break;
                        }
                    }
                }
            }

            $conn->commit();
            return $rolledBack;

        } catch (Exception $e) {
            $conn->rollBack();
            throw new Exception("Erro ao reverter migrations: " . $e->getMessage());
        }
    }

    public function reset() {
        $conn = $this->db->getConnection();
        
        try {
            $stmt = $conn->query("SELECT COUNT(DISTINCT batch) as batch_count FROM " . self::$migrationsTable);
            $batchCount = (int)$stmt->fetch(PDO::FETCH_ASSOC)['batch_count'];
            
            return $this->rollback($batchCount);
        } catch (Exception $e) {
            throw new Exception("Erro ao resetar migrations: " . $e->getMessage());
        }
    }

    public function refresh() {
        $this->reset();
        return $this->migrate();
    }
} 