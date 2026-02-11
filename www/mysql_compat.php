<?php
/**
 * MySQL 兼容层 - 将 mysql_* 函数映射到 mysqli_*
 * PHP 8+ 兼容层
 */

// PHP 8.0+ 移除的函数兼容层
if (!function_exists('get_magic_quotes_gpc')) {
    function get_magic_quotes_gpc() {
        return false;
    }
}

if (!function_exists('mysql_connect')) {
    // 创建全局连接池
    $GLOBALS['_mysql_connections'] = array();
    $GLOBALS['_mysql_last_link'] = null;

    function mysql_connect($server, $username, $password, $new_link = false) {
        // 持久连接支持
        if (strpos($server, 'p:') === 0) {
            $server = substr($server, 2);
        }
        $link = mysqli_connect('p:' . $server, $username, $password);
        if ($link) {
            $GLOBALS['_mysql_connections'][spl_object_id($link)] = $link;
            $GLOBALS['_mysql_last_link'] = $link;
        }
        return $link;
    }

    function mysql_pconnect($server, $username, $password) {
        return mysql_connect($server, $username, $password, false);
    }

    function mysql_close($link = null) {
        $link = $link ?: $GLOBALS['_mysql_last_link'];
        if ($link) {
            $id = spl_object_id($link);
            unset($GLOBALS['_mysql_connections'][$id]);
            return mysqli_close($link);
        }
        return false;
    }

    function mysql_select_db($database_name, $link = null) {
        $link = $link ?: $GLOBALS['_mysql_last_link'];
        return mysqli_select_db($link, $database_name);
    }

    function mysql_query($query, $link = null) {
        $link = $link ?: $GLOBALS['_mysql_last_link'];
        return mysqli_query($link, $query);
    }

    function mysql_unbuffered_query($query, $link = null) {
        return mysql_query($query, $link);
    }

    function mysql_fetch_assoc($result) {
        return mysqli_fetch_assoc($result);
    }

    function mysql_fetch_row($result) {
        return mysqli_fetch_row($result);
    }

    function mysql_fetch_array($result, $result_type = MYSQLI_BOTH) {
        return mysqli_fetch_array($result, $result_type);
    }

    function mysql_free_result($result) {
        return mysqli_free_result($result);
    }

    function mysql_num_rows($result) {
        return mysqli_num_rows($result);
    }

    function mysql_num_fields($result) {
        return mysqli_num_fields($result);
    }

    function mysql_affected_rows($link = null) {
        $link = $link ?: $GLOBALS['_mysql_last_link'];
        return mysqli_affected_rows($link);
    }

    function mysql_insert_id($link = null) {
        $link = $link ?: $GLOBALS['_mysql_last_link'];
        return mysqli_insert_id($link);
    }

    function mysql_error($link = null) {
        $link = $link ?: $GLOBALS['_mysql_last_link'];
        return mysqli_error($link);
    }

    function mysql_errno($link = null) {
        $link = $link ?: $GLOBALS['_mysql_last_link'];
        return mysqli_errno($link);
    }

    function mysql_real_escape_string($string, $link = null) {
        $link = $link ?: $GLOBALS['_mysql_last_link'];
        return mysqli_real_escape_string($link, $string);
    }

    function mysql_escape_string($string) {
        return mysqli_escape_string($GLOBALS['_mysql_last_link'], $string);
    }

    function mysql_stat($link = null) {
        $link = $link ?: $GLOBALS['_mysql_last_link'];
        return mysqli_stat($link);
    }

    function mysql_get_client_info() {
        return mysqli_get_client_info();
    }

    function mysql_ping($link = null) {
        $link = $link ?: $GLOBALS['_mysql_last_link'];
        return mysqli_ping($link);
    }

    // 设置字符集
    function mysql_set_charset($charset, $link = null) {
        $link = $link ?: $GLOBALS['_mysql_last_link'];
        return mysqli_set_charset($link, $charset);
    }

    // 别名 - mysql_query 的别名
    function mysql_db_query($database, $query, $link = null) {
        $link = $link ?: $GLOBALS['_mysql_last_link'];
        if (mysqli_select_db($link, $database)) {
            return mysqli_query($link, $query);
        }
        return false;
    }

    // 兼容 mysql_list_tables 等函数
    function mysql_list_tables($database, $link = null) {
        $link = $link ?: $GLOBALS['_mysql_last_link'];
        $result = mysqli_query($link, "SHOW TABLES FROM " . mysqli_real_escape_string($link, $database));
        return $result;
    }

    function mysql_tablename($result, $i) {
        mysqli_data_seek($result, $i);
        $row = mysqli_fetch_array($result);
        return $row[0];
    }

    function mysql_table_exists($table, $link = null) {
        $link = $link ?: $GLOBALS['_mysql_last_link'];
        $result = mysqli_query($link, "SHOW TABLES LIKE '" . mysqli_real_escape_string($link, $table) . "'");
        return mysqli_num_rows($result) > 0;
    }
}
?>
