#!/bin/bash
# Bot 啟動腳本 - 系統啟動時自動運行
# 使用 systemd 或 supervisor 管理更佳

BOT_SCRIPT="/root/d3cd1-main/www/source/telegram_bot.php"
LOG_FILE="/var/log/xss_bot.log"

start_bot() {
    echo "[$(date)] 啟動 XSS Bot..." >> $LOG_FILE
    while true; do
        php $BOT_SCRIPT
        echo "[$(date)] Bot 重啟中..." >> $LOG_FILE
        sleep 5
    done
}

case "$1" in
    start)
        start_bot &
        echo "Bot 已啟動 (PID: $!)"
        ;;
    stop)
        pkill -f "telegram_bot.php"
        echo "Bot 已停止"
        ;;
    restart)
        pkill -f "telegram_bot.php"
        sleep 2
        start_bot &
        echo "Bot 已重啟"
        ;;
    *)
        echo "用法: $0 {start|stop|restart}"
        exit 1
        ;;
esac
