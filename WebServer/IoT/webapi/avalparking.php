<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1" />
<link rel="stylesheet" href="/bst/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<script src="/jquery-3.2.0.min.js"></script>
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
<style>
	
	#wwo
	{
		 color:#292929;
	}
body
	{
		font-family: 'Raleway', sans-serif;color: #efefef
		/*color: */
	}
	.panel
	{
		background-color: rgba(255,255,255,0.8);
	}
	.panel-body
	{
		color: #999999;
	}
</style>
<?php
//$_POST['clat'] $_POST['clon']

/*
parm : lat lon

Show Parking Lot Name/Pos(map) Avalable
*/
//Waiting Then cancel kind=1 starttime<0 to 4 and charge

?>
<body style="background-color:#233447">
<a href="javascript:;" style="padding-left: 20px; padding-right: 15px"><img src="/static/park_a.png" width="100px" height="100px" /></a> <a href="userstatement"><img src="/static/statement.png" width="100px" height="100px" /></a> <a href="parking://gotomap?lat=0&lon=0" style="padding-right: 20px"><img src="/static/search.png" width="100px" height="100px" /></a>
<?php
echo '<div style="padding-left: 1%; font-size:18px"><br />';
echo 'Hi '.$_SESSION['user']['email'].'! <br />';
$lots=DB::fetch(DB::query('SELECT * FROM parking_statement WHERE `uid`='.S($_SESSION['user']['uid']).' AND `kind` BETWEEN 1 AND 3'));
$outputd=array();
if(count($lots)>0)
{
	$ltid=DB::result(DB::query('SELECT lotid FROM parking_pos WHERE `poid`='.$lots['poid']));
	$pname=DB::result(DB::query('SELECT ploname FROM parking_lot WHERE `lotid`='.$ltid));
	echo '<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title">Reservation</h3>
    </div>
    <div class="panel-body">
      You have a reservation at <b>'.$pname.'</b> before <b>'.date('H:i',$lots['starttime']).'</b>';
	if($lots['kind']==1)
	echo 
	'<a onclick="return confirm(\'Are you sure to cancel this reservation?\',\'Confirm\');" href="reserve?cancel='.$lots['poid'].'">Click Here to Cancel</a>';
	echo '</div>
</div><div id="wop" style="width:100%;"></div>';
	
}
else{
	$lots=DB::query('SELECT * FROM parking_lot');
$outputd=array();
echo '<br /><font size="5">Here is parking lots near You:</font><br />You can click one row for detail</div>';
echo '<table class="table table-striped"><thead style="background-color:#31a1f0"><tr><th>Name</th><th>POS Open</th><th>Distance</th></tr></thead>';
while($ltd=DB::fetch($lots))
{
	$seats=DB::result(DB::query('SELECT COUNT(*) FROM parking_pos WHERE `recid`=0 AND `lotid`='.$ltd['lotid']));
	$bgc=($seats==0?'#FF7777':'transparent');
	echo '<tr data-lid="'.$ltd['lotid'].'" style="background-color:'.$bgc.';" height="60px" onclick="OPO(this);"><td>'.$ltd['ploname'].'</td><td>'.$seats.'</td><td>0.1mi</td></tr>';
	echo '<tr class="hide" rlid="'.$ltd['lotid'].'" style="background-color:'.$bgc.'"><td colspan="3">Address: '.$ltd['address'].' <br />';
	if($seats!=0)
	echo '<a href="reserve?lotid='.$ltd['lotid'].'" class="btn btn-info">Reserve</a> ';
	
	echo '<a href="parking://gotomap?lat=&lon=" class="btn btn-default">Open Map</a></td></tr>';
}
echo '</table>';
}
?>
&nbsp;<a href="javascript:location.href=location.href" style="font-size: 18px" class="btn btn-primary">Refresh</a>
<script>
	function LoadLS()
	{$.get('../lotgui?lotid=<?php echo $ltid;?>&inajax=yes',function(data){$('#wop').html(data)});
		
	}
	LoadLS();
	function OPO(obj)
	{
		$('tr[class=show]').removeClass('show').addClass('hide');
		$('tr[rlid='+$(obj).data('lid')+']').removeClass('hide').addClass('show');
	}
</script>