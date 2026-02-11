<?php /* Smarty version 2.6.26, created on 2026-02-11 16:58:43
         compiled from module.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'module.html', 22, false),array('modifier', 'default', 'module.html', 22, false),array('modifier', 'date_format', 'module.html', 24, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div class="page-layout">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "menus.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div class="main-content">
	
	<style>
	.table { width: 100%; border-collapse: collapse; }
	.table th { background: rgba(168,85,247,0.1); color: var(--neon-purple); text-transform: uppercase; font-size: 12px; letter-spacing: 1px; padding: 12px; border-bottom: 1px solid var(--border-color); }
	.table td { padding: 12px; border-bottom: 1px solid var(--border-color); color: var(--text-primary); }
	.table tr:hover { background: rgba(0,212,255,0.05); }
	.table a { color: var(--neon-blue); transition: all 0.3s ease; }
	.table a:hover { color: var(--neon-green); }
	</style>
	
	<div class="panel panel-default">
		<div class="panel-heading">模块列表</div>
		<table class="table">
			<thead>
				<tr>
					<th width="200">模块名称</th>
					<th>模块描述</th>
					<th width="100">创建人</th>
					<th width="100">创建时间</th>
					<th width="80">操作</th>
				</tr>
			</thead>
			<tbody>
				<?php $_from = $this->_tpl_vars['modules']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
				<tr>
					<td><a href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=module&act=view&id=<?php echo $this->_tpl_vars['v']['id']; ?>
"><?php echo $this->_tpl_vars['v']['title']; ?>
</a></td>
					<td><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['v']['description'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')))) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
</td>
					<td><?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['userName'])) ? $this->_run_mod_handler('default', true, $_tmp, '系统') : smarty_modifier_default($_tmp, '系统')); ?>
</td>
					<td><?php echo ((is_array($_tmp=$this->_tpl_vars['v']['addTime'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d') : smarty_modifier_date_format($_tmp, '%Y-%m-%d')); ?>
</td>
					<td>
						<a href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=module&act=set&id=<?php echo $this->_tpl_vars['v']['id']; ?>
">配置</a>
						<?php if ($this->_tpl_vars['show']['user']['adminLevel'] > 0 || $this->_tpl_vars['v']['userId'] == $this->_tpl_vars['show']['user']['userId']): ?>
						<a href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=module&act=delete&id=<?php echo $this->_tpl_vars['v']['id']; ?>
&token=<?php echo $this->_tpl_vars['show']['user']['token']; ?>
" onclick="return confirm('确定删除吗?');" style="color: var(--neon-red); margin-left: 10px;">删除</a>
						<?php endif; ?>
					</td>
				</tr>
				<?php endforeach; endif; unset($_from); ?>
			</tbody>
		</table>
	</div>
</div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>