<?php /* Smarty version 2.6.26, created on 2026-02-11 04:09:00
         compiled from menus.html */ ?>

<style>
.col-sm-3 .panel { margin-bottom: 20px; }
.col-sm-3 .panel-heading a { color: var(--neon-blue) !important; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
.col-sm-3 .panel-heading a:hover { color: var(--neon-green) !important; }
.col-sm-3 .panel-heading { float: right; width: 100%; }
.col-sm-3 .nav-stacked li { margin-bottom: 5px; }
.col-sm-3 .nav-stacked li a { color: var(--text-secondary); padding: 10px 15px; border-radius: 4px; transition: all 0.3s ease; display: block; }
.col-sm-3 .nav-stacked li a:hover { color: var(--neon-blue); background: rgba(0,212,255,0.05); }
</style>

<div class="col-sm-3">
	<div class="panel panel-default">
		<div class="panel-heading"><a href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php">我的项目</a><a style="float:right;color:var(--neon-blue)!important;" href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=project&act=create">+ 创建</a></div>
			<div class="panel-body">
      			<ul class="nav nav-stacked">
						<?php $_from = $this->_tpl_vars['projects']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
						<li><a href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=project&act=view&id=<?php echo $this->_tpl_vars['v']['id']; ?>
"><?php echo $this->_tpl_vars['v']['title']; ?>
</a></li>
						<?php endforeach; endif; unset($_from); ?>
						</ul>
	</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading"><a href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=module">我的模块</a><a style="float:right;color:var(--neon-blue)!important;" href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=module&act=create">+ 创建</a></div>
			<div class="panel-body">
      			<ul class="nav nav-stacked">
		<?php $_from = $this->_tpl_vars['modules']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
		<?php if ($this->_tpl_vars['v']['isOpen'] == 0 || ( $this->_tpl_vars['v']['isOpen'] == 1 && $this->_tpl_vars['v']['isAudit'] == 0 )): ?>
		<li><a href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=module&act=set&id=<?php echo $this->_tpl_vars['v']['id']; ?>
"><?php echo $this->_tpl_vars['v']['title']; ?>
</a></li>
		<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>
						</ul>
	</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading">公共模块</div>
			<div class="panel-body">
      			<ul class="nav nav-stacked">
		<?php $_from = $this->_tpl_vars['modules']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
		<?php if ($this->_tpl_vars['v']['isOpen'] == 1 && $this->_tpl_vars['v']['isAudit'] == 1): ?>
		<li><a href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=module&act=view&id=<?php echo $this->_tpl_vars['v']['id']; ?>
"><?php echo $this->_tpl_vars['v']['title']; ?>
</a></li>
		<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>
						</ul>
	</div>
	</div>

		<div class="panel panel-default">
		<div class="panel-heading">工具链接</div>
			<div class="panel-body">
      			<ul class="nav nav-stacked">
				<li><a href="https://xss.bossjy.ccwu.cc/admin/index.php" target="_blank">后台管理</a></li>
						</ul>
	</div>
	</div>
</div>