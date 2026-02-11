<?php /* Smarty version 2.6.26, created on 2026-02-11 16:58:00
         compiled from menus.html */ ?>

<style>
/* ===== 頁面主容器 ===== */
.page-layout {
	display: grid;
	grid-template-columns: auto 1fr;
	align-items: start;
	justify-items: start;
	width: 100%;
	min-width: 100%;
}

/* ===== 左側模組欄 ===== */
.sidebar {
	width: clamp(360px, 22vw, 420px);
	min-width: 360px;
	max-width: 420px;
	flex-shrink: 0;
	display: flex;
	flex-direction: column;
	align-items: stretch;
	justify-content: flex-start;
	gap: 14px;
	padding-top: 55px; /* 往下移 */
}

/* ===== 區塊通用樣式 ===== */
.sidebar-block {
	background: linear-gradient(145deg, rgba(25,25,35,0.95) 0%, rgba(18,18,28,0.9) 100%);
	border: 1px solid rgba(0,212,255,0.2);
	border-radius: 12px;
	box-shadow: 0 4px 20px rgba(0,0,0,0.3), 0 0 1px rgba(0,212,255,0.1) inset;
	overflow: hidden;
}
.sidebar-block:hover {
	box-shadow: 0 6px 30px rgba(0,212,255,0.15), 0 0 2px rgba(0,212,255,0.2) inset;
	border-color: rgba(0,212,255,0.3);
}

/* ===== 區塊標題 ===== */
.section-header {
	display: flex;
	align-items: center;
	justify-content: space-between;
	padding: 12px 15px;
	background: linear-gradient(135deg, rgba(0,212,255,0.12) 0%, rgba(0,0,0,0.2) 100%);
	border-bottom: 1px solid rgba(0,212,255,0.2);
}
.section-header h4 {
	margin: 15px 0 10px 0; /* 往下移動 15px */
	color: var(--neon-blue);
	font-size: 12px;
	font-weight: 600;
	text-transform: uppercase;
	letter-spacing: 1px;
}
.section-header a {
	color: var(--neon-blue);
	font-size: 11px;
	text-transform: uppercase;
	letter-spacing: 1px;
	transition: color 0.2s ease, text-shadow 0.2s ease;
}
.section-header a:hover {
	color: var(--neon-green);
	text-shadow: 0 0 8px rgba(0,255,136,0.5);
}

/* ===== 我的專案區塊 ===== */
.projects-block {
	flex-shrink: 0;
	max-height: 200px;
	overflow: hidden;
}
.projects-list {
	max-height: 168px;
	overflow-y: auto;
	padding: 5px 0;
}
.projects-list::-webkit-scrollbar { width: 4px; }
.projects-list::-webkit-scrollbar-track { background: rgba(0,0,0,0.2); }
.projects-list::-webkit-scrollbar-thumb { background: var(--neon-blue); border-radius: 2px; }
.project-item {
	display: flex;
	align-items: center;
	justify-content: flex-start; /* 改為靠左 */
	padding: 10px 15px;
	color: var(--text-secondary);
	font-size: 13px;
	text-decoration: none;
	border-left: 3px solid transparent;
	transition: background 0.2s ease, border-color 0.2s ease, color 0.2s ease;
	overflow: hidden;
	text-overflow: ellipsis;
	white-space: nowrap;
}
.project-item:hover {
	color: var(--neon-blue);
	background: rgba(0,212,255,0.06);
	border-left-color: var(--neon-blue);
}

/* ===== 我的模組區塊 ===== */
.my-modules-block {
	flex-shrink: 0;
	max-height: 150px;
	overflow: hidden;
}
.modules-list {
	max-height: 118px;
	overflow-y: auto;
	padding: 5px 0;
}
.modules-list::-webkit-scrollbar { width: 4px; }
.modules-list::-webkit-scrollbar-track { background: rgba(0,0,0,0.2); }
.modules-list::-webkit-scrollbar-thumb { background: var(--neon-purple); border-radius: 2px; }
.module-item {
	display: flex;
	align-items: center;
	justify-content: flex-start; /* 改為靠左 */
	padding: 10px 15px;
	color: var(--text-secondary);
	font-size: 13px;
	text-decoration: none;
	border-left: 3px solid transparent;
	transition: background 0.2s ease, border-color 0.2s ease, color 0.2s ease;
}
.module-item:hover {
	color: var(--neon-purple);
	background: rgba(168,85,247,0.06);
	border-left-color: var(--neon-purple);
}

