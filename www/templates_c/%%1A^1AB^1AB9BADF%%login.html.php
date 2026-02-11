<?php /* Smarty version 2.6.26, created on 2026-02-11 04:26:55
         compiled from login.html */ ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Jy Technical Team - Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="<?php echo $this->_tpl_vars['url']['themePath']; ?>
/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo $this->_tpl_vars['url']['themePath']; ?>
/css/cyber.css">
<link rel="stylesheet" href="<?php echo $this->_tpl_vars['url']['themePath']; ?>
/css/css.css">
<link rel="stylesheet" href="<?php echo $this->_tpl_vars['url']['themePath']; ?>
/css/login.css">
<link rel="stylesheet" href="<?php echo $this->_tpl_vars['url']['themePath']; ?>
/css/fontawesome.min.css">
</head>
<body>
<div class="login-wrapper">
    <div class="login-bg"></div>
    <div class="login-box glass-card" style="background:rgba(20,20,35,0.8);backdrop-filter:blur(10px);border:1px solid rgba(0,212,255,0.2);border-radius:12px;padding:35px;">
        <div class="login-header">
            <h1>Jy Technical Team</h1>
            <p>Secure Authentication</p>
        </div>
        <?php if ($this->_tpl_vars['notice']['str']): ?>
        <div class="alert-<?php echo $this->_tpl_vars['notice']['style']; ?>
"><?php echo $this->_tpl_vars['notice']['str']; ?>
</div>
        <?php endif; ?>
        <form action="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=login&act=submit" method="post" onsubmit="return Login()">
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
            <span>系统版本:</span> 2.0 | <span>安全模式:</span> 开启<br>
            <span>© 2026 XSS Platform</span>
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
<script src="<?php echo $this->_tpl_vars['url']['themePath']; ?>
/js/jquery.min.js"></script>
<script src="<?php echo $this->_tpl_vars['url']['themePath']; ?>
/js/bootstrap.min.js"></script>
</body>
</html>