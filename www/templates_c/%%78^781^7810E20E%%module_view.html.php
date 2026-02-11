<?php /* Smarty version 2.6.26, created on 2026-02-11 04:14:58
         compiled from module_view.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'module_view.html', 9, false),array('modifier', 'default', 'module_view.html', 9, false),array('modifier', 'date_format', 'module_view.html', 11, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div class="container">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "menus.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div class="col-sm-9">
	
	<style>
	.info-row { padding: 12px 0; border-bottom: 1px solid var(--border-color); margin-bottom: 5px; }
	.info-row:last-child { border-bottom: none; }
	.info-row strong { color: var(--neon-blue); text-transform: uppercase; letter-spacing: 1px; margin-right: 10px; min-width: 80px; display: inline-block; }
	pre { background: rgba(0,0,0,0.4); border: 1px solid var(--border-color); padding: 15px; border-radius: 4px; overflow-x: auto; font-size: 12px; color: var(--text-primary); }
	.btn-primary { background: transparent; border: 1px solid var(--neon-blue); color: var(--neon-blue); text-transform: uppercase; letter-spacing: 2px; padding: 12px 25px; border-radius: 4px; transition: all 0.3s ease; margin-right: 10px; }
	.btn-primary:hover { background: rgba(0,212,255,0.1); box-shadow: 0 0 20px rgba(0,212,255,0.3); }
	.badge { display: inline-block; padding: 4px 10px; border-radius: 3px; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; margin-right: 5px; }
	.badge-success { background: rgba(0,255,136,0.2); color: var(--neon-green); border: 1px solid rgba(0,255,136,0.3); }
	.badge-default { background: rgba(150,150,150,0.2); color: var(--text-secondary); border: 1px solid rgba(150,150,150,0.3); }
	.badge-warning { background: rgba(255,193,7,0.2); color: #ffc107; border: 1px solid rgba(255,193,7,0.3); }
	.badge-danger { background: rgba(255,71,87,0.2); color: var(--neon-red); border: 1px solid rgba(255,71,87,0.3); }
	</style>
	
	<div class="panel panel-default">
		<div class="panel-heading">模块: <?php echo $this->_tpl_vars['module']['title']; ?>
</div>
		<div class="panel-body">
			<div class="info-row"><strong>描述:</strong> <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['module']['description'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')))) ? $this->_run_mod_handler('default', true, $_tmp, '...') : smarty_modifier_default($_tmp, '...')); ?>
</div>
			<div class="info-row"><strong>创建人:</strong> <?php echo ((is_array($_tmp=@$this->_tpl_vars['module']['userName'])) ? $this->_run_mod_handler('default', true, $_tmp, '系统') : smarty_modifier_default($_tmp, '系统')); ?>
</div>
			<div class="info-row"><strong>创建时间:</strong> <?php echo ((is_array($_tmp=$this->_tpl_vars['module']['addTime'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
</div>
			<div class="info-row"><strong>状态:</strong>
				<?php if ($this->_tpl_vars['module']['isOpen'] == 1): ?>
					<span class="badge badge-success">公开</span>
				<?php else: ?>
					<span class="badge badge-default">私密</span>
				<?php endif; ?>
				<?php if ($this->_tpl_vars['module']['isAudit'] == 1): ?>
					<span class="badge badge-success">已审核</span>
				<?php elseif ($this->_tpl_vars['module']['isAudit'] == 2): ?>
					<span class="badge badge-danger">未通过</span>
				<?php else: ?>
					<span class="badge badge-warning">待审核</span>
				<?php endif; ?>
			</div>

			<?php if ($this->_tpl_vars['module']['code']): ?>
			<div style="margin-top: 20px;">
				<label style="color: var(--neon-purple); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px; display: block;">模块代码</label>
				<pre><?php echo ((is_array($_tmp=$this->_tpl_vars['module']['code'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</pre>
			</div>
			<?php endif; ?>

			<div style="margin-top: 20px;">
				<a href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=module&act=set&id=<?php echo $this->_tpl_vars['module']['id']; ?>
" class="btn btn-primary">配置</a>
				<a href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=module&act=list" class="btn btn-default" style="background: transparent; border: 1px solid var(--border-color); color: var(--text-secondary); text-transform: uppercase; letter-spacing: 2px; padding: 12px 25px; border-radius: 4px; transition: all 0.3s ease;">返回列表</a>
			</div>
		</div>
	</div>
</div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>