#!/bin/bash
# USDT 收款自動確認 Bot
# 定時任務：*/5 * * * * /root/d3cd1-main/www/source/bot_payment_checker.sh

DB_HOST="localhost"
DB_USER="xssuser"
DB_PASS="Ss520520"
DB_NAME="xss_platform"

# TRON API
TRON_API="https://api.trongrid.io"
RECEIVE_ADDRESS="TDejRjcLQa92rrE6SB71LSC7J5VmHs35gq"

# 數據庫連接
mysql() {
    mysql -h$DB_HOST -u$DB_USER -p$DB_PASS $DB_NAME -e "$1" 2>/dev/null
}

# 檢查待確認訂單
orders=$(mysql "SELECT orderId, txHash, userId FROM oc_payment_orders WHERE status='pending' AND txHash!=''")

echo "$orders" | while read orderId txHash userId; do
    if [ -z "$orderId" ]; then continue; fi

    # 查詢 TRON 交易
    response=$(curl -s "$TRON_API/v1/transactions/$txHash/info")
    confirmed=$(echo $response | grep -o '"confirmed":[true|false]' | cut -d':' -f2)

    if [ "$confirmed" = "true" ]; then
        # 確認收款，更新用戶權限
        mysql "UPDATE oc_payment_orders SET status='confirmed', confirmTime=$(date +%s) WHERE orderId='$orderId'"

        # 獲取套餐信息
        plan=$(mysql "SELECT plan FROM oc_payment_orders WHERE orderId='$orderId'" | tail -1)

        # 升級用戶
        mysql "UPDATE oc_user SET vip_level='$plan', vip_expire=$(($(date +%s)+2592000)) WHERE id='$userId'"

        echo "[$(date)] Order $orderId confirmed for user $userId"
    fi
done

# 檢查新的 USDT 轉账（從區塊）
# 監聽最新區塊，查找轉账到你的地址的交易
latest_block=$(curl -s "$TRON_API/v1/blocks/latest" | grep -o '"number":[0-9]*' | cut -d':' -f2)

if [ -z "$(cat /tmp/last_block 2>/dev/null)" ]; then
    echo $latest_block > /tmp/last_block
    exit 0
fi

last_block=$(cat /tmp/last_block)
if [ "$latest_block" -gt "$last_block" ];
then
    # 檢查新區塊中的交易
    for block in $(seq $((last_block+1)) $latest_block); do
        txs=$(curl -s "$TRON_API/v1/blocks/$block" | grep -o '"txID":"[^"]*"' | cut -d'"' -f4)
        for tx in $txs; do
            # 檢查交易是否轉账到你的地址
            tx_detail=$(curl -s "$TRON_API/v1/transactions/$tx/info")
            if echo "$tx_detail" | grep -q "$RECEIVE_ADDRESS"; then
                amount=$(echo "$tx_detail" | grep -o '"amount":[0-9]*' | head -1 | cut -d':' -f2)
                # 根據金額匹配套餐...
                echo "[$(date)] New transfer: $tx, amount: $amount"
            fi
        done
    done
    echo $latest_block > /tmp/last_block
fi
