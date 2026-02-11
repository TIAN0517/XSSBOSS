<?php /* Smarty version 2.6.26, created on 2026-02-11 16:58:00
         compiled from index.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'index.html', 35, false),array('modifier', 'default', 'index.html', 35, false),array('modifier', 'date_format', 'index.html', 37, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<!-- 頁面主容器 -->
<div class="page-layout">
	<!-- 左側模組欄 -->
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "menus.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<!-- 右側主內容 -->
	<div class="main-content">
		
		<style>
		/* ===== 主內容區 ===== */
		.main-content {
			justify-self: stretch;
			align-self: start;
			min-width: 0;
		}

		.panel {
			margin-bottom: 20px;
			background: var(--bg-card);
			border: 1px solid var(--border-color);
			border-radius: 12px;
			box-shadow: 0 4px 20px rgba(0,0,0,0.3), 0 0 1px rgba(0,212,255,0.1) inset;
			overflow: hidden;
		}
		.panel:hover {
			box-shadow: 0 6px 30px rgba(0,212,255,0.15), 0 0 2px rgba(0,212,255,0.2) inset;
			border-color: rgba(0,212,255,0.3);
		}
		.panel-heading {
			background: linear-gradient(135deg, rgba(0,212,255,0.15) 0%, rgba(0,0,0,0.3) 100%) !important;
			border-bottom: 1px solid var(--border-color);
			padding: 18px 25px;
		}
		.panel-heading a { color: var(--neon-blue) !important; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; transition: color 0.2s ease, text-shadow 0.2s ease; }
		.panel-heading a:hover { color: var(--neon-green) !important; text-shadow: 0 0 10px rgba(0,255,136,0.5); }
		.panel-body { padding: 0; }
		.table { width: 100%; border-collapse: collapse; margin-bottom: 0; background: rgba(20,20,30,0.9); }
		.table th {
			background: rgba(168,85,247,0.15);
			color: var(--neon-purple);
			text-transform: uppercase;
			font-size: 12px;
			letter-spacing: 1px;
			padding: 20px 25px;
			border-bottom: 1px solid rgba(0,212,255,0.2);
			font-weight: 600;
		}
		.table td {
			padding: 20px 25px;
			border-bottom: 1px solid rgba(0,212,255,0.1);
			color: var(--text-primary);
			vertical-align: middle;
			background: rgba(20,20,30,0.5);
		}
		.table tr { transition: background 0.2s ease; }
		.table tr:hover { background: rgba(0,212,255,0.08); }
		.table tr:last-child td { border-bottom: none; }
		.table a { color: var(--neon-blue); transition: color 0.2s ease, text-shadow 0.2s ease; position: relative; }
		.table a:hover { color: var(--neon-green); text-shadow: 0 0 8px rgba(0,255,136,0.6); }
		.content-num {
			display: inline-block;
			background: rgba(0,212,255,0.1);
			color: var(--neon-blue);
			padding: 8px 16px;
			border-radius: 20px;
			font-size: 13px;
			font-weight: 600;
			transition: background 0.2s ease;
		}
		.content-num:hover { background: rgba(0,212,255,0.2); }
		.btn-delete {
			color: var(--neon-red) !important;
			padding: 10px 20px;
			border-radius: 6px;
			border: 1px solid rgba(255,71,87,0.3);
			transition: background 0.2s ease, box-shadow 0.2s ease;
			font-size: 12px;
		}
		.btn-delete:hover {
			background: rgba(255,71,87,0.15);
			border-color: var(--neon-red);
			box-shadow: 0 0 10px rgba(255,71,87,0.3);
		}
		.empty-state { text-align: center; padding: 60px 20px; color: var(--text-muted); }
		.empty-state .icon { font-size: 48px; margin-bottom: 15px; opacity: 0.5; }
		.pagination { margin: 20px 0 0 0; padding: 0 20px 20px; }
		.pagination a, .pagination span { display: inline-block; background: rgba(0,0,0,0.3); border: 1px solid var(--border-color); color: var(--text-secondary); padding: 8px 14px; margin: 0 4px; border-radius: 6px; text-decoration: none; transition: background 0.2s ease, border-color 0.2s ease, color 0.2s ease; }
		.pagination a:hover { border-color: var(--neon-blue); color: var(--neon-blue); background: rgba(0,212,255,0.1); }
		.pagination .current { background: rgba(0,212,255,0.2); border-color: var(--neon-blue); color: var(--neon-blue); box-shadow: 0 0 10px rgba(0,212,255,0.2); }
		</style>
		
		<div class="panel">
			<div class="panel-heading">
				<span>&#9889; 我的專案</span>
				<a href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=project&act=create" style="float:right;">+ 創建專案</a>
			</div>
			<div class="panel-body">
			<?php if (empty ( $this->_tpl_vars['projects'] )): ?>
				<div class="empty-state">
					<div class="icon">&#128279;</div>
					<p>暫無專案，點擊上方按鈕創建第一個專案</p>
				</div>
			<?php else: ?>
			<table class="table">
				<thead>
					<tr>
						<th width="200">專案名稱</th>
						<th>專案描述</th>
						<th width="90">內容數</th>
						<th width="110">創建時間</th>
						<th width="70">操作</th>
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
						<td><span class="content-num"><?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['contentNum'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</span></td>
						<td><?php echo ((is_array($_tmp=$this->_tpl_vars['v']['addTime'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d') : smarty_modifier_date_format($_tmp, '%Y-%m-%d')); ?>
</td>
						<td>
						<a href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=project&act=delete&id=<?php echo $this->_tpl_vars['v']['id']; ?>
&token=<?php echo $this->_tpl_vars['show']['user']['token']; ?>
" onclick="return confirm('確定刪除嗎?');" class="btn-delete">刪除</a>
						</td>
					</tr>
					<?php endforeach; endif; unset($_from); ?>
				</tbody>
			</table>
			<?php endif; ?>
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