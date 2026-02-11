<?php
/**
 * Telegram Bot Daemon - XSS Platform
 * 守護進程模式運行
 * 特性：文件鎖、數據庫持久化、健康檢查
 */

define('ROOT_PATH', dirname(__FILE__));
define('BOT_LOCK_FILE', ROOT_PATH . '/data/bot.lock');
define('BOT_PID_FILE', ROOT_PATH . '/data/bot.pid');
define('BOT_LOG_FILE', ROOT_PATH . '/logs/bot.log');
define('RUN_INTERVAL', 3); // 秒

// 確保目錄存在
if (!is_dir(ROOT_PATH . '/data')) mkdir(ROOT_PATH . '/data', 0755, true);
if (!is_dir(ROOT_PATH . '/logs')) mkdir(ROOT_PATH . '/logs', 0755, true);

class BotDaemon {
    private $token;
    private $apiUrl = 'https://api.telegram.org/bot';
    private $adminChatId;
    private $usdtAddress;
    private $running = false;
    private $db;
    private $offset = 0;

    // 套餐配置
    private $plans = [
        'vip' => ['name' => 'VIP會員', 'price' => 30, 'projects' => 50, 'cookies' => 0],
    ];

    function __construct() {
        // 加載配置
        $tgConfig = $this->loadConfig('telegram');
        $tronConfig = $this->loadConfig('tron');

        $this->token = $tgConfig['token'] ?? '';
        $this->adminChatId = $tgConfig['admin_id'] ?? '';
        $this->usdtAddress = $tronConfig['address'] ?? '';

        $this->db = $this->connectDB();
    }

