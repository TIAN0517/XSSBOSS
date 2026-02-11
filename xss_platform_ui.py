import customtkinter as ctk
import threading
from datetime import datetime

# ==========================================
# 規範設定：統一使用 text_color，禁止 fg
# ==========================================
COLOR_BG = "#06090F"
COLOR_CARD = "#111721"
COLOR_ACCENT = "#00D1FF"  # 3D 霓虹青
COLOR_BORDER = "#1F2937"
COLOR_TEXT = "#E5E7EB"
COLOR_TEXT_MUTED = "#6B7280"

class XSSLayoutMaster(ctk.CTk):
    def __init__(self):
        super().__init__()

        # --- 1. 視窗權重校正 (解決解析度問題的核心) ---
        self.title("XSS PLATFORM - STABLE LAYOUT")
        self.geometry("1400x900")
        self.configure(fg_color=COLOR_BG)

        # 配置全局權重：讓右側主功能區 (Col 1) 自動拉伸 ### UI FIX ###
        self.grid_columnconfigure(1, weight=1)
        self.grid_rowconfigure(0, weight=1)

        self.init_layout()

    def init_layout(self):
        """
        佈局結構：
        [側邊欄 (280px)] [ 主工作區 (自適應) ]
                        [模組區 70%]
                        [日誌區 30%]
        """
        # --- A. 左側固定欄 ---
        self.side_panel = ctk.CTkFrame(
            self,
            width=280,
            fg_color=COLOR_CARD,
            border_width=1,
            border_color=COLOR_BORDER
        )
        self.side_panel.grid(row=0, column=0, sticky="nsew", padx=10, pady=10) ### UI FIX ###
        self.side_panel.grid_propagate(False) # 鎖定寬度防止被擠壓 ### UI FIX ###

        # 側邊欄內部權重 (讓專案列表區自動拉伸) ### UI FIX ###
        self.side_panel.grid_rowconfigure(2, weight=1)

        # 標題樣式統一
        ctk.CTkLabel(
            self.side_panel,
            text="⚡ 我的專案",
            font=("Arial", 18, "bold"),
            text_color=COLOR_ACCENT
        ).grid(row=0, column=0, padx=20, pady=20, sticky="w") ### UI FIX ###

        # 專案列表容器 (可滾動)
        self.project_scroll = ctk.CTkScrollableFrame(
            self.side_panel,
            fg_color=COLOR_BG,
            border_width=1,
            border_color=COLOR_BORDER
        )
        self.project_scroll.grid(row=2, column=0, sticky="nsew", padx=10, pady=(0, 10)) ### UI FIX ###
        self.project_scroll.grid_rowconfigure(0, weight=1)
        self.project_scroll.grid_columnconfigure(0, weight=1)

        # 示例專案按鈕 (展示卡片樣式)
        self._create_project_button(self.project_scroll, "測試專案 #1") ### UI FIX ###
        self._create_project_button(self.project_scroll, "測試專案 #2") ### UI FIX ###
        self._create_project_button(self.project_scroll, "演示環境") ### UI FIX ###

        # --- B. 右側主區域 (分上下層) ---
        self.main_workarea = ctk.CTkFrame(self, fg_color="transparent")
        self.main_workarea.grid(row=0, column=1, sticky="nsew", padx=(0, 10), pady=10) ### UI FIX ###

        # 配置主區域權重：上層模組區 (70%), 下層日誌區 (30%) ### UI FIX ###
        self.main_workarea.grid_rowconfigure(0, weight=7)
        self.main_workarea.grid_rowconfigure(1, weight=3)
        self.main_workarea.grid_columnconfigure(0, weight=1)

        # B-1. 上層：功能模組矩陣 (Scrollable)
        self.module_container = ctk.CTkScrollableFrame(
            self.main_workarea,
            label_text="XSS 功能模組矩陣",
            label_text_color=COLOR_ACCENT,
            fg_color=COLOR_CARD,
            border_width=1,
            border_color=COLOR_BORDER
        )
        self.module_container.grid(row=0, column=0, sticky="nsew", pady=(0, 10)) ### UI FIX ###

        # 模組矩陣內部：使用 autogrid 自動均勻分布 ### UI FIX ###
        self.module_container.grid_columnconfigure(0, weight=1)

        # 創建模組卡片網格
        self._create_module_grid() ### UI FIX ###

        # B-2. 下層：日誌終端
        self.terminal_frame = ctk.CTkFrame(
            self.main_workarea,
            fg_color=COLOR_CARD,
            border_width=1,
            border_color=COLOR_BORDER
        )
        self.terminal_frame.grid(row=1, column=0, sticky="nsew") ### UI FIX ###
        self.terminal_frame.grid_rowconfigure(0, weight=1)
        self.terminal_frame.grid_columnconfigure(0, weight=1)

        # 日誌標題
        ctk.CTkLabel(
            self.terminal_frame,
            text="📋 執行日誌",
            font=("Arial", 14, "bold"),
            text_color=COLOR_ACCENT
        ).grid(row=0, column=0, sticky="nw", padx=15, pady=(10, 0)) ### UI FIX ###

        self.log_text = ctk.CTkTextbox(
            self.terminal_frame,
            fg_color=COLOR_BG,
            text_color=COLOR_ACCENT,
            font=("Consolas", 12),
            border_width=1,
            border_color=COLOR_BORDER
        )
        self.log_text.grid(row=1, column=0, sticky="nsew", padx=10, pady=(35, 10)) ### UI FIX ###
        self.log_text.configure(state="disabled")

        # 初始日誌
        self.log_thread_safe("SYSTEM", "平台初始化完成") ### UI FIX ###
        self.log_thread_safe("READY", "等待指令...") ### UI FIX ###

    def _create_project_button(self, parent, project_name):
        """創建專案按鈕卡片 - 統一樣式 ### UI FIX ###"""
        btn = ctk.CTkButton(
            parent,
            text=project_name,
            fg_color="transparent",
            border_width=1,
            border_color=COLOR_BORDER,
            text_color=COLOR_TEXT,
            font=("Arial", 13),
            height=40,
            corner_radius=8,
            hover_color=COLOR_ACCENT,
            command=lambda: self.log_thread_safe("PROJECT", f"選擇: {project_name}")
        )
        btn.grid(row=0, column=0, sticky="ew", padx=5, pady=5) ### UI FIX ###
        return btn

    def _create_module_grid(self):
        """創建模組卡片網格 - 統一邊框與間距 ### UI FIX ###"""
        modules = [
            ("🍪 Cookie 竊取", "cookie"),
            ("📍 位置追蹤", "location"),
            ("⌨️ 鍵盤記錄", "keystroke"),
            ("📸 螢幕截圖", "screenshot"),
            ("🌐 釣魚頁面", "phishing"),
            ("🔄 重定向", "redirect"),
        ]

        # 使用 autogrid 實現均勻分布 ### UI FIX ###
        for i, (name, tag) in enumerate(modules):
            self._create_module_card(i // 3, i % 3, name, tag) ### UI FIX ###

    def _create_module_card(self, row, col, name, tag):
        """創建單個模組卡片 - 固定尺寸 + 統一邊框 ### UI FIX ###"""
        card = ctk.CTkFrame(
            self.module_container,
            fg_color=COLOR_BG,
            border_width=1,
            border_color=COLOR_BORDER,
            corner_radius=10
        )
        # 使用 padx/pady 統一間距為 10 ### UI FIX ###
        card.grid(row=row, column=col, sticky="nsew", padx=10, pady=10) ### UI FIX ###

        # 模組圖標
        icon_map = {
            "cookie": "🍪",
            "location": "📍",
            "keystroke": "⌨️",
            "screenshot": "📸",
            "phishing": "🌐",
            "redirect": "🔄"
        }

        ctk.CTkLabel(
            card,
            text=f"  {icon_map.get(tag, '⚡')}  {name}",
            font=("Arial", 14, "bold"),
            text_color=COLOR_ACCENT
        ).pack(anchor="w", padx=12, pady=(12, 8)) ### UI FIX ###

        # 模組描述
        desc_map = {
            "cookie": "竊取目標 Cookie",
            "location": "獲取地理位置",
            "keystroke": "記錄鍵盤輸入",
            "screenshot": "截取螢幕畫面",
            "phishing": "偽造登入頁面",
            "redirect": "302 重定向"
        }

        ctk.CTkLabel(
            card,
            text=desc_map.get(tag, ""),
            font=("Arial", 11),
            text_color=COLOR_TEXT_MUTED
        ).pack(anchor="w", padx=12, pady=(0, 8)) ### UI FIX ###

        # 執行按鈕 - 固定邊框樣式 ### UI FIX ###
        ctk.CTkButton(
            card,
            text="執行",
            fg_color=COLOR_CARD,
            border_width=1,
            border_color=COLOR_ACCENT,
            text_color=COLOR_ACCENT,
            font=("Arial", 11, "bold"),
            height=28,
            corner_radius=6,
            hover_color=COLOR_ACCENT,
            command=lambda: self.run_module(name, tag)
        ).pack(fill="x", padx=12, pady=(0, 12)) ### UI FIX ###

    # ==========================================
    # 🛠️ 核心功能接入點 (不要動 UI，動這裡就好)
    # ==========================================

    def run_module(self, module_name, tag):
        """### 接入點：在這裡放你原本的 PHP 調用代碼 ###"""
        self.log_thread_safe("ACTION", f"啟動模組: {module_name}") ### UI FIX ###
        threading.Thread(target=lambda: self._mock_module_task(module_name, tag), daemon=True).start() ### UI FIX ###

    def _mock_module_task(self, module_name, tag):
        """### 模擬任務 (可替換為真實邏輯) ###"""
        import time
        time.sleep(0.5)
        self.log_thread_safe("SUCCESS", f"{module_name} 任務完成") ### UI FIX ###
        self.log_thread_safe("DATA", f"標籤: {tag} - 結果已記錄") ### UI FIX ###

    def log_thread_safe(self, category, message):
        """符合規範的日誌系統：f-string 對齊 + Thread-safe ### UI FIX ###"""
        def _update():
            now = datetime.now().strftime("%H:%M:%S")
            # f-string 寬度對齊 (12格)
            formatted = f"[{now}] {category:<12} | {message}\n"
            self.log_text.configure(state="normal")
            self.log_text.insert("end", formatted)
            self.log_text.see("end")
            self.log_text.configure(state="disabled")
        self.after(0, _update)

# ==========================================
# 啟動
# ==========================================
if __name__ == "__main__":
    app = XSSLayoutMaster()
    app.mainloop()
