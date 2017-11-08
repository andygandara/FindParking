<?php
$IN_OA=true;
require 'class_db.php';
DB::connect();

//every 5 min is
//poll HeartBeat
//poid=<poid>&status=&platenumber=
//
$i=1;
//if($i==1)
//	echo json_encode(array('errno'=>100,'platenumber'=>'AZ-BAZ2122'));
//else if($i==1)
//	echo json_encode(array('errno'=>0,'platenumber'=>''));
//exit;
//DB::query('UPDATE parking_pos SET `recid`='.S($_POST['platenumber']).' WHERE `poid`='.S($_POST['posid']));
$poid=S($_POST['posid'],'int');
$mog=DB::fetch(DB::query('SELECT recid,pos FROM parking_pos WHERE `poid`='.$poid));
$mystatus=$mog['recid'];
if($mystatus!=0)
{
	$poa=DB::fetch(DB::query('SELECT * FROM parking_statement WHERE `recid`='.$mystatus));
}
if(count($mog)==0)$err=0;
else if($poa['kind']=='1'){$err=100;}
$status=$_POST['status'];
if($poa['kind']==2)
{
	if($_POST['status']==0)//释放了
	{
		DB::query('UPDATE parking_statement SET `kind`=4,`endtime`=\''.time().'\' WHERE `recid`='.$poa['recid'].'');
		DB::query('UPDATE parking_pos SET `recid`=0 WHERE `poid`='.$poid);
		if($poa['uid']=='-1'){$code=strtoupper(substr(md5(time()),4,8));}else{$code='';}
	}
}
else if($err==100&&$status==1)//UpdateSession
{
	DB::query('UPDATE parking_statement SET `kind`=2,`starttime`=\''.time().'\' WHERE `recid`='.$poa['recid'].'');
	DB::query('UPDATE parking_pos SET `recid`='.$poa['recid'].' WHERE `poid`='.$poid);
}
else if($err==0&&$status==1)//New Session
{
	DB::query('INSERT INTO `parking`.`parking_statement` ( `starttime`, `poid`, `platenumber`, `kind`,`uid`, `amount`) values ( '.time().', '.$poid.', '.S($_POST['platenumber']).', \'2\',-1 ,-1)');
	DB::query('UPDATE parking_pos SET `recid`='.DB::insert_id().' WHERE `poid`='.$poid);
}
if($poa['platenumber']==""){$poa['platenumber']="";}
echo json_encode(array('errno'=>$err,'kind'=>$poa['kind'],'uid'=>$code,'posname'=>$mog['pos'],'platenumber'=>$poa['platenumber']));
?>