    private function loadConfig($type) {
        $config = [];
        $envFile = ROOT_PATH . '/.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) continue;
                if (strpos($line, '=') !== false) {
                    list($key, $value) = explode('=', $line, 2);
                    $key = trim($key);
                    if (strpos($key, strtoupper($type)) === 0) {
                        $config[strtolower(str_replace(strtoupper($type) . '_', '', $key))] = trim($value);
                    }
                    if ($type == 'telegram' && strpos($key, 'TELEGRAM') === 0) {
                        $config[strtolower(str_replace('TELEGRAM_', '', $key))] = trim($value);
                    }
                }
            }
        }
        return $config;
    }

    private function connectDB() {
        $envFile = ROOT_PATH . '/.env';
        $config = [];
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) continue;
                if (strpos($line, '=') !== false) {
                    list($key, $value) = explode('=', $line, 2);
                    $config[trim($key)] = trim($value);
                }
            }
        }

        $host = $config['DB_HOST'] ?? 'localhost';
        $port = $config['DB_PORT'] ?? 3306;
        $user = $config['DB_USER'] ?? 'root';
        $pass = $config['DB_PASS'] ?? '';
        $name = $config['DB_NAME'] ?? 'xss_platform';

        $link = mysqli_connect($host, $user, $pass, $name, $port);
        if (!$link) {
            $this->log("數據庫連接失敗: " . mysqli_connect_error());
            return null;
        }
        mysqli_set_charset($link, 'utf8mb4');
        return $link;
    }

    private function tb($name) {
        $envFile = ROOT_PATH . '/.env';
        $prefix = 'oc_';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos($line, 'DB_PREFIX') === 0) {
                    $prefix = trim(explode('=', $line, 2)[1]) . '_';
                    break;
                }
            }
        }
        return $prefix . $name;
    }

    // ==================== 鎖機制 ====================

    public function acquireLock() {
        $pid = getmypid();

        // 檢查進程是否存在
        if (file_exists(BOT_PID_FILE)) {
            $oldPid = (int)file_get_contents(BOT_PID_FILE);
            if ($oldPid > 0 && $this->processExists($oldPid)) {
                $this->log("Bot 已在運行中 (PID: $oldPid)");
                return false;
            }
        }

        // 嘗試獲取文件鎖
        $lockFile = fopen(BOT_LOCK_FILE, 'w');
        if (!$lockFile || !flock($lockFile, LOCK_EX | LOCK_NB)) {
            $this->log("無法獲取文件鎖");
            return false;
        }

        // 寫入 PID
        file_put_contents(BOT_PID_FILE, $pid);
        $this->log("獲取鎖成功 (PID: $pid)");

        // 更新數據庫狀態
        $this->updateStatus('running', $pid);

        return true;
    }

    public function releaseLock() {
        $this->updateStatus('stopped', null);
        if (file_exists(BOT_PID_FILE)) {
            unlink(BOT_PID_FILE);
        }
        if (file_exists(BOT_LOCK_FILE)) {
            flock(fopen(BOT_LOCK_FILE, 'r'), LOCK_UN);
        }
        $this->log("釋放鎖");
    }

    private function processExists($pid) {
        return posix_kill($pid, 0) || (PHP_OS !== 'WINNT' && file_exists("/proc/$pid"));
    }

    // ==================== 狀態管理 ====================

    private function updateStatus($status, $pid = null) {
        if (!$this->db) return;
        $stmt = mysqli_prepare($this->db, "UPDATE " . $this->tb('bot_status') . " SET status=?, pid=?, start_time=IF(?='running', UNIX_TIMESTAMP(), NULL), last_update=?");
        $now = time();
        mysqli_stmt_bind_param($stmt, 'ssii', $status, $pid, $status, $now);
        mysqli_stmt_execute($stmt);
    }

    private function incrementMessages() {
        if (!$this->db) return;
        mysqli_query($this->db, "UPDATE " . $this->tb('bot_status') . " SET message_count = message_count + 1, last_update = " . time());
    }

    private function logError($error) {
        if (!$this->db) return;
        $error = mysqli_real_escape_string($this->db, $error);
        mysqli_query($this->db, "UPDATE " . $this->tb('bot_status') . " SET error_count = error_count + 1, last_error = CONCAT('" . date('Y-m-d H:i:s') . ": ', ?, CHAR(13)), last_update = " . time(), $error ?? 'Unknown error');
        $this->log("ERROR: $error");
    }

    private function log($msg) {
        $time = date('Y-m-d H:i:s');
        $pid = getmypid();
        $logLine = "[$time] [PID:$pid] $msg\n";
        file_put_contents(BOT_LOG_FILE, $logLine, FILE_APPEND);
    }

    private function logAction($action, $details = null, $userId = null) {
        if (!$this->db) return;
        $action = mysqli_real_escape_string($this->db, $action);
        $details = $details ? mysqli_real_escape_string($this->db, $details) : null;
        $now = time();
        mysqli_query($this->db, "INSERT INTO " . $this->tb('bot_logs') . " (action, details, user_id, created_at) VALUES ('$action', '$details', " . ($userId ?? 'NULL') . ", $now)");
    }

    // ==================== Bot API ====================

    function request($method, $data) {
        $url = $this->apiUrl . $this->token . '/' . $method;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $result = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            $this->logError("CURL Error: $error");
            return false;
        }
        return json_decode($result, true);
    }

    function sendCard($chatId, $title, $text, $buttons = null) {
        $data = [
            'chat_id' => $chatId,
            'text' => $this->formatCard($title, $text),
            'parse_mode' => 'HTML',
        ];
        if ($buttons) {
            $data['reply_markup'] = json_encode(['inline_keyboard' => $buttons]);
        }
        $result = $this->request('sendMessage', $data);
        if ($result && $result['ok']) {
            $this->incrementMessages();
        }
        return $result;
    }

    function formatCard($title, $content) {
        $card = "┌─────────────────────────────┐\n";
        $card .= "│ {$title}\n";
        $card .= "├─────────────────────────────┤\n";
        $card .= "│ {$content}\n";
        $card .= "└─────────────────────────────┘";
        return $card;
    }

    // ==================== 用戶操作 ====================

    function getUserByChatId($chatId) {
        $result = mysqli_query($this->db, "SELECT * FROM " . $this->tb('users') . " WHERE tg_chat_id = " . intval($chatId));
        return mysqli_fetch_assoc($result);
    }

    function getUserById($userId) {
        $result = mysqli_query($this->db, "SELECT * FROM " . $this->tb('user') . " WHERE id = " . intval($userId));
        return mysqli_fetch_assoc($result);
    }

    function bindUser($chatId, $userId) {
        $user = $this->getUserById($userId);
        if ($user) {
            mysqli_query($this->db, "UPDATE " . $this->tb('users') . " SET tg_chat_id = " . intval($chatId) . " WHERE id = " . intval($userId));
            $this->logAction('bind', "chatId=$chatId, userId=$userId");
            return true;
        }
        return false;
    }

    function getUserCookieStats($userId) {
        $projects = mysqli_query($this->db, "SELECT id, title FROM " . $this->tb('project') . " WHERE userId = " . intval($userId));
        $totalCookies = 0;
        $projectList = [];

        while ($p = mysqli_fetch_assoc($projects)) {
            $count = mysqli_query($this->db, "SELECT COUNT(*) FROM " . $this->tb('project_content') . " WHERE projectId = " . intval($p['id']));
            $count = mysqli_fetch_array($count)[0];
            $totalCookies += $count;
            $projectList[] = "• {$p['title']}: $count 個";
        }

        return [
            'total' => $totalCookies,
            'projects' => $projectList,
            'projectCount' => count($projectList)
        ];
    }

    function getUserSubscription($userId) {
        $result = mysqli_query($this->db, "SELECT * FROM " . $this->tb('user_subscriptions') . " WHERE userId = " . intval($userId));
        $sub = mysqli_fetch_assoc($result);

        if ($sub && $sub['expire_time'] > time()) {
            $plan = $this->plans[$sub['plan_key']] ?? ['name' => '未知'];
            return [
                'active' => true,
                'plan' => $plan['name'],
                'expire' => date('Y-m-d', $sub['expire_time']),
                'projects' => $sub['max_projects'],
                'cookies' => $sub['max_cookies_per_day']
            ];
        }
        return ['active' => false];
    }

    function verifyPayment($txHash, $amount) {
        $tronConfig = $this->loadConfig('tron');
        $apiUrl = $tronConfig['api_url'] ?? 'https://api.trongrid.io';
        $apiKey = $tronConfig['api_key'] ?? '';

        $url = "$apiUrl/v1/transactions/$txHash/info";
        $headers = [];
        if ($apiKey) $headers[] = "TRON-Pro-API-Key: $apiKey";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($ch);
        curl_close($ch);
        $tx = json_decode($response, true);

        if (!$tx || !isset($tx['confirmed'])) {
            return ['success' => false, 'msg' => '交易不存在或未確認'];
        }

        if ($tx['confirmed'] !== true) {
            return ['success' => false, 'msg' => '交易待確認中...'];
        }

        // 驗證金額和地址
        $received = false;
        if (isset($tx['transfers'])) {
            foreach ($tx['transfers'] as $transfer) {
                if ($transfer['to'] === $this->usdtAddress && $transfer['amount'] >= $amount * 1000000) {
                    $received = true;
                    break;
                }
            }
        }

        if (!$received) {
            return ['success' => false, 'msg' => '未收到款項或金額不足'];
        }

        // 匹配套餐
        $plan = null;
        foreach ($this->plans as $key => $p) {
            if ($p['price'] == $amount) {
                $plan = $key;
                break;
            }
        }

        if (!$plan) {
            return ['success' => false, 'msg' => '金額不匹配任何套餐'];
        }

        return ['success' => true, 'plan' => $plan, 'orderId' => 'ORD' . date('YmdHis') . rand(1000, 9999)];
    }

    function startPaymentVerify($chatId, $amount, $txHash) {
        $user = $this->getUserByChatId($chatId);
        if (!$user) {
            return "請先綁定帳號：輸入 /bind [用戶ID] 進行綁定";
        }

        $result = $this->verifyPayment($txHash, $amount);

        if ($result['success']) {
            $expireTime = time() + 2592000;
            $p = $this->plans[$result['plan']];

            // 檢查訂單是否存在
            $existing = mysqli_query($this->db, "SELECT id FROM " . $this->tb('payment_orders') . " WHERE txHash = '" . mysqli_real_escape_string($this->db, $txHash) . "'");
            if (mysqli_fetch_assoc($existing)) {
                return "該交易已處理過";
            }

            // 創建訂單
            mysqli_query($this->db, "INSERT INTO " . $this->tb('payment_orders') . "
                (orderId, userId, plan, amount, txHash, status, addTime, confirmTime)
                VALUES ('" . $result['orderId'] . "', " . $user['id'] . ", '" . $result['plan'] . "', $amount, '" . mysqli_real_escape_string($this->db, $txHash) . "', 'confirmed', " . time() . ", " . time() . ")");

            // 開通訂閱
            mysqli_query($this->db, "REPLACE INTO " . $this->tb('user_subscriptions') . "
                (userId, plan_key, max_projects, max_cookies_per_day, expire_time, created_at)
                VALUES (" . $user['id'] . ", '" . $result['plan'] . "', " . $p['projects'] . ", " . $p['cookies'] . ", $expireTime, " . time() . ")");

            mysqli_query($this->db, "UPDATE " . $this->tb('user') . " SET vip_level='" . $result['plan'] . "', vip_expire=$expireTime WHERE id=" . $user['id']);

            $this->logAction('payment', "userId={$user['id']}, amount=$amount, orderId={$result['orderId']}");

            return "充值成功！\n\n套餐：{$p['name']}\n訂單號：{$result['orderId']}\n到期時間：" . date('Y-m-d', $expireTime);
        }

        return $result['msg'];
    }

    function getPaymentButtons() {
        return [
            [['text' => "💰 充值 VIP (\$30/月)", 'callback_data' => "pay_vip"]],
            [['text' => "🔍 查詢充值", 'callback_data' => "query_pay"], ['text' => "📊 Cookie統計", 'callback_data' => "cookie_stats"]],
            [['text' => "💳 訂閱狀態", 'callback_data' => "sub_status"]]
        ];
    }

    // ==================== 消息處理 ====================

    function handleUpdate($update) {
        $this->log("收到更新: " . json_encode($update));

        // Callback Button
        if (isset($update['callback_query'])) {
            $chatId = $update['callback_query']['from']['id'];
            $data = $update['callback_query']['data'];
            $user = $this->getUserByChatId($chatId);

            $this->logAction('callback', "chatId=$chatId, data=$data", $user['id'] ?? null);

            if (strpos($data, 'pay_') === 0) {
                $planKey = str_replace('pay_', '', $data);
                $p = $this->plans[$planKey] ?? ['name' => 'VIP'];
                $this->sendCard($chatId, "💳 充值 {$p['name']}",
                    "金額：\${$p['price']} USDT\n\n轉帳地址：\n<code>{$this->usdtAddress}</code>\n\n轉帳後請輸入：\n/pay [交易Hash]",
                    [['text' => "🔙 返回", 'callback_data' => 'main_menu']]);
            } elseif ($data === 'query_pay') {
                $this->sendCard($chatId, "🔍 查詢充值", "請輸入 /pay [txHash]", [['text' => "🔙 返回", 'callback_data' => 'main_menu']]);
            } elseif ($data === 'cookie_stats') {
                if (!$user) {
                    $this->sendCard($chatId, "❌ 未綁定", "請先輸入 /bind [用戶ID] 綁定帳號", [['text' => "🔙 返回", 'callback_data' => 'main_menu']]);
                } else {
                    $stats = $this->getUserCookieStats($user['id']);
                    $this->sendCard($chatId, "🍪 Cookie 統計",
                        "總計：{$stats['total']} 個\n項目數：{$stats['projectCount']}\n\n" . implode("\n", array_slice($stats['projects'], 0, 10)),
                        [['text' => "🔙 返回", 'callback_data' => 'main_menu']]);
                }
            } elseif ($data === 'sub_status') {
                if (!$user) {
                    $this->sendCard($chatId, "❌ 未綁定", "請先輸入 /bind [用戶ID] 綁定帳號", [['text' => "🔙 返回", 'callback_data' => 'main_menu']]);
                } else {
                    $sub = $this->getUserSubscription($user['id']);
                    if ($sub['active']) {
                        $this->sendCard($chatId, "💳 訂閱狀態",
                            "狀態：已激活\n套餐：{$sub['plan']}\n項目限額：{$sub['projects']}\nCookie限額：{$sub['cookies']}\n到期時間：{$sub['expire']}",
                            [['text' => "🔙 返回", 'callback_data' => 'main_menu']]);
                    } else {
                        $this->sendCard($chatId, "💳 訂閱狀態", "狀態：未開通\n\nVIP會員：\$30/月", [['text' => "🔙 返回", 'callback_data' => 'main_menu']]);
                    }
                }
            } elseif ($data === 'main_menu') {
                $this->sendCard($chatId, "🔰 XSS Platform", "歡迎使用！請選擇操作：", $this->getPaymentButtons());
            }
        }

        // 普通消息
        if (isset($update['message'])) {
            $chatId = $update['message']['from']['id'];
            $text = $update['message']['text'];

            $this->logAction('message', "chatId=$chatId, text=$text");

            if (strpos($text, '/start') === 0) {
                $this->sendCard($chatId, "🔰 XSS Platform Bot",
                    "歡迎使用！\n\n可用命令：\n• /bind [用戶ID] - 綁定帳號\n• /stats - Cookie統計\n• /sub - 訂閱狀態\n• /pay [txHash] - 充值驗證",
                    $this->getPaymentButtons());
            } elseif (strpos($text, '/bind') === 0) {
                $userId = trim(str_replace('/bind', '', $text));
                if ($this->bindUser($chatId, $userId)) {
                    $this->sendCard($chatId, "✅ 綁定成功", "用戶ID：$userId\n\n現在可以查詢Cookie和充值了！");
                } else {
                    $this->sendCard($chatId, "❌ 綁定失敗", "用戶ID不存在");
                }
            } elseif (strpos($text, '/stats') === 0) {
                $user = $this->getUserByChatId($chatId);
                if (!$user) {
                    $this->sendCard($chatId, "❌ 未綁定", "請先輸入 /bind [用戶ID] 綁定帳號");
                } else {
                    $stats = $this->getUserCookieStats($user['id']);
                    $this->sendCard($chatId, "🍪 Cookie 統計",
                        "總計：{$stats['total']} 個\n項目數：{$stats['projectCount']}\n\n" . implode("\n", array_slice($stats['projects'], 0, 10)));
                }
            } elseif (strpos($text, '/sub') === 0) {
                $user = $this->getUserByChatId($chatId);
                if (!$user) {
                    $this->sendCard($chatId, "❌ 未綁定", "請先輸入 /bind [用戶ID] 綁定帳號");
                } else {
                    $sub = $this->getUserSubscription($user['id']);
                    if ($sub['active']) {
                        $this->sendCard($chatId, "💳 訂閱狀態",
                            "狀態：已激活\n套餐：{$sub['plan']}\n到期時間：{$sub['expire']}");
                    } else {
                        $this->sendCard($chatId, "💳 訂閱狀態", "狀態：未開通\n\n請輸入 /pay [txHash] 進行充值");
                    }
                }
            } elseif (strpos($text, '/pay') === 0) {
                $txHash = trim(str_replace('/pay', '', $text));
                if (strlen($txHash) < 10) {
                    $this->sendCard($chatId, "❌ 格式錯誤", "正確格式：\n/pay [交易Hash]");
                } else {
                    $result = $this->startPaymentVerify($chatId, 30, $txHash);
                    $this->sendCard($chatId, "💰 充值結果", $result);
                }
            } elseif (strpos($text, '/status') === 0 && $chatId == $this->adminChatId) {
                // 管理員命令：查看 Bot 狀態
                $status = mysqli_query($this->db, "SELECT * FROM " . $this->tb('bot_status') . " WHERE id=1");
                $status = mysqli_fetch_assoc($status);
                $this->sendCard($chatId, "🔧 Bot 狀態",
                    "狀態：{$status['status']}\n消息數：{$status['message_count']}\n錯誤數：{$status['error_count']}\n運行時間：" . ($status['start_time'] ? floor((time() - $status['start_time']) / 60) . ' 分鐘' : 'N/A'));
            } elseif (strpos($text, '/stop') === 0 && $chatId == $this->adminChatId) {
                // 管理員命令：停止 Bot
                $this->sendCard($chatId, "🛑 停止 Bot", "正在停止守護進程...");
                $this->logAction('stop', '由管理員停止');
                $this->running = false;
            } else {
                $this->sendCard($chatId, "❓ 未知命令",
                    "可用命令：\n• /start - 開始使用\n• /bind [用戶ID] - 綁定帳號\n• /stats - Cookie統計\n• /sub - 訂閱狀態\n• /pay [txHash] - 充值驗證");
            }
        }
    }

    // ==================== 主循環 ====================

    function run() {
        if (!$this->token) {
            $this->log("錯誤：未配置 Telegram Token");
            return;
        }

        $this->log("Bot 啟動中...");

        // 獲取最新的 offset
        $offsetFile = ROOT_PATH . '/data/bot.offset';
        if (file_exists($offsetFile)) {
            $this->offset = (int)file_get_contents($offsetFile);
        }

        $this->running = true;
        $lastHealthCheck = time();

        while ($this->running) {
            // 健康檢查
            if (time() - $lastHealthCheck > 60) {
                $lastHealthCheck = time();
                // 檢查數據庫連接
                if (!mysqli_ping($this->db)) {
                    $this->db = $this->connectDB();
                    $this->log("重新連接數據庫");
                }
            }

            // 獲取更新
            $url = $this->apiUrl . $this->token . "/getUpdates?timeout=20&offset=" . ($this->offset + 1);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 25);
            $response = curl_exec($ch);
            curl_close($ch);

            $updates = json_decode($response, true);

            if ($updates && $updates['ok'] && !empty($updates['result'])) {
                foreach ($updates['result'] as $update) {
                    $this->handleUpdate($update);
                    $this->offset = $update['update_id'];
                }
                file_put_contents($offsetFile, $this->offset);
            }

            // 檢查是否需要停止
            if (file_exists(ROOT_PATH . '/data/bot.stop')) {
                $this->log("收到停止信號");
                unlink(ROOT_PATH . '/data/bot.stop');
                break;
            }
        }

        $this->releaseLock();
        $this->log("Bot 已停止");
    }
}

// 處理 Webhook 模式
if (php_sapi_name() !== 'cli') {
    $content = file_get_contents('php://input');
    $update = json_decode($content, true);
    if ($update) {
        $bot = new BotDaemon();
        $bot->handleUpdate($update);
    }
    exit;
}

// CLI 模式 - 守護進程
$daemon = new BotDaemon();

if (!$daemon->acquireLock()) {
    echo "Bot 已在運行中或無法獲取鎖\n";
    exit(1);
}

// 信號處理
pcntl_signal(SIGTERM, function() use ($daemon) {
    $daemon->log("收到 SIGTERM 信號");
    $daemon->releaseLock();
    exit;
});

pcntl_signal(SIGINT, function() use ($daemon) {
    $daemon->log("收到 SIGINT 信號");
    $daemon->releaseLock();
    exit;
});

// 開始運行
$daemon->run();
