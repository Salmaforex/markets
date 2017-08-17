<?php 
$sql="select count(a.id) c from mujur_account a, mujur_register r where a.reg_id=r.reg_id and r.reg_agent !=''  ";
$data=dbFetchOne($sql);
$n=isset($_GET['p'])?$_GET['p']:0;
$max=113;
$total=$data['c'];
echo 'total:'.$data['c'];
$sql="select a.id, a.reg_id, r.reg_agent from mujur_account a, mujur_register r where a.reg_id=r.reg_id and r.reg_agent !='' 
limit $n,$max
 ";
	$res=dbFetch($sql);
	foreach($res as $row){
		$sql="update mujur_account set agent='$row[reg_agent]' where reg_id='$row[reg_id]'";
						//echo "$sql<br/>";
		dbQuery($sql);
	}

if($n < $total){
$n+=$max;
$url=current_url()."?p=$n";
}
else{
	$url=site_url('member/listApi/done')."?p=$n";
}


echo '<script>window.location.href ="'.$url.'";</script>';