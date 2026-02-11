<?php /* Smarty version 2.6.26, created on 2026-02-11 04:09:00
         compiled from header.html */ ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $this->_tpl_vars['show']['sitename']; ?>
 - <?php echo $this->_tpl_vars['show']['sitedesc']; ?>
</title>
<link rel="stylesheet" href="<?php echo $this->_tpl_vars['url']['themePath']; ?>
/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo $this->_tpl_vars['url']['themePath']; ?>
/css/cyber.css">
<script src="<?php echo $this->_tpl_vars['url']['themePath']; ?>
/js/jquery.min.js"></script>
<script src="<?php echo $this->_tpl_vars['url']['themePath']; ?>
/js/bootstrap.min.js"></script>

<style>
body { background: var(--bg-dark); color: var(--text-primary); padding-top: 70px; }
.footer { margin-top: 30px; padding: 20px 0; text-align: center; color: var(--text-muted); border-top: 1px solid var(--border-color); }
.wireframe-bg { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; background: linear-gradient(90deg, transparent 98%, rgba(0,212,255,0.1) 98%, transparent 98.5%), linear-gradient(0deg, transparent 98%, rgba(0,212,255,0.1) 98%, transparent 98.5%); background-size: 50px 50px; animation: scanline 10s linear infinite; }
.scanlines { position: fixed; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 9999; background: repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(0,0,0,0.1) 2px, rgba(0,0,0,0.1) 4px); }
.navbar-fixed-top { background: rgba(10,10,15,0.95); backdrop-filter: blur(10px); border-bottom: 1px solid var(--border-color); }
.navbar-brand { color: var(--neon-blue) !important; font-weight: bold; text-transform: uppercase; letter-spacing: 3px; text-shadow: 0 0 10px rgba(0,212,255,0.5); }
.nav > li > a { color: var(--text-secondary) !important; text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s ease; }
.nav > li > a:hover { color: var(--neon-blue) !important; }
.container { position: relative; z-index: 1; }
.panel { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; }
.panel-heading { background: rgba(0,212,255,0.1); border-bottom: 1px solid var(--border-color); color: var(--neon-blue); padding: 15px 20px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
.panel-body { padding: 20px; color: var(--text-primary); }
.well { background: rgba(0,0,0,0.3); border: 1px solid var(--border-color); border-radius: 4px; color: var(--text-primary); }
.btn-default { background: transparent; border: 1px solid var(--neon-blue); color: var(--neon-blue); text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s ease; }
.btn-default:hover { background: rgba(0,212,255,0.1); box-shadow: 0 0 20px rgba(0,212,255,0.3); color: var(--neon-blue); }
.form-control { background: rgba(0,0,0,0.3); border: 1px solid var(--border-color); color: var(--text-primary); padding: 14px 18px; border-radius: 4px; width: 100%; transition: all 0.3s ease; }
.form-control:focus { outline: none; border-color: var(--neon-blue); box-shadow: 0 0 20px rgba(0,212,255,0.3); }
.form-control::placeholder { color: var(--text-muted); }
table { width: 100%; border-collapse: collapse; }
th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid var(--border-color); }
th { color: var(--neon-purple); text-transform: uppercase; font-size: 12px; letter-spacing: 1px; }
tr:hover { background: rgba(0,212,255,0.05); }
.alert { background: rgba(255,71,87,0.1); border: 1px solid rgba(255,71,87,0.3); color: var(--neon-red); padding: 15px 20px; border-radius: 4px; }
.alert-success { background: rgba(0,255,136,0.1); border-color: rgba(0,255,136,0.3); color: var(--neon-green); }
a { color: var(--neon-blue); text-decoration: none; transition: all 0.3s ease; }
a:hover { color: var(--neon-green); text-shadow: 0 0 10px rgba(0,255,136,0.5); }
label { display: block; color: var(--text-secondary); font-size: 12px; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px; }
select.form-control { background: rgba(0,0,0,0.3); border: 1px solid var(--border-color); color: var(--text-primary); }
textarea.form-control { background: rgba(0,0,0,0.3); border: 1px solid var(--border-color); color: var(--text-primary); }
</style>

</head>
<body>
<div class="wireframe-bg"></div>
<div class="scanlines"></div>
<nav class="navbar navbar-fixed-top">
   <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar" style="background: var(--neon-blue);"></span>
            <span class="icon-bar" style="background: var(--neon-blue);"></span>
            <span class="icon-bar" style="background: var(--neon-blue);"></span>
          </button>
          <a class="navbar-brand" href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php">XSS Platform</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php">主页</a></li>
          </ul>
		  <?php if ($this->_tpl_vars['show']['user']['userId'] < 1): ?>
		  	<ul class="nav navbar-nav navbar-right">
                <li><a href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=login">登录</a></li>
                <li><a href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=register">注册</a></li>
            </ul>
			<?php endif; ?>
		  <?php if ($this->_tpl_vars['show']['user']['userId'] > 0): ?>
		  <ul class="nav navbar-nav navbar-right">
                <li><a href="#">用户：<?php echo $this->_tpl_vars['show']['user']['userName']; ?>
</a></li>
                <li><a href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=user&act=seting">个人设置</a></li>
                <li><a href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=user&act=ipurlseting">IP-URL黑名单</a></li>
				<?php if ($this->_tpl_vars['show']['user']['adminLevel'] > 0): ?>
				  <li><a href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=user&act=invite">邀请码</a></li>
				  <li><a href="<?php echo $this->_tpl_vars['url']['root']; ?>
/admin/index.php">超管后台</a></li>
				<?php endif; ?>
                <li><a href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=login&act=logout">退出</a></li>
            </ul>
			<?php endif; ?>
        </div>
   </div>
</nav>
<div class="container">