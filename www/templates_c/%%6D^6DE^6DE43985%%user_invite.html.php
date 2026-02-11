<?php /* Smarty version 2.6.26, created on 2026-02-11 16:58:31
         compiled from user_invite.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'user_invite.html', 8, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div class="container">
	
	<style>
	.table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
	.table th { background: rgba(0,212,255,0.1); color: var(--neon-blue); text-transform: uppercase; font-size: 12px; letter-spacing: 1px; padding: 12px 15px; border-bottom: 1px solid var(--border-color); text-align: left; }
	.table td { padding: 12px 15px; border-bottom: 1px solid var(--border-color); color: var(--text-primary); font-family: monospace; }
	.table tr:hover { background: rgba(0,212,255,0.05); }
	h3 { color: var(--text-primary); font-size: 16px; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 15px; }
	.badge { display: inline-block; padding: 4px 10px; border-radius: 3px; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; margin-right: 5px; }
	.badge-success { background: rgba(0,255,136,0.2); color: var(--neon-green); border: 1px solid rgba(0,255,136,0.3); }
	</style>
	
	<div class="col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading">邀请码生成</div>
			<div class="panel-body">
				<h3>乌云币奖品邀请码 (<?php echo count($this->_tpl_vars['codesWooyun']); ?>
) <a href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=user&act=createinvite&isWooyun=1" style="float:right; font-size:12px; color:var(--neon-blue);">+ 生成邀请码</a></h3>
				<table class="table">
					<thead>
						<tr>
							<th>邀请码 (生成时间倒序排列)</th>
						</tr>
					</thead>
					<tbody>
						<?php $_from = $this->_tpl_vars['codesWooyun']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
						<tr>
							<td><span class="badge badge-success"><?php echo $this->_tpl_vars['v']['code']; ?>
</span></td>
						</tr>
						<?php endforeach; endif; unset($_from); ?>
					</tbody>
				</table>

				<h3>其它邀请码 (<?php echo count($this->_tpl_vars['codesOther']); ?>
) <a href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=user&act=createinvite&isWooyun=0" style="float:right; font-size:12px; color:var(--neon-blue);">+ 生成邀请码</a></h3>
				<table class="table">
					<thead>
						<tr>
							<th>邀请码 (生成时间倒序排列)</th>
						</tr>
					</thead>
					<tbody>
						<?php $_from = $this->_tpl_vars['codesOther']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
						<tr>
							<td><span class="badge badge-success"><?php echo $this->_tpl_vars['v']['code']; ?>
</span></td>
						</tr>
						<?php endforeach; endif; unset($_from); ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>