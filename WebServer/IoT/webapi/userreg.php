<?php
if(!$IN_OA) exit('Access Denied');
if($_POST['email']=='' ||$_POST['password']=='')
{
	exit(json_encode(array('errno'=>2,'errmsg'=>'email and password required')));
}
if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$_POST['email'])) {
  exit(json_encode(array('errno'=>3,'errmsg'=>'illegal email address')));
}
$pla=strtoupper($_POST['platestate'].'-'.$_POST['platenumber']);
$ccl=DB::num_rows(DB::query('SELECT email FROM parking_users WHERE `email` = '.S($_POST['email']).' OR `platenumber` LIKE \'%'.S($pla,'none').',%\''));

if($ccl>0)
	exit(json_encode(array('errno'=>10,'errmsg'=>'user exists or platenumber has been regsitered')));

DB::query('INSERT INTO `parking_users` ( `email`, `password`, `platenumber`) values ( '.S($_POST['email']).', '.S($_POST['password']).','.S($pla.',').')');
exit(json_encode(array('errno'=>0,'errmsg'=>'success','data'=>DB::insert_id(),'sessionid'=>session_id())));
?>