<?php 
/*
By Zhener-Maozhenyu DataBase OPT
*/
if(!$IN_OA){exit('Access Denied');}
function S($theValue, $theType="text"){return DB::SQLDO($theValue, $theType);}
$debug_o =1;

final class DB {  

public static $querynum = 0;  

public static $link; 
 
public static $is_type_tight=false;

static function connect($dbhost='127.0.0.1', $dbuser='parking', $dbpw='parking', $dbname = "parking",$dbcharse='utf8', $pconnect = 0) {  

self::$link = mysqli_connect($dbhost, $dbuser, $dbpw, $dbname);
if(mysqli_connect_errno(self::$link)) {  
self::halt("Can not connect to MySQL server:".mysqli_connect_error());  
}  
mysqli_set_charset(self::$link,$dbcharse); 
if($dbname) {  
//mysqli_select_db($dbname, self::$link);  
}  
return 'finish';
}  


static function fetch($query, $result_type = MYSQL_ASSOC) {  
if($result_type == 'MYSQL_ASSOC') $result_type = MYSQLI_ASSOC;
		return $query ? $query->fetch_array($result_type) : null;
}  
static function fetch_all($sql, $arg = array(), $keyfield = '', $silent=false) {

		$data = array();
		$query = self::query($sql, $arg, $silent, false);
		while ($row = self::$query->fetch_array($query)) {
			if ($keyfield && isset($row[$keyfield])) {
				$data[$row[$keyfield]] = $row;
			} else {
				$data[] = $row;
			}
		}
		self::$db->free_result($query);
		return $data;
}
	
static function query($sql, $type = "") {  
 $func = $type == "UNBUFFERED" && @function_exists("mysqli_unbuffered_query") ?  
"mysqli_unbuffered_query" : "mysqli_query";  
if(!($query = $func(self::$link,$sql)) && $type != "SILENT") {  
self::halt("MySQL Query Error:", $sql);  
}  
self::$querynum++;  
return $query;  
}  


static function affected_rows() {  
return mysqli_affected_rows(self::$link);  
}  


static function error() {  
return ((self::$link) ? mysqli_error(self::$link) : mysqli_error());  
}  


static function errno() {  
return intval((self::$link) ? mysqli_errno(self::$link) : mysqli_errno());  
}  


static function result($query, $row=0,$flname=0) {  
if(!$query || mysqli_num_rows($query) == 0) {
			return null;
		}
		$query->data_seek($row);
		$assocs = $query->fetch_row();
		return $assocs[0];
}  


static function num_rows($query) {  
$query = mysqli_num_rows($query);  
return $query;  
}  


static function num_fields($query) {  
return mysqli_num_fields($query);  
}  


static function free_result($query) {  
return @mysqli_free_result($query);  
}  

 
static function insert_id() {  
return ($id = mysqli_insert_id(self::$link)) >= 0 ? $id : self::$result(self::$query("SELECT last_insert_id()"), 0);  
}  

static function fetch_row($query) {  
$query = mysqli_fetch_row($query);  
return $query;  
}  

static function fetch_fields($query) {  
return mysqli_fetch_field($query);  
}  

static function select_affectedt_rows($rs){
	 return mysqli_affected_rows($rs,self::$link);
	
}

static function version() {  
if(empty($this->version)) {
			$this->version = $this->curlink->server_info;
		}
		return $this->version;
}  

static function close() {  

return mysqli_close(self::$link);  
}  

static function halt($message = "", $sql = "") {
	 @header("Content-type: text/html; charset=gbk");
	 $debug_o=1;
	if ($debug_o==1){
		$debug = debug_backtrace();
		$debugs=self::echoarray($debug);
		echo errormsg($message.self::error(),$sql,$debugs);
		
  	}else{
		echo 'Hello'.$sql;
	}
	exit;
	if(!strstr(self::error(),"for key 'PRIMARY'")){exit;}
}  

static function insert($table,$array){
	$temp="";$temp2='';
	foreach($array as $key=>$value){
		$mode=is_string($value)?'text':'int';
		$temp .="`$key`,";$temp2 .=DB::SQLDO($value,$mode).',';
	}
	$temp = substr($temp,0,strlen($temp)-1);
	$temp2 = substr($temp2,0,strlen($temp2)-1);
	
	$sql = "INSERT INTO `$table` ($temp) VALUES($temp2);";
	return self::query($sql);
}


static function delete($table,$where){
	$sql = "DELETE FROM {$table} where {$where}";
	return self::query($sql);
}

static function update($table,$array,$where){
	foreach ($array as $key=>$value){
		$temp .= "`$key` = ".DB::SQLDO($value).",";
	}
	$temp = substr($temp,0,strlen($temp)-1);
	$sql = "UPDATE {$table} SET $temp WHERE $where";
	return self::query($sql);
}

static function select(){
	$numargs = func_num_args();
	$where = "";$key="";$limit="";$by="";
	if($numargs==0){return false;}
	//echo $numargs;
	if($numargs>=2){
		$arg_list = func_get_args();
		$table = $arg_list[0];
		unset($arg_list[0]);
	//	print_r($arg_list);
		foreach($arg_list as $k=>$value){
			if(preg_match("#^(where:)\w#",$value)){
				$temp = explode(":",$value);
					$where = "WHERE {$temp[1]} " ;
			}elseif(preg_match("#^by:\w#",$value)){
				$temp = explode(":",$value);
				$by = "order by {$temp[1]}" ;
			}elseif(preg_match("#^limit:\w#",$value)){
				$temp = explode(":",$value);
				$limit = "limit {$temp[1]}";
			}else{
				$key .= "$value,";
			}
		}
		
		if($key==""){
			$key = "*";
		}else{
			$key =substr($key,0,strlen($key)-1); 
		}
		
	$sql_base="SELECT $key FROM $table";
	}
	if(!empty($where)){
		$sql_base .= " $where";
	}
	if(!empty($by)){
		$sql_base .= " $by";
	}
	if(!empty($limit)){
		$sql_base .= " $limit";
	}
	//echo $sql_base;
	//echo $by ;
	$rs = self::query($sql_base);
	$re=array();
	if(self::num_rows($rs)>=1){
		while($info = self::fetch_array($rs)){
			$re[]=$info;
		}
	}
	self::free_result($rs);
	return $re;
	}
	

