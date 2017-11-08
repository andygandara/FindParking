<?php
$IN_OA=true;
if($_POST['usersession']!='')
{
	session_id($_POST['usersession']);
}
session_name('UserSession');
session_start();
date_default_timezone_set('America/Phoenix'); 
if($_GET['mod']=='testlogin'){
	if($_SESSION['user']['uid']!='')exit(json_encode(array('errno'=>1900,'platenumber'=>$_SESSION['user']['platenumber'])));
}
require 'class_db.php';
ini_set('display_errors','On');
error_reporting(E_ALL || ~E_NOTICE);
DB::connect();
//print_r($_GET);
$userid=2;
if($_GET['mod']!='')
{
	require 'webapi/'.$_GET['mod'].'.php';
}

?>