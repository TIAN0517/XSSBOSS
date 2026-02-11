<?php
/**
 * Standalone Login Page - Cyberpunk Style
 * 独立登录页面 - 科技暗黑风格
 */

define('ROOT_PATH', dirname(__FILE__));
define('IN_OLDCMS', true);

// 加载基础配置
include(ROOT_PATH . '/config.php');

// 初始化数据库
include(ROOT_PATH . '/mysql_compat.php');
include(ROOT_PATH . '/source/function.php');
include(ROOT_PATH . '/source/global.func.php');

// 连接数据库
$db = DBConnect();
$tbUser = $db->tbPrefix . 'user';
$tbSession = $db->tbPrefix . 'session';

// 处理登录提交
$notice = array('str' => '', 'style' => 'danger');

if (Val('act', 'REQUEST') === 'submit') {
    $username = Val('user', 'POST');
    $password = Val('pwd', 'POST');

    if (empty($username)) {
        $notice['str'] = '用户名不能为空';
    } elseif (empty($password)) {
        $notice['str'] = '密码不能为空';
    } else {
        // 查询用户 - 使用已有的数据库连接
        $username_escaped = mysqli_real_escape_string($db->linkId, $username);
        $loginSql = "SELECT id,adminLevel,userName,email,userPwd,validated FROM " . $tbUser . " WHERE 1=1";
        $loginSql .= strpos($username, '@') ? " AND email='{$username_escaped}'" : " AND userName='{$username_escaped}'";

        $row = $db->FirstRow($loginSql);

        if ($row && $row['userPwd'] == OCEncrypt($password)) {
            // 登录成功
            $expiryTime = 3600;
            $ocKey = OCEncrypt($row['id'] . '-' . $row['userName'] . '-' . $row['userPwd']);

            // 设置 Cookie
            setcookie('ocKey', $ocKey, time() + $expiryTime, '/');

            $token = OCEncrypt(substr($ocKey, 0, 5) . time());
            $data = serialize(array(
                'userId' => $row['id'],
                'adminLevel' => $row['adminLevel'],
                'userName' => $row['userName'],
                'email' => $row['email'],
                'avatarImg' => '',
                'avatarImg_s' => '',
                'signature' => ''
            ));

            // 保存会话
            $sqlValue = array(
                'userId' => $row['id'],
                'ocKey' => $ocKey,
                'token' => $token,
                'ip' => IP(),
                'data' => $data,
                'expires' => time() + $expiryTime,
                'updateTime' => time(),
                'addTime' => time()
            );

            $sessionExisted = $db->FirstValue("SELECT COUNT(*) FROM " . $tbSession . " WHERE ocKey='{$ocKey}'");
            if ($sessionExisted > 0) {
                $db->AutoExecute($tbSession, $sqlValue, 'UPDATE', " ocKey='{$ocKey}'");
            } else {
                $db->AutoExecute($tbSession, $sqlValue);
            }

            $db->Execute("UPDATE " . $tbUser . " SET loginTime='" . time() . "' where id=" . $row['id']);

            header('Location: xss.php');
            exit;
        } else {
            $notice['str'] = '登录失败,请检查用户名或密码';
        }
    }
}

