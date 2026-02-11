<?php /* Smarty version 2.6.26, created on 2026-02-11 16:58:23
         compiled from project_create.html */ ?>
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
	.form-group { margin-bottom: 25px; }
	.form-group label { display: block; color: var(--text-secondary); font-size: 12px; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px; }
	textarea.form-control { min-height: 100px; resize: vertical; }
	.btn-primary { background: transparent; border: 1px solid var(--neon-blue); color: var(--neon-blue); text-transform: uppercase; letter-spacing: 2px; padding: 12px 30px; transition: all 0.3s ease; border-radius: 4px; }
	.btn-primary:hover { background: rgba(0,212,255,0.1); box-shadow: 0 0 20px rgba(0,212,255,0.3); }
	</style>
	
	<div class="panel panel-default">
		<div class="panel-heading">创建项目</div>
		<div class="panel-body">
			<form action="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=project&act=create_submit" method="post">
				<input type="hidden" name="token" value="<?php echo $this->_tpl_vars['show']['user']['token']; ?>
">
				<div class="form-group">
					<label>项目名称</label>
					<input type="text" name="title" class="form-control" placeholder="输入项目名称" required>
				</div>
				<div class="form-group">
					<label>项目描述</label>
					<textarea name="description" class="form-control" rows="3" placeholder="输入项目描述"></textarea>
				</div>
				<button type="submit" class="btn btn-primary">创建项目</button>
			</form>
		</div>
	</div>
</div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>