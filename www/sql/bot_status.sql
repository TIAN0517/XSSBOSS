-- Telegram Bot 狀態管理表
CREATE TABLE IF NOT EXISTS `{PREFIX}bot_status` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `status` ENUM('running','stopped','error') DEFAULT 'stopped',
    `pid` INT(11) DEFAULT NULL,
    `start_time` INT(11) DEFAULT NULL,
    `last_update` INT(11) DEFAULT NULL,
    `message_count` INT(11) DEFAULT 0,
    `error_count` INT(11) DEFAULT 0,
    `last_error` TEXT DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Bot 進程鎖表（防止重複啟動）
CREATE TABLE IF NOT EXISTS `{PREFIX}bot_locks` (
    `lock_key` VARCHAR(64) NOT NULL,
    `lock_value` VARCHAR(128) NOT NULL,
    `expire_time` INT(11) NOT NULL,
    PRIMARY KEY (`lock_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Bot 操作日誌
CREATE TABLE IF NOT EXISTS `{PREFIX}bot_logs` (
    `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
    `action` VARCHAR(50) NOT NULL,
    `details` TEXT DEFAULT NULL,
    `user_id` INT(11) DEFAULT NULL,
    `created_at` INT(11) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_action` (`action`),
    KEY `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 插入初始狀態
INSERT INTO `{PREFIX}bot_status` (`id`, `status`, `start_time`, `last_update`) VALUES (1, 'stopped', NULL, UNIX_TIMESTAMP()) ON DUPLICATE KEY UPDATE `last_update`=UNIX_TIMESTAMP();