/* ===== XSS 功能模組區塊 ===== */
.xss-modules-block {
	flex-shrink: 0;
	overflow: hidden;
}
.modules-grid {
	padding: 5px 0; /* 調整 padding */
	display: flex;
	flex-direction: column; /* 改為垂直列表 */
	gap: 0; /* 移除間距，使用 padding */
	max-height: calc(100vh - 420px);
	overflow-y: auto;
}
.modules-grid::-webkit-scrollbar { width: 4px; }
.modules-grid::-webkit-scrollbar-track { background: rgba(0,0,0,0.2); }
.modules-grid::-webkit-scrollbar-thumb { background: var(--neon-blue); border-radius: 2px; }

/* ===== XSS 模組卡片 ===== */
.xss-module-card {
	/* height: 56px; 移除固定高度 */
	/* min-height: 56px; */
	/* min-width: 150px; */
	width: 100%; /* 全寬 */
	padding: 10px 15px; /* 與其他項目一致 */
	box-sizing: border-box;
	display: flex;
	align-items: center;
	gap: 10px;
	background: transparent; /* 移除背景 */
	border: none; /* 移除邊框 */
	border-left: 3px solid transparent; /* 添加左側邊框 */
	/* border-radius: 8px; 移除圓角 */
	text-decoration: none;
	transition: background 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
	overflow: hidden;
}
.xss-module-card:hover {
	background: rgba(0,212,255,0.06); /* 與其他項目一致 */
	/* border-color: var(--neon-blue); */
	border-left-color: var(--neon-blue);
	box-shadow: none; /* 移除陰影 */
}
.xss-module-card .icon {
	font-size: 16px; /* 稍微縮小 */
	flex-shrink: 0;
	position: relative;
	z-index: 1;
	transition: text-shadow 0.2s ease;
	width: 20px; /* 固定寬度對齊 */
	text-align: center;
}
.xss-module-card:hover .icon {
	text-shadow: 0 0 15px rgba(0,212,255,0.8);
}
.xss-module-card .name {
	font-size: 13px; /* 與其他項目一致 */
	color: var(--text-secondary);
	line-height: 1.3;
	overflow: hidden;
	text-overflow: ellipsis;
	white-space: nowrap; /* 不換行 */
	display: block; /* 移除 -webkit-box */
	/* -webkit-line-clamp: 2; */
	/* -webkit-box-orient: vertical; */
	/* word-break: break-all; */
	position: relative;
	z-index: 1;
	transition: color 0.2s ease;
}
.xss-module-card:hover .name {
	color: var(--neon-blue);
}

/* ===== 快捷入口區塊 ===== */
.quick-links-block {
	flex-shrink: 0;
}
.quick-links-list {
	padding: 5px 0;
}
.quick-link {
	display: block;
	padding: 10px 15px;
	color: var(--text-secondary);
	font-size: 13px;
	text-decoration: none;
	border-left: 3px solid transparent;
	transition: background 0.2s ease, border-color 0.2s ease, color 0.2s ease;
}
.quick-link:hover {
	color: var(--neon-green);
	background: rgba(0,255,136,0.06);
	border-left-color: var(--neon-green);
}

/* ===== 右側主內容 ===== */
.main-content {
	min-width: 0;
	width: 100%;
}

/* ===== 響應式斷點 ===== */
@media (max-width: 1023px) {
	.sidebar {
		width: clamp(300px, 28vw, 360px);
		min-width: 300px;
		max-width: 360px;
	}
	/* 移除 .modules-grid 網格設定，保持列表樣式 */
	.modules-grid {
		/* grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); */
		/* gap: 10px; */
	}
	.xss-module-card {
		/* min-width: 140px; */
		/* height: 52px; */
		/* min-height: 52px; */
		padding: 8px 10px; /* 保持 padding 調整 */
	}
	.xss-module-card .icon {
		font-size: 16px;
	}
	.xss-module-card .name {
		font-size: 11px;
	}
}

