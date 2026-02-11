<?php
/**
 * 安全配置載入器
 * 從 .env 讀取配置，提供安全函數
 */
class Config {
    private static $config = [];
    private static $loaded = false;

    /**
     * 載入 .env 文件
     */
    public static function load() {
        if(self::$loaded) return;

        $envFile = dirname(__FILE__, 2) . '/.env';
        if(!file_exists($envFile)) {
            die('配置文件不存在');
        }

        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach($lines as $line) {
            if(strpos(trim($line), '#') === 0) continue; // 跳過註釋

            $parts = explode('=', $line, 2);
            if(count($parts) == 2) {
                $key = trim($parts[0]);
                $value = trim($parts[1]);
                self::$config[$key] = $value;
            }
        }

        self::$loaded = true;
    }

    /**
     * 獲取配置值
     */
    public static function get($key, $default = null) {
        self::load();
        return self::$config[$key] ?? $default;
    }

    /**
     * 獲取資料庫配置
     */
    public static function getDB() {
        self::load();
        return [
            'host' => self::$config['DB_HOST'] ?? 'localhost',
            'port' => self::$config['DB_PORT'] ?? 3306,
            'user' => self::$config['DB_USER'] ?? '',
            'pass' => self::$config['DB_PASS'] ?? '',
            'name' => self::$config['DB_NAME'] ?? ''
        ];
    }

    /**
     * 獲取 Telegram 配置
     */
    public static function getTelegram() {
        self::load();
        return [
            'token' => self::$config['TELEGRAM_BOT_TOKEN'] ?? '',
            'admin_id' => self::$config['TELEGRAM_ADMIN_ID'] ?? ''
        ];
    }

    /**
     * 獲取 TRON 配置
     */
    public static function getTRON() {
        self::load();
        return [
            'api_url' => self::$config['TRON_API_URL'] ?? 'https://api.trongrid.io',
            'api_key' => self::$config['TRON_API_KEY'] ?? '',
            'address' => self::$config['USDT_TRC20_ADDRESS'] ?? ''
        ];
    }

    /**
     * 獲取安全配置
     */
    public static function getSecurity() {
        self::load();
        return [
            'encryption_key' => self::$config['ENCRYPTION_KEY'] ?? '',
            'token_expiry' => (int)(self::$config['TOKEN_EXPIRY_HOURS'] ?? 24),
            'allowed_origins' => array_map('trim', explode(',', self::$config['ALLOWED_ORIGINS'] ?? ''))
        ];
    }

    /**
     * 簡化函數：獲取值
     */
    public static function val($key) {
        return self::get($key);
    }
}

/**
 * 安全輔助函數
 */
class Security {
    /**
     * XSS 過濾
     */
    public static function xssClean($input) {
        if(is_array($input)) {
            return array_map([self::class, 'xssClean'], $input);
        }
        return htmlspecialchars(strip_tags($input), ENT_QUOTES, 'UTF-8');
    }

    /**
     * SQL 注入防護（使用參數化查詢，但額外過濾）
     */
    public static function sanitize($input, $maxLength = 1000) {
        if(is_array($input)) {
            return array_map([self::class, 'sanitize'], $input);
        }
        $input = substr(trim($input), 0, $maxLength);
        // 移除潛在危險字符
        $input = preg_replace('/[\x00-\x1f\x7f]/', '', $input);
        return $input;
    }

    /**
     * 驗證地址格式
     */
    public static function isValidAddress($address) {
        return preg_match('/^T[a-zA-Z0-9]{33}$/', $address);
    }

    /**
     * 驗證 TxHash 格式
     */
    public static function isValidTxHash($hash) {
        return preg_match('/^[a-fA-F0-9]{64}$/', $hash);
    }

    /**
     * 檢查是否為管理員
     */
    public static function isAdmin($userId) {
        return $userId == 1;
    }

    /**
     * 速率限制
     */
    private static $rateLimit = [];
    public static function rateLimit($key, $maxRequests = 10, $window = 60) {
        $now = time();
        if(!isset(self::$rateLimit[$key])) {
            self::$rateLimit[$key] = [];
        }
        // 清理過期記錄
        self::$rateLimit[$key] = array_filter(self::$rateLimit[$key], function($t) use ($now, $window) {
            return ($now - $t) < $window;
        });
        // 檢查限制
        if(count(self::$rateLimit[$key]) >= $maxRequests) {
            return false;
        }
        self::$rateLimit[$key][] = $now;
        return true;
    }

    /**
     * 記錄安全事件
     */
    public static function logSecurityEvent($type, $details, $ip = null) {
        $log = [
            'time' => date('Y-m-d H:i:s'),
            'type' => $type,
            'details' => $details,
            'ip' => $ip ?? $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        ];
        $logFile = dirname(__FILE__, 2) . '/logs/security.log';
        @file_put_contents($logFile, json_encode($log) . "\n", FILE_APPEND);
    }

    /**
     * CORS 頭部設置
     */
    public static function setCORS() {
        $config = Config::getSecurity();
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
        if(in_array($origin, $config['allowed_origins'])) {
            header("Access-Control-Allow-Origin: {$origin}");
            header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type, Authorization');
            header('Access-Control-Allow-Credentials: true');
        }
    }

    /**
     * 請求來源驗證
     */
    public static function verifyOrigin() {
        $config = Config::getSecurity();
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
        if(!empty($origin) && !in_array($origin, $config['allowed_origins'])) {
            self::logSecurityEvent('invalid_origin', ['origin' => $origin]);
            return false;
        }
        return true;
    }

    /**
     * 驗證充值訂單
     */
    public static function validatePaymentOrder($amount, $txHash) {
        // 驗證金額
        if($amount <= 0 || $amount > 10000) {
            return ['valid' => false, 'error' => '金額無效'];
        }
        // 驗證 TxHash
        if(!self::isValidTxHash($txHash)) {
            return ['valid' => false, 'error' => '交易哈希格式無效'];
        }
        return ['valid' => true];
    }
}

// 加載配置
Config::load();