 	static function echoarray($array){
		$i=0;
		
		$no=1;
		while($i<count($array)){
			if($array[$i]['function']!='halt'){
				$array[$i]['file']=str_ireplace(dirname( __FILE__ ),"",$array[$i]['file']);
			$a=$a.'<tr class="bg1"><td>'.$no.'</td><td>'.$array[$i]['file'].'</td><td>'.$array[$i]['line'].'</td><td>'.$array[$i]['class']."::".$array[$i]['function'].'()</td></tr>';
			$no++;
			}
			$i++;
			
		}
		return $a;
	}

	function get_server_info(){
		return mysqli_get_server_info();
	}
  

/*

*/
	static function big_select($table,$keys,$index,$pagesize,$pageNo,$orderby=NULL,$where=NULL){
		$start=$pageNo*$pagesize;
		if($where){
			$sqlIndex="SELEECT {$index} from {$table} where {$where}";
		}else{
			$sqlIndex="SELEECT {$index} from {$table}";
		}
		if($orderby){
			$sqlIndex .=" ORDER BY {$orderby} Limit $start,$pagesize";	
		}else{
			$sqlIndex .=" ORDER BY  Limit $start,$pagesize";
		}
		$sql = "SELECT $keys FROM {$table} INNER JOIN({$sqlIndex}) AS lim USING({$index})";
		
		$rs = 	self::query($sql);
		
			$re=array();
	if(self::num_rows($rs)>=1){
		while($info = self::fetch_array($rs)){
			$re[]=$info;
		}
	}
	self::free_result($rs);
	return $re;
		
	}
	static function big_del($table,$where){
		set_time_limit(0);
		$sql="delete from {$table} where {$where} Limit 5000";
		$rows = 0;
		$eff=0;
		do{
			self::query($sql);
			$rows=self::affected_rows();
			$eff += $rows;
		}while($rows>0);
		return $eff;
	}
	
	static function SQLDO($theValue, $theType="text", $htmldo = "stop", $theNotDefinedValue = "") 
{
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
	//if($htmldo=="stop"){$theValue= htmlspecialchars($theValue,NULL,"UTF-8");}
	//' OR ''=''
  $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string(self::$link,$theValue) : mysqli_escape_string(self::$link,$theValue);
  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
	 case "none":
      $theValue = ($theValue != "") ? $theValue  : "NULL";
     break;  
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

function errormsg($msg,$sql,$debuttg){
	if($sql==''){$sql='None';}
return '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title>Zhener-soft - Database Error</title>
	<meta http-equiv="Content-Type" content="text/html; charset=gbk" />
	<meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE" />
	<style type="text/css">
	<!--
	body { background-color: white; color: black; font: 9pt/11pt verdana, arial, sans-serif;}
	#container { width: 1024px; }
	#message   { width: 1024px; color: black; }

	.red  {color: red;}
	a:link     { font: 9pt/11pt verdana, arial, sans-serif; color: red; }
	a:visited  { font: 9pt/11pt verdana, arial, sans-serif; color: #4e4e4e; }
	h1 { color: #FF0000; font: 18pt "Verdana"; margin-bottom: 0.5em;}
	.bg1{ background-color: #FFFFCC;}
	.bg2{ background-color: #EEEEEE;}
	.table {background: #AAAAAA; font: 11pt Menlo,Consolas,"Lucida Console"}
	.info {
	    background: none repeat scroll 0 0 #F3F3F3;
	    border: 0px solid #aaaaaa;
	    border-radius: 10px 10px 10px 10px;
	    color: #000000;
	    font-size: 11pt;
	    line-height: 160%;
	    margin-bottom: 1em;
	    padding: 1em;
	}

	.help {
	    background: #F3F3F3;
	    border-radius: 10px 10px 10px 10px;
	    font: 12px verdana, arial, sans-serif;
	    text-align: center;
	    line-height: 160%;
	    padding: 1em;
	}

	.sql {
	    background: none repeat scroll 0 0 #FFFFCC;
	    border: 1px solid #aaaaaa;
	    color: #000000;
	    font: arial, sans-serif;
	    font-size: 9pt;
	    line-height: 160%;
	    margin-top: 1em;
	    padding: 4px;
	}
	-->
	</style>
</head>
<body>
<div id="container">
<h1>Zhener-soft Database Error</h1>
<div class="info">'.$msg.'<div class="sql">'.$sql.'</div></div>

<div class="info"><p><strong>PHP Debug</strong></p><table cellpadding="5" cellspacing="1" width="100%" class="table"><tr class="bg2"><td>No.</td><td>File</td><td>Line</td><td>Code</td></tr>'.$debuttg.'</table></div><div class="help"><a href="http://app.zhener.pw">www.zhener.pw</a> Zhener Database OpeClass <a href="http://www.zhener.pw" target="_blank"><span class="red">Need Help?</span></a></div>
</div>
</body>
</html>';}
?>