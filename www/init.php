<?php
/**
 * init.php 初始化信息
 * ----------------------------------------------------------------
 * OldCMS,site:http://www.oldcms.com
 */
define('IN_OLDCMS',true);
if(!defined('ROOT_PATH')) define('ROOT_PATH',dirname(__FILE__));

// 加載安全配置
include(ROOT_PATH.'/source/config.php');

// 调试模式
if(Config::get('DEBUG') == 'true') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    error_reporting(0);
}

// 从 .env 加载基础配置
$dbConfig = Config::getDB();
$urlRoot = Config::get('APP_URL', 'https://xss.bossjy.ccwu.cc');

// 旧版兼容变量
$config = [
    'debug' => Config::get('DEBUG', 'false'),
    'urlroot' => $urlRoot,
    'urlrewrite' => 0,
    'register' => 1,
    'mailauth' => 0,
    'filepath' => '',
    'fileprefix' => '',
    'template' => 'default',
    'expires' => 0,
    'tbPrefix' => 'oc_',
    'timezone' => 'Asia/Shanghai',
    'show' => [],
    'point' => []
];

// 数据库连接 - 使用 BlueDB
$GLOBALS['db'] = null;
function DBConnect() {
    if($GLOBALS['db'] === null) {
        $dbConfig = Config::getDB();
        include_once(ROOT_PATH.'/source/class/DB.class.php');
        $GLOBALS['db'] = BlueDB::DB('mysql');
        $GLOBALS['db']->Connect(
            $dbConfig['host'],
            $dbConfig['user'],
            $dbConfig['pass'],
            $dbConfig['name'],
            'utf8mb4',
            TABLE_PREFIX
        );
    }
    return $GLOBALS['db'];
}

// 定义常量
define('URL_ROOT', $urlRoot);
define('URL_REWRITE', 0);
define('REGISTER', 1);
define('MAIL_AUTH', 0);
define('FILE_PATH', '');
define('FILE_PREFIX', '');
define('TEMPLATE_PATH', ROOT_PATH.'/themes/default');
define('ADMIN_PATH', ROOT_PATH.'/admin');
define('EXPIRES', 0);
define('TABLE_PREFIX', 'oc_');

// 加载兼容层和函数
include(ROOT_PATH.'/mysql_compat.php');
include(ROOT_PATH.'/source/function.php');
include(ROOT_PATH.'/source/global.func.php');
include(ROOT_PATH.'/source/class/User.class.php');

// url设置
$url = [];
$url['root'] = $urlRoot;
$url['imagePath'] = '/image/';
$url['avatarPath'] = '/avatar/';
$url['fieldPath'] = '/field/';
$url['themePath'] = $urlRoot.'/themes/default';

$urlDoArray = ['login', 'register'];
$url['rewrite'] = 0;
foreach($urlDoArray as $value) {
    $url[$value] = $url['root'].'/xss.php?do='.$value;
}

// 用户初始化
$user = new User();
if($user->userId > 0) {
    $show['user'] = [
        'userId' => $user->userId,
        'userName' => $user->userName,
        'adminLevel' => $user->adminLevel,
        'token' => $user->token,
        'avatarImg' => $user->avatarImg,
        'avatarImg_s' => $user->avatarImg_s,
        'signature' => $user->signature
    ];
}

// 清理
unset($dbConfig);
