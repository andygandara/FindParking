<?php
$IN_OA=true;
require 'class_db.php';
ini_set('display_errors','On');
error_reporting(E_ALL || ~E_NOTICE);
DB::connect();

if($_GET['lotid']==''){exit('No!');}
$wowpc=DB::fetch(DB::query('SELECT * FROM parking_lot WHERE `lotid`='.S($_GET['lotid'],'int')));

$query_pos=DB::query('SELECT * FROM parking_pos WHERE `lotid`='.S($_GET['lotid'],'int'));
if($_GET['inajax']!='yes')
{
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Parking Infomation - <?php echo $wowpc['ploname'];?></title>
<?php }if($_GET['a']!='x'){?>
	<style>
	h1{
	text-align:center;
}

#tra{
	width:100%;
	background-color: #f1f1f1
}

.floor td{
	display:inline-block;    
    width:33%;
    text-align:center;
}

table {
	width:100%;
	
}

td{
	width:10%;
	text-align:center;
}

.Floors tr{
	height:120px;
	text-align:center;
}

.yellow{
	background-color:#ffff66;
}.my{
	color: #63ADF1;}
.red{
	background-color:#ff4d4d;
}
.green{
	background-color:#66ff66;
}
	</style>
</head>
Welcome to Parking Lot: <?php echo $wowpc['ploname'];?>
<br />
<div id="smog">
<?php
}
	$template=file_get_contents('pktempl/'.$_GET['lotid'].'.html');
	$i=0;
	$opo='';
	while($data=DB::fetch($query_pos))
	{
		$i++;
		$me=($m['uid']==$_SESSION['user']['uid']);
		if($data['recid']=='0'){$class='green';$status='Available';}
		else{
			$m=DB::fetch(DB::query('SELECT kind,platenumber,starttime,uid FROM parking_statement WHERE `recid`='.$data['recid']));
			$me || $m['platenumber'] = substr($m['platenumber'],0,3)."***".substr($m['platenumber'],6,4);
			if($m['kind']==1){$class='yellow';$status='Reserved By: '.$m['platenumber'];}
			else {$class='red';$status='In Use (From '.date('H:i:s',$m['starttime']).')';}
		}
		
		$op = '<td class='.$class.'>';
		$op.= '<p>'.$data['pos'].'</p>';
		$op.= '<p>'.$status.'</p>';
		$op.= '</td>';
		$class='';
		$template=str_replace('['.$data['pos'].']',$op,$template);
	}
	echo $template;
	if($_GET['a']=='x')exit;
	?>
	</div>
	<script src="/jquery-3.2.0.min.js"></script>
	<script>
		
	function fetch()
		{
			$.get('/lotgui?inajax=yes&a=x&lotid=<?php echo $_GET['lotid'];?>',function(data){$('#smog').html(data);});
		}
		self.setInterval("fetch()",2000)
	</script>
</body>
</html>