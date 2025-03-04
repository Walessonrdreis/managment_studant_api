<?php

class ApiResponse {
    public static function success($data = null, ?string $message = null) {
        self::send(true, $data, $message, 200);
    }

    public static function error($message, $code = 400) {
        self::send(false, null, $message, $code);
    }

    public static function created($data = null, ?string $message = null): void {
        self::send(true, $data, $message, 201);
    }

    public static function validationError(array $errors): void {
        self::send(false, ['errors' => $errors], 'Erro de validação', 422);
    }

    public static function badRequest(string $message): void {
        self::send(false, null, $message, 400);
    }

    public static function notFound(string $message): void {
        self::send(false, null, $message, 404);
    }

    private static function send(bool $success, $data, ?string $message, int $statusCode): void {
        // Limpa qualquer saída anterior
        if (ob_get_level()) {
            ob_clean();
        }

        // Define os headers
        if (!headers_sent()) {
            header('Content-Type: application/json');
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type, Authorization');
            http_response_code($statusCode);
        }
        
        // Gera a resposta JSON
        echo json_encode([
            'success' => $success,
            'data' => $data,
            'message' => $message,
            'timestamp' => date('c')
        ]);

        // Força o envio da resposta
        if (ob_get_level()) {
            ob_end_flush();
        }
        exit;
    }
} 