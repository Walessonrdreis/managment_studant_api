<?php

class Config {
    private static $instance = null;
    private $config = [];

    private function __construct() {
        $configFile = __DIR__ . '/config.php';
        if (file_exists($configFile)) {
            $this->config = require $configFile;
        }
    }

    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function get(string $key, $default = null) {
        $parts = explode('.', $key);
        $config = $this->config;

        foreach ($parts as $part) {
            if (!isset($config[$part])) {
                return $default;
            }
            $config = $config[$part];
        }

        return $config;
    }

    public function set(string $key, $value): void {
        $parts = explode('.', $key);
        $config = &$this->config;

        foreach ($parts as $i => $part) {
            if ($i === count($parts) - 1) {
                $config[$part] = $value;
                break;
            }

            if (!isset($config[$part]) || !is_array($config[$part])) {
                $config[$part] = [];
            }

            $config = &$config[$part];
        }
    }

    public function has(string $key): bool {
        $parts = explode('.', $key);
        $config = $this->config;

        foreach ($parts as $part) {
            if (!isset($config[$part])) {
                return false;
            }
            $config = $config[$part];
        }

        return true;
    }

    public function all(): array {
        return $this->config;
    }

    private function __clone() {}
    public function __wakeup() {}
} 