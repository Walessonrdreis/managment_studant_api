<?php

class Logger {
    private static $instance = null;
    private $logFile;
    private $config;

    private function __construct() {
        require_once __DIR__ . '/../config/Config.php';
        $this->config = Config::getInstance();
        $this->logFile = $this->config->get('logging.file');
        
        // Cria o diretório de logs se não existir
        $logDir = dirname($this->logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }
    }

    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function debug($message, array $context = []): void {
        $this->log('DEBUG', $message, $context);
    }

    public function info($message, array $context = []): void {
        $this->log('INFO', $message, $context);
    }

    public function warning($message, array $context = []): void {
        $this->log('WARNING', $message, $context);
    }

    public function error($message, array $context = []): void {
        $this->log('ERROR', $message, $context);
    }

    private function log(string $level, $message, array $context = []): void {
        if (!$this->config->get('logging.enabled', true)) {
            return;
        }

        $logLevel = strtoupper($this->config->get('logging.level', 'debug'));
        $levels = ['DEBUG' => 0, 'INFO' => 1, 'WARNING' => 2, 'ERROR' => 3];

        if ($levels[$level] < $levels[$logLevel]) {
            return;
        }

        $timestamp = date('Y-m-d H:i:s');
        $contextStr = empty($context) ? '' : ' ' . json_encode($context);
        $logMessage = "[$timestamp] $level: $message$contextStr" . PHP_EOL;

        error_log($logMessage, 3, $this->logFile);
    }

    private function __clone() {}
    public function __wakeup() {}
} 