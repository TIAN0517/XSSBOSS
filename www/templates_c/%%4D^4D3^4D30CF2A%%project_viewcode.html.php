<?php /* Smarty version 2.6.26, created on 2026-02-11 04:09:18
         compiled from project_viewcode.html */ ?>
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
.code-popup {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 90%;
    max-width: 600px;
    max-height: 80vh;
    overflow-y: auto;
    background: rgba(20, 20, 35, 0.95);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(0, 212, 255, 0.3);
    border-radius: 12px;
    z-index: 9999;
    box-shadow: 0 0 50px rgba(0, 212, 255, 0.3);
}
.code-popup .panel-heading {
    background: rgba(0, 212, 255, 0.1);
    border-bottom: 1px solid rgba(0, 212, 255, 0.2);
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.code-popup .panel-body {
    padding: 20px;
}
.code-popup .code-display {
    background: rgba(0, 0, 0, 0.5);
    border: 1px solid var(--border-color);
    border-radius: 6px;
    padding: 15px;
    margin: 15px 0;
}
.code-popup code {
    display: block;
    word-break: break-all;
    color: var(--neon-green);
    font-family: 'Consolas', 'Monaco', monospace;
    font-size: 13px;
    line-height: 1.6;
}
.close-btn {
    background: transparent;
    border: 1px solid var(--neon-blue);
    color: var(--neon-blue);
    padding: 8px 20px;
    border-radius: 4px;
    cursor: pointer;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 12px;
    transition: all 0.3s ease;
}
.close-btn:hover {
    background: rgba(0, 212, 255, 0.1);
    box-shadow: 0 0 15px rgba(0, 212, 255, 0.3);
}
.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    z-index: 9998;
}
</style>

	<div class="panel code-popup">
		<div class="panel-heading">
			<span style="color: var(--neon-blue); text-transform: uppercase; letter-spacing: 1px;"><?php echo $this->_tpl_vars['project']['title']; ?>
 - 代碼</span>
			<a href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=project&act=view&id=<?php echo $this->_tpl_vars['project']['id']; ?>
" class="close-btn">關閉</a>
		</div>
		<div class="panel-body">
			<p><strong style="color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px; font-size: 12px;">URL:</strong>
			<a href="<?php echo $this->_tpl_vars['url']['root']; ?>
/<?php echo $this->_tpl_vars['project']['urlKey']; ?>
" target="_blank" style="color: var(--neon-green);"><?php echo $this->_tpl_vars['url']['root']; ?>
/<?php echo $this->_tpl_vars['project']['urlKey']; ?>
</a></p>

			<p><strong style="color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px; font-size: 12px;">代碼:</strong></p>
			<div class="code-display">
				<code>&lt;script src="<?php echo $this->_tpl_vars['url']['root']; ?>
/<?php echo $this->_tpl_vars['project']['urlKey']; ?>
"&gt;&lt;/script&gt;</code>
			</div>

			<p style="margin-top: 15px;"><strong style="color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px; font-size: 12px;">短網址:</strong>
			<span style="color: var(--text-primary);"><?php echo $this->_tpl_vars['url']['root']; ?>
/<?php echo $this->_tpl_vars['project']['urlKey']; ?>
</span></p>

			<div style="margin-top: 20px; text-align: center;">
				<a href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=project&act=view&id=<?php echo $this->_tpl_vars['project']['id']; ?>
" class="close-btn">返回項目</a>
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