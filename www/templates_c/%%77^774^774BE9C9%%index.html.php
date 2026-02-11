<?php /* Smarty version 2.6.26, created on 2026-02-11 04:09:12
         compiled from index.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'index.html', 22, false),array('modifier', 'default', 'index.html', 22, false),array('modifier', 'date_format', 'index.html', 24, false),)), $this); ?>
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
	.panel { margin-bottom: 20px; }
	.panel-heading a { color: var(--neon-blue) !important; text-transform: uppercase; letter-spacing: 1px; }
	.panel-heading a:hover { color: var(--neon-green) !important; }
	.table { width: 100%; border-collapse: collapse; margin-bottom: 0; }
	.table th { background: rgba(0,212,255,0.1); color: var(--neon-purple); text-transform: uppercase; font-size: 12px; letter-spacing: 1px; padding: 15px; border-bottom: 1px solid var(--border-color); }
	.table td { padding: 12px 15px; border-bottom: 1px solid var(--border-color); color: var(--text-primary); }
	.table tr:hover { background: rgba(0,212,255,0.05); }
	.table a { color: var(--neon-blue); transition: all 0.3s ease; }
	.table a:hover { color: var(--neon-green); text-shadow: 0 0 10px rgba(0,255,136,0.5); }
	.pagination { margin: 20px 0 0 0; }
	.pagination a, .pagination span { background: rgba(0,0,0,0.3); border: 1px solid var(--border-color); color: var(--text-secondary); padding: 8px 12px; margin: 0 3px; border-radius: 4px; text-decoration: none; transition: all 0.3s ease; }
	.pagination a:hover { border-color: var(--neon-blue); color: var(--neon-blue); }
	.pagination .current { background: rgba(0,212,255,0.2); border-color: var(--neon-blue); color: var(--neon-blue); }
	</style>
	
	<div class="panel panel-default">
		<div class="panel-heading">我的项目 <a href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=project&act=create" style="float:right;color:var(--neon-blue)!important;">+ 创建项目</a></div>
	<table class="table">
		<thead>
			<tr>
				<th width="200">项目名称</th>
				<th>项目描述</th>
				<th width="80">内容数</th>
				<th width="100">创建时间</th>
				<th width="60">操作</th>
			</tr>
		</thead>
		<tbody>
			<?php $_from = $this->_tpl_vars['projects']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
			<tr>
				<td><a href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=project&act=view&id=<?php echo $this->_tpl_vars['v']['id']; ?>
"><?php echo $this->_tpl_vars['v']['title']; ?>
</a></td>
				<td><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['v']['description'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')))) ? $this->_run_mod_handler('default', true, $_tmp, '...') : smarty_modifier_default($_tmp, '...')); ?>
</td>
				<td><?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['contentNum'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
				<td><?php echo ((is_array($_tmp=$this->_tpl_vars['v']['addTime'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d') : smarty_modifier_date_format($_tmp, '%Y-%m-%d')); ?>
</td>
				<td>
				<a href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=project&act=delete&id=<?php echo $this->_tpl_vars['v']['id']; ?>
&token=<?php echo $this->_tpl_vars['show']['user']['token']; ?>
" onclick="return confirm('确定删除吗?');" style="color: var(--neon-red);">删除</a>
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