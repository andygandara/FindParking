<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1" />
<link rel="stylesheet" href="/bst/css/bootstrap.min.css" >
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
<style>
body{font-family: 'Raleway', sans-serif;color: #efefef}
</style>
<body style="background-color:#233447">
<?php
$lotid=intval($_GET['lotid']);
if($_GET['cancel']!='')
{
	//Checking it can be cancelled?
	//DB::query('SELECT * FROM parking_statement WHERE ')
	DB::query('UPDATE parking_statement SET `kind`=-1 WHERE `kind`=1 AND `uid`='.$_SESSION['user']['uid'].'');
	DB::query('UPDATE parking_pos SET `recid`=0 WHERE `poid`='.intval($_GET['cancel']));
	?>
		<table class="table form-horizontal">
		<tr><td colspan="2"><br /><font size="5">Success</font><br /></td></tr>
<tr><th>Message</th><td>Your reservation has been cancelled.</td></tr><tr><td colspan="2"><a class="btn btn-info" href="avalparking">Return</a></td></tr></table>
	<?php
	exit;
}
$ld=DB::fetch(DB::query('SELECT * FROM parking_lot WHERE `lotid`='.$lotid));
if($_POST['platenumber']!='')
{
	$st_num=DB::num_rows(DB::query('SELECT * FROM parking_statement WHERE `kind`=1 AND `uid`='.S($_SESSION['user']['uid'])));
if($st_num>0)
{
	?>
	<table class="table form-horizontal">
		<tr><td colspan="2"><br /><font size="5">Oops! Something wrong.</font><br /></td></tr>
<tr><th>Reason</th><td>You have another reservation.</td></tr>
<tr><th>Name</th><td><?php echo $ld['ploname'];?></td></tr>
<tr><th>Address</th><td><?php echo $ld['address'];?></td></tr>
<tr><th>Plate No.</th><td><?php echo $_POST['platenumber'];?></td></tr><tr><td colspan="2"><a class="btn btn-warning" href="avalparking">Return</a></td></tr></table>
	<?php exit;
}
	
$poidt=DB::fetch(DB::query('SELECT poid,pos FROM parking_pos WHERE `recid`=0 AND `lotid`='.$lotid.' '));
	$poid=$poidt['poid'];
DB::query('INSERT INTO `parking`.`parking_statement` ( `starttime`, `poid`, `platenumber`, `kind`,`uid`,`amount`) values ( '.(time()+15*60).', '.$poid.', '.S($_POST['platenumber']).', \'1\','.$_SESSION['user']['uid'].',-1)');
	DB::query('UPDATE parking_pos SET `recid`='.DB::insert_id().' WHERE `poid`='.$poid);
	?>
	<table class="table form-horizontal">
		<tr><td colspan="2"><br /><font size="5">Success!</font><br /></td></tr>
<tr><th>Name</th><td><?php echo $ld['ploname'];?></td></tr>
<tr><th>Address</th><td><?php echo $ld['address'];?></td></tr>
<tr><th>Position</th><td><?php echo $poidt['pos'];?></td></tr>
<tr><th>Plate No.</th><td><?php echo $_POST['platenumber'];?></td></tr><tr><td colspan="2">You must arrive in 15 minutes (<?php echo date('Y-m-d H:i',(time()+15*60));?>)</td></tr><tr><td colspan="2"><a class="btn btn-info" href="avalparking">Return</a></td></tr></table>
	<?php
	exit;
	
}
?><form method="post" action=""><div class="height:50px"></div><table class="table form-horizontal">
	<tr><td colspan="2"><br />Welcome to reserve a park position<br /></td></tr>
<tr><th>Name</th><td><?php echo $ld['ploname'];?></td></tr>
<tr><th>Location</th><td><?php echo $ld['address'];?></td></tr>

<tr><th>You Plate</th><td><select class="form-control" name="platenumber"><option value="<?php echo $_SESSION['user']['platenumber'];?>"><?php echo $_SESSION['user']['platenumber'];?></option></select></td></tr><tr><td colspan="2">You must arrive in 15 minutes (Time select is not supported now.)</td></tr><tr><td colspan="2"><input type="submit" class="btn btn-success" value="Confirm"/> <a class="btn btn-warning" href="javascript:history.back(0);">Return</a></td></tr></table></form>