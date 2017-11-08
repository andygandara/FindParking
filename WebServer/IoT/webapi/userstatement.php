<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1" />
<link rel="stylesheet" href="/bst/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1" />
<style>
body
	{
		font-family: 'Raleway', sans-serif;color: #efefef
		/*color: */
	}
</style>
<body style="background-color:#233447">
<a href="avalparking" style="padding-left: 20px; padding-right: 15px"><img src="/static/park.png" width="100px" height="100px" /></a> <a href="javascript:;"><img src="/static/statement_a.png" width="100px" height="100px" /></a> <a href="parking://gotomap?lat=0&lon=0" style="padding-right: 20px"><img src="/static/search.png" width="100px" height="100px" /></a>
<?php
$lots=DB::query('SELECT * FROM parking_statement WHERE `uid`='.$_SESSION['user']['uid'].' ORDER BY `endtime` DESC');
$outputd=array();
echo '<table class="table"><thead><tr><th>Date</th><th>ParkingPOT</th><th>Amount</th></tr></thead>';
while($ltd=DB::fetch($lots))
{
	$ltid=DB::result(DB::query('SELECT lotid FROM parking_pos WHERE `poid`='.$ltd['poid']));
	$pname=DB::result(DB::query('SELECT ploname FROM parking_lot WHERE `lotid`='.$ltid));
	if($ltd['amount']==-1)$ltd['amount']='Pending';
	else $ltd['amount']='$'.number_format($ltd['amount'],2);
	echo '<tr><td>'.date('Y-m-d',$ltd['starttime']).'</td><td>'.$pname.'</td><td>'.$ltd['amount'].'</td></tr>';
	echo' <tr><td colspan=3>';
	if($ltd['kind']==1)
	{
		echo 'Reserved. Arrived before '.date('H:i',$ltd['starttime']);
	}
	else if($ltd['kind']==2 || $ltd['kind']==3)
	{
		echo 'From: '.date('H:i',$ltd['starttime']);
	}
	else{
		echo date('H:i',$ltd['starttime']).' - '.date('H:i',$ltd['endtime']).' ('.round(($ltd['endtime']-$ltd['starttime'])/3600,1).' hrs)';
	}
	echo '</td></tr>';
}
echo '</table>';
?>