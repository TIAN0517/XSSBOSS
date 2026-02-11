<?php
/**
 * USDT 支付處理
 */
define('ROOT_PATH', dirname(__FILE__).'/..');
include(ROOT_PATH.'/init.php');

// TRON API 配置
$tronConfig = [
    'apiUrl' => 'https://api.trongrid.io',
    'apiKey' => '', // 填入你的 TRONGrid API Key
    'receiveAddress' => 'TDejRjcLQa92rrE6SB71LSC7J5VmHs35gq',
];

// 套餐配置
$plans = [
    'vip' => ['price' => 30, 'name' => 'VIP會員', 'projects' => 50, 'cookies' => 0],
];

$act = Val('act', 'GET');

switch($act) {
    case 'create':
        // 創建訂單
        $plan = Val('plan', 'POST');
        if(!isset($plans[$plan])) ShowError('無效套餐');
        if($user->userId <= 0) ShowError('請先登入', URL_ROOT.'/xss.php?do=login');

        $orderId = 'ORD'.date('YmdHis').rand(1000,9999);
        $amount = $plans[$plan]['price'];

        $values = [
            'orderId' => $orderId,
            'userId' => $user->userId,
            'plan' => $plan,
            'amount' => $amount,
            'status' => 'pending',
            'txHash' => '',
            'addTime' => time()
        ];
        $db->AutoExecute(Tb('payment_orders'), $values);

        $smarty = InitSmarty();
        $smarty->assign('orderId', $orderId);
        $smarty->assign('amount', $amount);
        $smarty->assign('address', $tronConfig['receiveAddress']);
        $smarty->assign('planName', $plans[$plan]['name']);
        $smarty->display('payment_create.html');
        break;

    case 'check':
        // 查詢訂單狀態
        $orderId = Val('orderId', 'GET');
        $order = $db->FirstRow("SELECT * FROM ".Tb('payment_orders')." WHERE orderId='{$orderId}'");
        echo json_encode(['status' => $order['status']]);
        break;

    case 'webhook':
        // USDT Webhook（TRON API 調用）
        $txHash = Val('txHash', 'POST');
        // 驗證交易...
        break;
}

function checkTRONTransaction($txHash, $receiveAddress, $amount) {
    // TRON API 查詢交易
    $url = "https://api.trongrid.io/v1/transactions/{$txHash}/info";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}
