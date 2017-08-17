<?php
if (   function_exists('logFile')){ logFile('view/member/data','widtdrawal_data.php','data'); };
ob_start();
//$user_id=2325;
$user_id= $userlogin['id'];
$raw=$this->forex->flowMember($user_id);
echo 'start:'.$user_id;
$response=array('c'=>$raw['count']);
$response['raw']=$raw;
$table=array();
//print_r($data);
foreach($raw['data'] as $row){
	if($row['types']=='deposit'){
		$trans=isset($row['param']['orderDeposit'])?$row['param']['orderDeposit']:0;
	}
	if($row['types']=='widtdrawal'){
		$trans=isset($row['param']['orderWidtdrawal'])?$row['param']['orderWidtdrawal']:0;
	}
	$rate=isset($row['param']['rate'])?$row['param']['rate']:0;
	$total=$trans*$rate;
	$status=isset($row['status'])?$row['status']:null;
	switch($status){
		case 0: $statusTitle='Waiting'; break;
		case 1: $statusTitle='Disetujui'; break;
		case 2: $statusTitle='Ditolak'; break;
		default: $statusTitle='tidak diketahui';
	}
	
	
	$tbl=array(
		$row['created'],
		$row['user']['id'],
		$row['user']['accountid'],
		$row['user']['username'],
		$row['types'],
		number_format($trans),
		number_format($rate),
		number_format($total),
		$statusTitle ,
		$row
	);

	$table[]=$tbl;
}

$response['data']=$table;
$warning = ob_get_contents();
ob_end_clean();
if($warning!=''){
	$response['warning']=$warning;     
}
unset($response['raw']);
echo json_encode($response);