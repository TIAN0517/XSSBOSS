-- USDT 支付系統數據庫表

-- 套餐表
CREATE TABLE IF NOT EXISTS oc_plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    plan_key VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    max_projects INT DEFAULT 0,
    max_cookies_per_day INT DEFAULT 0,
    features TEXT,
    created_at INT DEFAULT 0
);

-- 訂單表
CREATE TABLE IF NOT EXISTS oc_payment_orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    orderId VARCHAR(50) NOT NULL UNIQUE,
    userId INT NOT NULL,
    plan VARCHAR(50) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    txHash VARCHAR(100) DEFAULT '',
    status ENUM('pending','confirmed','failed') DEFAULT 'pending',
    addTime INT DEFAULT 0,
    confirmTime INT DEFAULT 0,
    INDEX idx_user (userId),
    INDEX idx_status (status),
    INDEX idx_txHash (txHash)
);

-- 用戶訂閱表
CREATE TABLE IF NOT EXISTS oc_user_subscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    userId INT NOT NULL UNIQUE,
    plan_key VARCHAR(50) NOT NULL,
    max_projects INT DEFAULT 3,
    max_cookies_per_day INT DEFAULT 100,
    cookies_used_today INT DEFAULT 0,
    last_reset INT DEFAULT 0,
    expire_time INT DEFAULT 0,
    created_at INT DEFAULT 0
);

-- 支付記錄
CREATE TABLE IF NOT EXISTS oc_payment_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    userId INT NOT NULL,
    orderId VARCHAR(50) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(20) DEFAULT 'USDT',
    status ENUM('success','failed','refunded') DEFAULT 'success',
    created_at INT DEFAULT 0
);

-- 插入默認套餐
INSERT IGNORE INTO oc_plans (plan_key, name, price, max_projects, max_cookies_per_day, features, created_at) VALUES
('vip', 'VIP會員', 30.00, 50, 0, '50項目+無限Cookie', UNIX_TIMESTAMP());
