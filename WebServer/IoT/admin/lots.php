<?php
if($_GET['reset']!='')
{
	$poid=intval($_GET['reset']);
	//DB::query('UPDATE parking_statement SET `kind`=4,`endtime`=\''.time().'\' WHERE `recid`='.$poa['recid'].'');
	DB::query('UPDATE parking_pos SET `recid`=0 WHERE `poid`='.$poid);
	header("Location: ".$_SERVER['HTTP_REFERER']);
}
if($_GET['resetpos']!='')
{
	DB::query('');
}