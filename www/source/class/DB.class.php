<?php
/**
 * DB.class.php database operator (PHP 7+ Compatible)
 * @author blue,email: blueforyou@163.com
 * $databaseType: mysql,mssql,oracle..
 * ----------------------------------------------------------------
 * OldCMS,site:http://www.oldcms.com
 * Updated for PHP 7+ compatibility
 */
if(!defined('IN_OLDCMS')) die('Access Denied');

class BlueDB{
	public static function DB($databaseType='mysql'){
		switch(strtolower($databaseType)){
			case 'mysql':
				return new DB_Mysql;
				break;
			default:
				return false;
				break;
		}
	}
}
interface IDataBase{
	function Connect($dbHost='localhost',$dbUser='root',$dbPwd='',$dbName='',$dbCharset='utf8',$tbPrefix='');
	function Execute($sql);
	function Dataset($sql);
	function FirstRow($sql);
	function FirstColumn($sql);
	function FirstValue($sql);
}
/**
* Mysql (PHP 7+ compatible with mysqli)
*/
class DB_Mysql implements IDataBase{
	private $host,$username,$password,$database,$charset;
	public $linkId,$queryId,$rows=array(),$rowsNum=0,$tbPrefix;
	function __destructor(){
		$this->Disconnect();
	}
	/* connect to database */
	public function Connect($dbHost='localhost',$dbUser='root',$dbPwd='',$dbName='',$dbCharset='utf8',$tbPrefix=''){
		$this->host=$dbHost;
		$this->username=$dbUser;
		$this->password=$dbPwd;
		$this->database=$dbName;
		$this->charset=$dbCharset;
		$this->tbPrefix=$tbPrefix;

		// Use persistent connection with p: prefix
		$this->linkId=mysqli_connect('p:'.$this->host,$this->username,$this->password);
		if(!empty($this->linkId)){
			mysqli_query($this->linkId, "SET NAMES '".$this->charset."'");
			if(mysqli_select_db($this->linkId, $this->database)) return $this->linkId;
		}else{
			return false;
		}
	}
	/* disconnect to database */
	private function Disconnect(){
		if(!empty($this->linkId)){
			if(!empty($this->queryId)) mysqli_free_result($this->queryId);
			return mysqli_close($this->linkId);
		}
	}
	/* execute without result */
	public function Execute($sql){
		return mysqli_query($this->linkId, $sql);
	}
	/* auto execute type=>insert/update */
	public function AutoExecute($table,$array=array(),$type='INSERT',$where=''){
		if(!empty($array) && !empty($table)){
			switch(strtoupper($type)){
				case 'INSERT':
					$fields = array_keys($array);
					$values = array_values($array);
					// Escape all values
					$escapedValues = array_map(function($v) {
						return mysqli_real_escape_string($this->linkId, $v);
					}, $values);
					$sql="INSERT INTO {$table}(".implode(',',$fields).") VALUES('".implode("','",$escapedValues)."')";
					break;
				case 'UPDATE':
					$sql="UPDATE {$table}";
					$updates=array();
					foreach($array as $key=>$value){
						$escapedValue = mysqli_real_escape_string($this->linkId, $value);
						$updates[]="{$key}='{$escapedValue}'";
					}
					$sql.=" SET ".implode(',',$updates);
					if(!empty($where)){
						$sql.=" WHERE {$where}";
					}
					break;
				default:break;
			}
			return $this->Execute($sql);
		}else{
			return false;
		}
	}
	/* return dataset of query */
	public function Dataset($sql){
		$this->rows=array();
		$this->queryId=mysqli_query($this->linkId, $sql);
		if ($this->queryId === false) {
			error_log("SQL Error in Dataset: " . mysqli_error($this->linkId) . " | SQL: " . $sql);
			return $this->rows;
		}
		while($row=mysqli_fetch_assoc($this->queryId)){
			$this->rows[]=$row;
		}
		$this->rowsNum=count($this->rows);
		return $this->rows;
	}
	/* return first row */
	public function FirstRow($sql){
		$this->queryId=mysqli_query($this->linkId, $sql);
		if ($this->queryId === false) {
			error_log("SQL Error in FirstRow: " . mysqli_error($this->linkId) . " | SQL: " . $sql);
			return false;
		}
		$row=mysqli_fetch_assoc($this->queryId);
		if(!empty($row)){
			$this->rowsNum=1;
			return $row;
		}else{
			$this->rowsNum=0;
			return false;
		}
	}
	/* return first column (array) */
	public function FirstColumn($sql){
		$Columns=array();
		$this->queryId=mysqli_query($this->linkId, $sql);
		while($row=@mysqli_fetch_row($this->queryId)){
			$Columns[]=$row[0];
		}
		$this->rowsNum=count($Columns);
		return $Columns;
	}
	/* return first value */
	public function FirstValue($sql){
		$this->queryId=mysqli_query($this->linkId, $sql);
		$row=@mysqli_fetch_row($this->queryId);
		if(!empty($row)){
			$this->rowsNum=1;
			return $row[0];
		}else{
			$this->rowsNum=0;
			return false;
		}
	}
	/* last id */
	public function LastId(){
		return mysqli_insert_id($this->linkId);
	}
}
?>
