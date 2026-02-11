<?php /* Smarty version 2.6.26, created on 2026-02-11 16:58:03
         compiled from module_view.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'module_view.html', 9, false),array('modifier', 'default', 'module_view.html', 9, false),array('modifier', 'date_format', 'module_view.html', 11, false),)), $this); ?>
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

	/* ===== Panel 樣式 ===== */
	.panel {
		background: var(--bg-card);
		border: 1px solid var(--border-color);
		border-radius: 12px;
		overflow: hidden;
	}
	.panel:hover {
		box-shadow: 0 6px 30px rgba(0,212,255,0.15), 0 0 2px rgba(0,212,255,0.2) inset;
		border-color: rgba(0,212,255,0.3);
	}
	.panel-heading {
		background: linear-gradient(135deg, rgba(0,212,255,0.15) 0%, rgba(0,0,0,0.2) 100%) !important;
		border-bottom: 1px solid var(--border-color);
		padding: 18px 25px;
		color: var(--neon-blue);
		font-weight: bold;
		text-transform: uppercase;
		letter-spacing: 1px;
		font-size: 15px;
	}
	.panel-body {
		padding: 25px;
		background: rgba(20,20,30,0.5);
	}

	/* ===== 資訊行 ===== */
	.info-row {
		padding: 15px 0;
		border-bottom: 1px solid rgba(0,212,255,0.1);
		margin-bottom: 5px;
	}
	.info-row:last-child { border-bottom: none; margin-bottom: 0; }
	.info-row strong {
		color: var(--neon-blue);
		text-transform: uppercase;
		letter-spacing: 1px;
		margin-right: 10px;
		min-width: 90px;
		display: inline-block;
		font-size: 12px;
	}
	.info-row span, .info-row div { color: var(--text-primary); }

	/* ===== 標籤樣式 ===== */
	.badge {
		display: inline-block;
		padding: 6px 14px;
		border-radius: 20px;
		font-size: 11px;
		text-transform: uppercase;
		letter-spacing: 1px;
		margin-right: 8px;
		font-weight: 600;
	}
	.badge-success {
		background: rgba(0,255,136,0.15);
		color: var(--neon-green);
		border: 1px solid rgba(0,255,136,0.3);
	}
	.badge-default {
		background: rgba(150,150,150,0.15);
		color: var(--text-secondary);
		border: 1px solid rgba(150,150,150,0.3);
	}
	.badge-warning {
		background: rgba(255,193,7,0.15);
		color: #ffc107;
		border: 1px solid rgba(255,193,7,0.3);
	}
	.badge-danger {
		background: rgba(255,71,87,0.15);
		color: var(--neon-red);
		border: 1px solid rgba(255,71,87,0.3);
	}

	/* ===== 代碼區域 ===== */
	.code-section {
		margin-top: 25px;
	}
	.code-section label {
		color: var(--neon-purple);
		text-transform: uppercase;
		letter-spacing: 1px;
		margin-bottom: 15px;
		display: block;
		font-size: 12px;
		font-weight: 600;
	}
	.code-block {
		background: rgba(0,0,0,0.5);
		border: 1px solid rgba(0,212,255,0.2);
		border-radius: 8px;
		padding: 20px;
		max-height: 300px;
		overflow: auto;
		word-wrap: break-word;
		word-break: break-all;
		white-space: pre-wrap;
		font-family: Consolas, "Courier New", monospace;
		font-size: 12px;
		line-height: 1.6;
		color: var(--neon-green);
	}
	.code-block::-webkit-scrollbar { width: 8px; height: 8px; }
	.code-block::-webkit-scrollbar-track { background: rgba(0,0,0,0.3); border-radius: 4px; }
	.code-block::-webkit-scrollbar-thumb { background: var(--neon-blue); border-radius: 4px; }

	/* ===== 按鈕 ===== */
	.btn-area { margin-top: 25px; display: flex; gap: 15px; flex-wrap: wrap; }
	.btn {
		padding: 12px 25px;
		border-radius: 6px;
		font-size: 12px;
		text-transform: uppercase;
		letter-spacing: 1px;
		font-weight: 600;
		transition: all 0.2s ease;
		cursor: pointer;
		text-decoration: none;
		display: inline-block;
	}
	.btn-primary {
		background: transparent;
		border: 1px solid var(--neon-blue);
		color: var(--neon-blue);
	}
	.btn-primary:hover {
		background: rgba(0,212,255,0.15);
		box-shadow: 0 0 20px rgba(0,212,255,0.4);
	}
	.btn-default {
		background: transparent;
		border: 1px solid var(--border-color);
		color: var(--text-secondary);
	}
	.btn-default:hover {
		border-color: var(--neon-blue);
		color: var(--neon-blue);
	}
	</style>
	
	<div class="panel">
		<div class="panel-heading">&#9889; <?php echo $this->_tpl_vars['module']['title']; ?>
</div>
		<div class="panel-body">
			<div class="info-row"><strong>描述:</strong> <span><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['module']['description'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')))) ? $this->_run_mod_handler('default', true, $_tmp, '...') : smarty_modifier_default($_tmp, '...')); ?>
</span></div>
			<div class="info-row"><strong>創建人:</strong> <span><?php echo ((is_array($_tmp=@$this->_tpl_vars['module']['userName'])) ? $this->_run_mod_handler('default', true, $_tmp, '系統') : smarty_modifier_default($_tmp, '系統')); ?>
</span></div>
			<div class="info-row"><strong>創建時間:</strong> <span><?php echo ((is_array($_tmp=$this->_tpl_vars['module']['addTime'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
</span></div>
			<div class="info-row"><strong>狀態:</strong>
				<div style="display: inline-block;">
					<?php if ($this->_tpl_vars['module']['isOpen'] == 1): ?>
						<span class="badge badge-success">公開</span>
					<?php else: ?>
						<span class="badge badge-default">私密</span>
					<?php endif; ?>
					<?php if ($this->_tpl_vars['module']['isAudit'] == 1): ?>
						<span class="badge badge-success">已審核</span>
					<?php elseif ($this->_tpl_vars['module']['isAudit'] == 2): ?>
						<span class="badge badge-danger">未通過</span>
					<?php else: ?>
						<span class="badge badge-warning">待審核</span>
					<?php endif; ?>
				</div>
			</div>

			<?php if ($this->_tpl_vars['module']['code']): ?>
			<div class="code-section">
				<label>&#128226; 模組代碼</label>
				<div class="code-block"><?php echo ((is_array($_tmp=$this->_tpl_vars['module']['code'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</div>
			</div>
			<?php endif; ?>

			<div class="btn-area">
				<a href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=module&act=set&id=<?php echo $this->_tpl_vars['module']['id']; ?>
" class="btn btn-primary">&#9881; 配置</a>
				<a href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=module&act=list" class="btn btn-default">&#128281; 返回列表</a>
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