@media (max-width: 767px) {
	.page-layout {
		grid-template-columns: 1fr;
	}
	.sidebar {
		width: 100%;
		min-width: 100%;
		max-width: 100%;
	}
	/* 移除 .modules-grid 網格設定，保持列表樣式 */
	.modules-grid {
		/* grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); */
		/* gap: 8px; */
	}
	.xss-module-card {
		/* min-width: 120px; */
		/* height: 48px; */
		/* min-height: 48px; */
		padding: 6px 8px; /* 保持 padding 調整 */
		gap: 8px;
	}
	.xss-module-card .icon {
		font-size: 14px;
	}
	.xss-module-card .name {
		font-size: 10px;
	}
}
</style>

<script>
// 自動計算 header 高度
(function() {
	function updateHeaderHeight() {
		var header = document.querySelector('.navbar-fixed-top');
		if (header) {
			var height = header.offsetHeight;
			document.documentElement.style.setProperty('--header-height', height + 'px');
		}
	}

	// 頁面載入時執行
	updateHeaderHeight();

	// 監聽視窗大小變化
	window.addEventListener('resize', updateHeaderHeight);

	// 圖片載入後重新計算
	window.addEventListener('load', updateHeaderHeight);
})();
</script>


<!-- 左側模組欄 -->
<div class="sidebar">

	<!-- 我的專案區塊 -->
	<div class="sidebar-block projects-block">
		<div class="section-header">
			<h4>&#9889; 我的專案</h4>
			<a href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=project&act=create">+ 創建</a>
		</div>
		<div class="projects-list">
			<?php $_from = $this->_tpl_vars['projects']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
			<a class="project-item" href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=project&act=view&id=<?php echo $this->_tpl_vars['v']['id']; ?>
"><?php echo $this->_tpl_vars['v']['title']; ?>
</a>
			<?php endforeach; endif; unset($_from); ?>
		</div>
	</div>

	<!-- 我的模組區塊 -->
	<div class="sidebar-block my-modules-block">
		<div class="section-header">
			<h4>&#9889; 我的模組</h4>
			<a href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=module&act=create">+ 創建</a>
		</div>
		<div class="modules-list">
			<?php $_from = $this->_tpl_vars['modules']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
			<?php if ($this->_tpl_vars['v']['isOpen'] == 0 || ( $this->_tpl_vars['v']['isOpen'] == 1 && $this->_tpl_vars['v']['isAudit'] == 0 )): ?>
			<a class="module-item" href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=module&act=set&id=<?php echo $this->_tpl_vars['v']['id']; ?>
"><?php echo $this->_tpl_vars['v']['title']; ?>
</a>
			<?php endif; ?>
			<?php endforeach; endif; unset($_from); ?>
		</div>
	</div>

	<!-- XSS 功能模組區塊 -->
	<div class="sidebar-block xss-modules-block">
		<div class="section-header">
			<h4>&#9889; XSS 功能模組</h4>
		</div>
		<div class="modules-grid">
			<?php $_from = $this->_tpl_vars['modules']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
			<?php if ($this->_tpl_vars['v']['isOpen'] == 1 && $this->_tpl_vars['v']['isAudit'] == 1): ?>
			<a class="xss-module-card" href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=module&act=view&id=<?php echo $this->_tpl_vars['v']['id']; ?>
">
				<span class="icon">&#9889;</span>
				<span class="name"><?php echo $this->_tpl_vars['v']['title']; ?>
</span>
			</a>
			<?php endif; ?>
			<?php endforeach; endif; unset($_from); ?>
		</div>
	</div>

	<!-- 快捷入口區塊 -->
	<div class="sidebar-block quick-links-block">
		<div class="section-header">
			<h4>&#128205; 快捷入口</h4>
		</div>
		<div class="quick-links-list">
			<?php if ($this->_tpl_vars['user']->userId == 1): ?>
			<a class="quick-link" href="<?php echo $this->_tpl_vars['url']['root']; ?>
/xss.php?do=admin">&#9889; 管理員後台</a>
			<?php endif; ?>
		</div>
	</div>

</div>