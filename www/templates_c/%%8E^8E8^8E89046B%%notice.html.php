<?php /* Smarty version 2.6.26, created on 2026-02-11 04:18:48
         compiled from notice.html */ ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $this->_tpl_vars['notice']['str']; ?>
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
body { background: var(--bg-dark); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
.alert-cyber { background: rgba(255,71,87,0.1); border: 1px solid rgba(255,71,87,0.3); color: var(--neon-red); padding: 20px 30px; border-radius: 8px; margin: 0; max-width: 500px; text-align: center; }
.alert-success { background: rgba(0,255,136,0.1); border-color: rgba(0,255,136,0.3); color: var(--neon-green); }
.alert-info { background: rgba(0,212,255,0.1); border-color: rgba(0,212,255,0.3); color: var(--neon-blue); }
</style>

</head>
<body>
<div class="container" style="padding-top: 0;">
	<div class="alert-cyber <?php if ($this->_tpl_vars['notice']['style'] == 'success'): ?>alert-success<?php elseif ($this->_tpl_vars['notice']['style'] == 'info'): ?>alert-info<?php endif; ?>">
		<p style="font-size: 14px; margin-bottom: 20px;"><?php echo $this->_tpl_vars['notice']['str']; ?>
</p>
		<?php if ($this->_tpl_vars['notice']['turnto']): ?>
		<p><a href="<?php echo $this->_tpl_vars['notice']['turnto']; ?>
" class="btn btn-default" data-turnto="<?php echo $this->_tpl_vars['notice']['turnto']; ?>
" style="background: transparent; border: 1px solid var(--neon-blue); color: var(--neon-blue); text-transform: uppercase; letter-spacing: 1px;">点击跳转</a></p>
		<script>

(function(){
	var turnto = document.querySelector('[data-turnto]');
	if (turnto) {
		var url = turnto.getAttribute('data-turnto');
		if (url) {
			setTimeout(function(){ location.href = url; }, 500);
		}
	}
})();

		</script>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['notice']['urltitle']): ?>
		<p><a href="<?php echo $this->_tpl_vars['notice']['turnto']; ?>
" class="btn btn-default" style="background: var(--neon-blue); color: var(--bg-dark); text-transform: uppercase; letter-spacing: 1px;"><?php echo $this->_tpl_vars['notice']['urltitle']; ?>
</a></p>
		<?php endif; ?>
	</div>
</div>
</body>
</html>