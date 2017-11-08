<?php
while($data=DB::fetch($query_pos))
	{
		$i++;
		if($data['recid']=='0'){$class='green';$status='Available';}
		else{
			$m=DB::fetch(DB::query('SELECT kind,platenumber,starttime FROM parking_statement WHERE `recid`='.$data['recid']));
			$m['platenumber'] = substr($m['platenumber'],0,3)."***".substr($m['platenumber'],6,4);
			if($m['kind']==1){$class='yellow';$status='Reserved By: '.$m['platenumber'];}
			else {$class='red';$status='In Use (From '.date('H:i:s',$m['starttime']).')';}
		}
		
		$op = '<td class='.$class.'>';
		$op.= '<p>'.$data['pos'].'</p>';
		$op.= '<p>'.$status.'</p>';
		$op.= '</td>';
		$template=str_replace('['.$data['pos'].']',$op,$template);
	}
?>