// URL 配置
$urlRoot = $config['urlroot'];
$themePath = $urlRoot . '/themes/' . $config['template'];
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Jy Technical Team - Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/svg+xml" href="<?php echo $urlRoot; ?>/favicon.svg">
<link rel="stylesheet" href="<?php echo $themePath; ?>/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo $themePath; ?>/css/cyber.css">
<link rel="stylesheet" href="<?php echo $themePath; ?>/css/css.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
.login-wrapper {min-height:100vh;display:flex;align-items:center;justify-content:center;padding:40px 20px;background:#0a0a0f;position:relative;overflow:hidden;}
.login-bg{position:fixed;top:0;left:0;width:100%;height:100%;z-index:-1;background:linear-gradient(135deg,#0a0a0f 0%,#1a1a2e 100%);}
.login-bg::before{content:'';position:absolute;top:0;left:0;right:0;bottom:0;background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2300d4ff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");}
.login-box{width:100%;max-width:420px;position:relative;z-index:1;}
.login-header{text-align:center;margin-bottom:35px;}
.login-header h1{color:#00d4ff;font-size:28px;font-weight:300;text-transform:uppercase;letter-spacing:6px;text-shadow:0 0 20px rgba(0,212,255,0.5);margin-bottom:8px;}
.login-header p{color:#666;font-size:11px;text-transform:uppercase;letter-spacing:3px;}
.cyber-input{background:rgba(0,0,0,0.4);border:1px solid rgba(0,212,255,0.3);color:#e0e0e0;padding:14px 18px 14px 45px;font-size:14px;border-radius:4px;width:100%;transition:all 0.3s ease;}
.cyber-input:focus{outline:none;border-color:#00d4ff;box-shadow:0 0 20px rgba(0,212,255,0.3);background:rgba(0,0,0,0.6);}
.cyber-input::placeholder{color:#555;}
.input-icon{position:relative;}
.input-icon i{position:absolute;left:15px;top:50%;transform:translateY(-50%);color:#00d4ff;font-size:15px;}
.cyber-btn{width:100%;padding:14px;background:transparent;border:1px solid #00d4ff;color:#00d4ff;font-size:13px;text-transform:uppercase;letter-spacing:3px;cursor:pointer;border-radius:4px;transition:all 0.3s ease;margin-top:8px;}
.cyber-btn:hover{background:rgba(0,212,255,0.1);box-shadow:0 0 25px rgba(0,212,255,0.4);text-shadow:0 0 10px rgba(0,212,255,0.5);}
.login-footer{text-align:center;margin-top:25px;padding-top:20px;border-top:1px solid rgba(0,212,255,0.1);}
.login-footer a{color:#888;text-decoration:none;font-size:11px;text-transform:uppercase;letter-spacing:1px;transition:all 0.3s ease;margin:0 12px;}
.login-footer a:hover{color:#00d4ff;}
.alert-cyber{background:rgba(255,71,87,0.1);border:1px solid rgba(255,71,87,0.3);color:#ff4757;padding:12px 15px;border-radius:4px;margin-bottom:20px;font-size:12px;text-align:center;}
.alert-success{background:rgba(0,255,136,0.1);border-color:rgba(0,255,136,0.3);color:#00ff88;}
.login-info{text-align:center;margin-top:20px;font-size:10px;color:#444;line-height:1.6;}
.login-info span{color:#666;}
@media(max-width:480px){.login-header h1{font-size:22px;letter-spacing:3px;}}
</style>
</head>
<body>
<div class="login-wrapper">
    <div class="login-bg"></div>
    <div class="login-box glass-card" style="background:rgba(20,20,35,0.8);backdrop-filter:blur(10px);border:1px solid rgba(0,212,255,0.2);border-radius:12px;padding:35px;">
        <div class="login-header">
            <h1>Jy Technical Team</h1>
            <p>Secure Authentication</p>
        </div>
        <?php if ($notice['str']): ?>
        <div class="alert-cyber"><?php echo htmlspecialchars($notice['str']); ?></div>
        <?php endif; ?>
        <form action="login.php?act=submit" method="post" onsubmit="return Login()">
            <div class="form-group" style="margin-bottom:20px;">
                <div class="input-icon">
                    <i class="fas fa-user"></i>
                    <input class="cyber-input" type="text" placeholder="用户名 / 邮箱" name="user" id="user" value="admin" required>
                </div>
            </div>
            <div class="form-group" style="margin-bottom:20px;">
                <div class="input-icon">
                    <i class="fas fa-lock"></i>
                    <input class="cyber-input" type="password" placeholder="密码" name="pwd" id="pwd" required>
                </div>
            </div>
            <button class="cyber-btn" type="submit"><i class="fas fa-sign-in-alt" style="margin-right:8px;"></i>登 陆</button>
        </form>
        <div class="login-footer">
            <a href="#" onclick="alert('註冊功能暫時關閉');return false;"><i class="fas fa-user-plus"></i> 註冊</a>
            <a href="#" onclick="alert('忘記密碼功能暫時關閉');return false;"><i class="fas fa-key"></i> 忘記密碼</a>
        </div>
        <div class="login-info">
            <span>System:</span> v2.0 | <span>Security:</span> Active<br>
            <span>© 2026 Jy Technical Team</span>
        </div>
    </div>
</div>
<script>
function Login(){
    if(document.getElementById("user").value==""){
        alert("用户名不能为空");return false;
    }
    if(document.getElementById("pwd").value==""){
        alert("密码不能为空");return false;
    }
    return true;
}
</script>
</body>
</html>
