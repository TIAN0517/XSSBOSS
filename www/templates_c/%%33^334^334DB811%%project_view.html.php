<?php /* Smarty version 2.6.26, created on 2026-02-11 04:09:15
         compiled from project_view.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'project_view.html', 9, false),array('modifier', 'default', 'project_view.html', 9, false),array('modifier', 'date_format', 'project_view.html', 11, false),array('modifier', 'truncate', 'project_view.html', 35, false),)), $this); ?>
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
	.info-row { padding: 15px 0; border-bottom: 1px solid var(--border-color); margin-bottom: 10px; }
	.info-row:last-child { border-bottom: none; }
	.info-row strong { color: var(--neon-blue); text-transform: uppercase; letter-spacing: 1px; margin-right: 10px; }
	.info-row a { color: var(--neon-blue); }
	.info-row a:hover { color: var(--neon-green); }
	.btn-sm { padding: 8px 15px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; border-radius: 4px; margin-right: 8px; }
	.btn-primary { background: transparent; border: 1px solid var(--neon-blue); color: var(--neon-blue); }
	.btn-primary:hover { background: rgba(0,212,255,0.1); box-shadow: 0 0 15px rgba(0,212,255,0.3); }
	.btn-info { background: transparent; border: 1px solid var(--neon-purple); color: var(--neon-purple); }
	.btn-info:hover { background: rgba(168,85,247,0.1); box-shadow: 0 0 15px rgba(168,85,247,0.3); }
	.btn-danger { background: transparent; border: 1px solid var(--neon-red); color: var(--neon-red); }
	.btn-danger:hover { background: rgba(255,71,87,0.1); box-shadow: 0 0 15px rgba(255,71,87,0.3); }
	pre { background: rgba(0,0,0,0.4); border: 1px solid var(--border-color); padding: 15px; border-radius: 4px; overflow-x: auto; font-size: 12px; }
	.table { width: 100%; border-collapse: collapse; }
	.table th { background: rgba(168,85,247,0.1); color: var(--neon-purple); text-transform: uppercase; font-size: 12px; letter-spacing: 1px; padding: 12px; border-bottom: 1px solid var(--border-color); }
	.table td { padding: 12px; border-bottom: 1px solid var(--border-color); color: var(--text-primary); }
	.table tr:hover { background: rgba(0,212,255,0.05); }
	.pagination { margin-top: 20px; text-align: center; }
	.pagination a, .pagination span { display: inline-block; background: rgba(0,0,0,0.3); border: 1px solid var(--border-color); color: var(--text-secondary); padding: 8px 12px; margin: 0 3px; border-radius: 4px; text-decoration: none; transition: all 0.3s ease; }
	.pagination a:hover { border-color: var(--neon-blue); color: var(--neon-blue); }
	.pagination .current { background: rgba(0,212,255,0.2); border-color: var(--neon-blue); color: var(--neon-blue); }
	</style>
	
	<div class="panel panel-default">
		<div class="panel-heading">项目: <?php echo $this->_tpl_vars['project']['title']; ?>
</div>
		<div class="panel-body">
			<div class="info-row"><strong>描述:</strong> <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['project']['description'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')))) ? $this->_run_mod_handler('default', true, $_tmp, '...') : smarty_modifier_default($_tmp, '...')); ?>
</div>
			<div class="info-row"><strong>URL:</strong> <a href="<?php echo $this->_tpl_vars['url']['root']; ?>
/<?php echo $this->_tpl_vars['project']['urlKey']; ?>
" target="_blank"><?php echo $this->_tpl_vars['url']['root']; ?>
/<?php echo $this->_tpl_vars['project']['urlKey']; ?>
</a></div>
			<div class="info-row"><strong>创建时间:</strong> <?php echo ((is_array($_tmp=$this->_tpl_vars['project']['addTime'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
</div>
			<div style="margin-top: 20px;">
				<a href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=project&act=setcode&ty=create&id=<?php echo $this->_tpl_vars['project']['id']; ?>
" class="btn btn-primary btn-sm">配置项目</a>
				<a href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=project&act=viewcode&id=<?php echo $this->_tpl_vars['project']['id']; ?>
" class="btn btn-info btn-sm">获取代码</a>
				<a href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=project&act=delete&id=<?php echo $this->_tpl_vars['project']['id']; ?>
&token=<?php echo $this->_tpl_vars['show']['user']['token']; ?>
" class="btn btn-danger btn-sm" onclick="return confirm('确定删除吗?')">删除</a>
			</div>
		</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading">收到的 Cookie</div>
		<table class="table">
			<thead>
				<tr>
					<th width="150">时间</th>
					<th width="200">来源</th>
					<th width="130">IP</th>
					<th>Cookie</th>
				</tr>
			</thead>
			<tbody>
				<?php $_from = $this->_tpl_vars['contents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
				<tr>
					<td><?php echo ((is_array($_tmp=$this->_tpl_vars['v']['addTime'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
</td>
					<td><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['v']['referer'])) ? $this->_run_mod_handler('default', true, $_tmp, '直接访问') : smarty_modifier_default($_tmp, '直接访问')))) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')))) ? $this->_run_mod_handler('truncate', true, $_tmp, 30) : smarty_modifier_truncate($_tmp, 30)); ?>
</td>
					<td><?php echo ((is_array($_tmp=$this->_tpl_vars['v']['ip'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</td>
					<td><pre style="margin:0;background:transparent;border:none;"><?php echo ((is_array($_tmp=$this->_tpl_vars['v']['cookie'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</pre></td>
				</tr>
				<?php endforeach; endif; unset($_from); ?>
			</tbody>
		</table>
		<div class="pagination">
			<?php echo $this->_tpl_vars['pagers']; ?>

		</div>
	</div>
</div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>