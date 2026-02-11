<?php
/**
 * Telegram Bot 控制腳本
 * 用法：
 *   php botctl.php start   - 啟動 Bot
 *   php botctl.php stop   - 停止 Bot
 *   php botctl.php status - 查詢狀態
 *   php botctl.php restart - 重啟 Bot
 */

define('ROOT_PATH', dirname(__FILE__));
define('BOT_PID_FILE', ROOT_PATH . '/data/bot.pid');
define('BOT_STOP_FILE', ROOT_PATH . '/data/bot.stop');

class BotController {
    private $db;

    function __construct() {
        $this->db = $this->connectDB();
    }

    private function connectDB() {
        $envFile = ROOT_PATH . '/.env';
        $config = [];
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) continue;
                if (strpos($line, '=') !== false) {
                    list($k, $v) = explode('=', $line, 2);
                    $config[trim($k)] = trim($v);
                }
            }
        }

        $link = mysqli_connect(
            $config['DB_HOST'] ?? 'localhost',
            $config['DB_USER'] ?? 'root',
            $config['DB_PASS'] ?? '',
            $config['DB_NAME'] ?? 'xss_platform',
            $config['DB_PORT'] ?? 3306
        );
        if ($link) mysqli_set_charset($link, 'utf8mb4');
        return $link;
    }

    private function tb($name) {
        $prefix = 'oc_';
        $envFile = ROOT_PATH . '/.env';
        if (file_exists($envFile)) {
            foreach (file($envFile) as $line) {
                if (strpos($line, 'DB_PREFIX') === 0) {
                    $prefix = trim(explode('=', $line, 2)[1]) . '_';
                    break;
                }
            }
        }
        return $prefix . $name;
    }

    function getStatus() {
        if (!$this->db) return ['status' => 'error', 'msg' => '數據庫連接失敗'];

        $result = mysqli_query($this->db, "SELECT * FROM " . $this->tb('bot_status') . " WHERE id=1");
        $status = mysqli_fetch_assoc($result);

        if (!$status) {
            return ['status' => 'unknown', 'msg' => '未找到狀態記錄'];
        }

        // 檢查進程
        $running = false;
        if ($status['pid'] && $status['status'] == 'running') {
            $pid = $status['pid'];
            if (posix_kill($pid, 0) || (PHP_OS !== 'WINNT' && file_exists("/proc/$pid"))) {
                $running = true;
            }
        }

        return [
            'status' => $running ? 'running' : 'stopped',
            'pid' => $status['pid'],
            'start_time' => $status['start_time'],
            'last_update' => $status['last_update'],
            'message_count' => $status['message_count'],
            'error_count' => $status['error_count'],
            'uptime' => $status['start_time'] ? floor((time() - $status['start_time']) / 60) : 0
        ];
    }

    function start() {
        // 檢查是否已在運行
        $status = $this->getStatus();
        if ($status['status'] == 'running') {
            return ['success' => false, 'msg' => 'Bot 已在運行中 (PID: ' . $status['pid'] . ')'];
        }

        // 清理舊的停止文件
        if (file_exists(BOT_STOP_FILE)) unlink(BOT_STOP_FILE);

        // 啟動守護進程
        $cmd = "nohup php " . ROOT_PATH . "/bot_daemon.php >> " . ROOT_PATH . "/logs/bot_daemon.log 2>&1 &";
        exec($cmd);

        // 等待一下檢查狀態
        sleep(2);
        $newStatus = $this->getStatus();

        if ($newStatus['status'] == 'running') {
            // 記錄日誌
            $this->logAction('start', '手動啟動');
            return ['success' => true, 'msg' => 'Bot 已啟動 (PID: ' . $newStatus['pid'] . ')'];
        }

        return ['success' => false, 'msg' => '啟動失敗，請檢查日誌'];
    }

    function stop() {
        $status = $this->getStatus();

        if ($status['status'] != 'running') {
            return ['success' => false, 'msg' => 'Bot 未在運行'];
        }

        // 發送停止信號
        if ($status['pid']) {
            posix_kill($status['pid'], SIGTERM);
        }

        // 創建停止文件
        touch(BOT_STOP_FILE);

        // 等待停止
        sleep(3);

        $this->logAction('stop', '手動停止');
        return ['success' => true, 'msg' => '停止信號已發送'];
    }

    function restart() {
        $result = $this->stop();
        if ($result['success']) {
            sleep(2);
            return $this->start();
        }
        return $result;
    }

    function logAction($action, $details) {
        if (!$this->db) return;
        $now = time();
        mysqli_query($this->db, "INSERT INTO " . $this->tb('bot_logs') . " (action, details, created_at) VALUES ('$action', '" . mysqli_real_escape_string($this->db, $details) . "', $now)");
    }

    function getLogs($limit = 50) {
        if (!$this->db) return [];
        $result = mysqli_query($this->db, "SELECT * FROM " . $this->tb('bot_logs') . " ORDER BY id DESC LIMIT " . intval($limit));
        $logs = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $logs[] = $row;
        }
        return $logs;
    }
}

// CLI 處理
if (php_sapi_name() === 'cli' && isset($argv[1])) {
    $action = $argv[1];
    $controller = new BotController();

    switch ($action) {
        case 'start':
            $result = $controller->start();
            echo $result['msg'] . "\n";
            exit($result['success'] ? 0 : 1);

        case 'stop':
            $result = $controller->stop();
            echo $result['msg'] . "\n";
            exit($result['success'] ? 0 : 1);

        case 'restart':
            $result = $controller->restart();
            echo $result['msg'] . "\n";
            exit($result['success'] ? 0 : 1);

        case 'status':
            $status = $controller->getStatus();
            echo "Bot 狀態: " . $status['status'] . "\n";
            if ($status['pid']) echo "PID: " . $status['pid'] . "\n";
            if ($status['uptime']) echo "運行時間: " . $status['uptime'] . " 分鐘\n";
            echo "消息數: " . ($status['message_count'] ?? 0) . "\n";
            echo "錯誤數: " . ($status['error_count'] ?? 0) . "\n";
            break;

        case 'logs':
            $logs = $controller->getLogs(20);
            foreach ($logs as $log) {
                echo "[" . date('Y-m-d H:i:s', $log['created_at']) . "] {$log['action']}: {$log['details']}\n";
            }
            break;

        default:
            echo "用法: php botctl.php {start|stop|restart|status|logs}\n";
    }
}
