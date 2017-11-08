<?php
$IN_OA=true;
$ajax=($_GET['ajax']=='yes');
require 'class_db.php';
ini_set('display_errors','On');
error_reporting(E_ALL || ~E_NOTICE);
DB::connect();
if($_GET['mod']!='')
{
	//Check if file exists
	require 'admin/'.$_GET['mod'].'.php';
	exit;
}
if(!$ajax){
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="/bst/css/bootstrap.min.css" >
<div class="container-fluid">
<ul class="nav nav-tabs">
  <li role="presentation"><a href="#">Home</a></li>
  <li role="presentation" class="active"><a href="#">Parking Lots</a></li>
  <li role="presentation"><a href="?mod=user">Users</a></li>
  <li role="presentation"><a href="#">Finance</a></li>
</ul>
<?php
}
if($_GET['lotid']=='')
{
	$lots=DB::query('SELECT * FROM parking_lot');
	echo '<br /><font size="5">FindParking Administration System</font>';
echo '<table class="table table-striped"><thead><tr><th>Name</th><th></th><th>Operation</th></tr></thead>';
while($ltd=DB::fetch($lots))
{
	echo '<tr><td><a href="AdminPanel?lotid='.$ltd['lotid'].'">'.$ltd['ploname'].'</a></td><td></td><td><a href="">Edit</a> <a href="">Delete</a> <a href="AdminPanel?m=sta&lotid='.$ltd['lotid'].'">Statement</a></td></tr>';
}	
	echo '</table> Create New Lots';exit;
}
$wowpc=DB::fetch(DB::query('SELECT * FROM parking_lot WHERE `lotid`='.S($_GET['lotid'],'int')));

$query_pos=DB::query('SELECT * FROM parking_pos WHERE `lotid`='.S($_GET['lotid'],'int'));
	if(!$ajax){
?>
<title>Admin Panel - <?php echo $wowpc['ploname'];?></title>
<style>

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
	<font size="5">Parking Lot: <?php echo $wowpc['ploname'];?></font>
	<table class="table table-striped"><thead><tr><th>Space</th><th>Status</th><th>PlateNumber(UserID)</th><th>StartTime</th><th>Operation</th></tr></thead><tbody id="smog">
<?php }
	$i=0;
	$opo='';
	while($data=DB::fetch($query_pos))
	{
		$me=($m['uid']==$_SESSION['user']['uid']);
		if($data['recid']=='0'){$class='green';$status='Available';$m='';$op='<a href="">Reserve</a> ';}
		else{
			$m=DB::fetch(DB::query('SELECT kind,platenumber,starttime,uid FROM parking_statement WHERE `recid`='.$data['recid']));
			if($m['kind']==1){$class='yellow';$status='Reserved';$op='<a href="">Cancel</a>';}
			else {$class='red';$status='In Use';$op='<a href="?mod=lots&reset='.$m['poid'].'">Reset</a>';}
		}
		
		echo '<tr><td>'.$data['pos'].'</td><td class='.$class.'>';
		echo $status;
		if($m['uid']=='-1'){$user=' Guest';}else if($m['uid']==''){$user='';}else{$user =' (UID:'.$m['uid'].')';}
		echo '<td>'.$m['platenumber'].($user).'</td>';
		echo '<td>'.($m['starttime']==0?'-':date('H:i:s',$m['starttime'])).'</td>';
		echo '<td>'.$op.'</td></tr>';
		
	}if($ajax)exit;
		?></tbody>
	</table>
	Pay Code: <input type="text" class="form-control" style="width: 50%" value=""/> <a class="btn btn-primary"> Pay</a>
	<a href="AdminPanel">Return</a>
	</div>
	<script src="/jquery-3.2.0.min.js"></script>
	<script>
		
	function fetch()
		{
			$.get('/AdminPanel?ajax=yes&m=sta&lotid=<?php echo $_GET['lotid'];?>',function(data){$('#smog').html(data);});
		}
		self.setInterval("fetch()",2000)
	</script>
</body>
</html>