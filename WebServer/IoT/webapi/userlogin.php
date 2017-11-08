<?php
$userdata=DB::fetch(DB::query('SELECT * FROM parking_users WHERE `email`='.S($_POST['email'])));
if($userdata['password']==$_POST['password'] && $_POST['password']!='')
{
	$_SESSION['user']=$userdata;	exit(json_encode(array('errno'=>'0','errmsg'=>'success','platenumber'=>$userdata['platenumber'],'sessionid'=>session_id())));
}
exit(json_encode(array('errno'=>'1','errmsg'=>'failed','sessionid'=>session_id())));