<?php
/**
 * WAF Core - Mini Version
 * 精简版 WAF 核心
 */

// WAF 模式: 'block' | 'log'
define('WAF_MODE', 'block');
define('WAF_LOG_FILE', ROOT_PATH . '/waf_logs.txt');

class WAF {
    private $detected = false;
    private $type = '';

    // 精简攻击特征库
    private $xss = ['script','onload','onerror','onclick','javascript:','data:','iframe','object'];
    private $sqli = ['union','select','insert','update','delete','drop','--','#','/*','or 1=1','and 1=1'];
    private $cmd  = ['cmd.exe','/bin/sh','|',';','&&','`','$(','wget','curl','nc'];

    public function mode($m='block'){ return $this; }

    public function check($input){
        if(empty($input)) return false;
        $str = is_array($input)?json_encode($input):(string)$input;

        // XSS
        foreach($this->xss as $p) if(stripos($str,$p)!==false) return $this->hit('XSS',$str);
        // SQLi
        foreach($this->sqli as $p) if(stripos($str,$p)!==false) return $this->hit('SQLi',$str);
        // CMD
        foreach($this->cmd as $p) if(stripos($str,$p)!==false) return $this->hit('CMD',$str);

        return false;
    }

    private function hit($type,$payload){
        $this->detected=true; $this->type=$type;
        $this->log($type,$payload);
        if(WAF_MODE==='block') $this->block();
        return true;
    }

    private function block(){
        header('HTTP/1.1 403 WAF');
        exit('<h1>403 WAF</h1><p>Blocked: '.$this->type.'</p>');
    }

    private function log($type,$payload){
        $log = sprintf("[%s] %s | %s\n",date('Y-m-d H:i:s'),$type,substr($payload,0,200));
        file_put_contents(WAF_LOG_FILE,$log,FILE_APPEND);
    }

    public function clean($input){
        if(empty($input)) return $input;
        if(is_array($input)) return array_map([$this,'clean'],$input);
        return htmlspecialchars($input,ENT_QUOTES,'UTF-8');
    }

    public function logs($n=50){
        return file_exists(WAF_LOG_FILE)?array_slice(file(WAF_LOG_FILE),-$n):[];
    }

    public function isDetected(){ return $this->detected; }
    public function getType(){ return $this->type; }
}

$GLOBALS['waf'] = new WAF();

// 便捷函数
function waf_check(){ return $GLOBALS['waf']->check($_GET) || $GLOBALS['waf']->check($_POST); }
function waf_clean($x){ return $GLOBALS['waf']->clean($x); }
function waf_logs(){ return $GLOBALS['waf']->logs(); }
?>
