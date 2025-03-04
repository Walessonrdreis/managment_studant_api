<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../utils/ApiResponse.php';
require_once __DIR__ . '/../../utils/Logger.php';
require_once __DIR__ . '/../../database/Database.php';

abstract class BaseController {
    protected $db;
    protected $conn;
    protected $logger;
    protected $errorMessages = [];

    public function __construct() {
        try {
            $this->logger = Logger::getInstance();
            $this->db = Database::getInstance();
            $this->conn = $this->db->getConnection();
        } catch (Exception $e) {
            ApiResponse::error($e->getMessage(), 500);
        }
    }

    /**
     * Método para validar dados de entrada
     * @param array $data Dados a serem validados
     * @param array $rules Regras de validação
     * @return bool
     */
    protected function validate(array $data, array $rules): bool {
        $this->errorMessages = [];
        
        foreach ($rules as $field => $rule) {
            if (!isset($data[$field]) && $rule['required']) {
                $this->errorMessages[$field] = $rule['message'] ?? "Campo {$field} é obrigatório";
                continue;
            }

            if (isset($data[$field])) {
                $value = $data[$field];

                if (isset($rule['type'])) {
                    switch ($rule['type']) {
                        case 'email':
                            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                $this->errorMessages[$field] = "Email inválido";
                            }
                            break;
                        case 'date':
                            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
                                $this->errorMessages[$field] = "Data inválida";
                            }
                            break;
                    }
                }

                if (isset($rule['min']) && strlen($value) < $rule['min']) {
                    $this->errorMessages[$field] = $rule['message'] ?? "Campo {$field} deve ter no mínimo {$rule['min']} caracteres";
                }

                if (isset($rule['max']) && strlen($value) > $rule['max']) {
                    $this->errorMessages[$field] = $rule['message'] ?? "Campo {$field} deve ter no máximo {$rule['max']} caracteres";
                }

                if (isset($rule['pattern']) && !preg_match($rule['pattern'], $value)) {
                    $this->errorMessages[$field] = $rule['message'] ?? "Campo {$field} está em formato inválido";
                }
            }
        }

        return empty($this->errorMessages);
    }

    /**
     * Método para sanitizar dados de entrada
     * @param array $data Dados a serem sanitizados
     * @return array
     */
    protected function sanitize(array $data): array {
        $sanitized = [];
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $sanitized[$key] = trim(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
            } else if (is_array($value)) {
                $sanitized[$key] = $this->sanitize($value);
            } else {
                $sanitized[$key] = $value;
            }
        }
        return $sanitized;
    }

    /**
     * Método para gerar resposta JSON
     * @param bool $success Status da operação
     * @param mixed $data Dados da resposta
     * @param string|null $message Mensagem opcional
     * @param int $statusCode Código HTTP
     */
    protected function jsonResponse(bool $success, $data = null, ?string $message = null, int $statusCode = 200): void {
        http_response_code($statusCode);
        echo json_encode([
            'success' => $success,
            'data' => $data,
            'message' => $message,
            'errors' => !empty($this->errorMessages) ? $this->errorMessages : null
        ]);
        exit;
    }

    /**
     * Método para verificar o método HTTP
     * @param string $method Método esperado
     */
    protected function requireMethod(string $method): void {
        if ($_SERVER['REQUEST_METHOD'] !== $method) {
            $response = [
                'success' => false,
                'message' => "Método {$_SERVER['REQUEST_METHOD']} não permitido"
            ];
            echo json_encode($response);
            exit;
        }
    }

    /**
     * Método para obter dados do corpo da requisição
     * @return array
     */
    protected function getRequestData(): array {
        $data = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            ApiResponse::badRequest("Dados inválidos");
        }
        return $this->sanitize($data ?? []);
    }

    /**
     * Método para tratar erros
     * @param Exception $e Exceção capturada
     * @param int $statusCode Código HTTP opcional
     */
    protected function handleError(Exception $e, int $statusCode = 500): void {
        $this->logger->error($e->getMessage(), [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        
        ApiResponse::error($e->getMessage(), $statusCode);
    }

    /**
     * Método para iniciar uma transação
     */
    protected function beginTransaction(): void {
        $this->conn->beginTransaction();
    }

    /**
     * Método para confirmar uma transação
     */
    protected function commit(): void {
        $this->conn->commit();
    }

    /**
     * Método para reverter uma transação
     */
    protected function rollback(): void {
        if ($this->conn->inTransaction()) {
            $this->conn->rollBack();
        }
    }
} 