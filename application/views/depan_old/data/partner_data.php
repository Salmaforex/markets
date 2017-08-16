<?php 
if (   function_exists('logFile')){ logFile('view/member/data','widtdrawal_data.php','data'); };
ob_start();
//api_data
$respon=array( 'draw'=>isset($_POST['draw'])?$_POST['draw']:1);
$session=$this->param['session'];

$detail=$userlogin=$this->account->detail($session['username'],'username');
if($detail!==false){
	$respon['userlogin']=$detail;
}
else{
	$detail=$userlogin=$this->account->detail($session['username'],'accountid');
	if($detail!==false){
		$respon['userlogin']=$detail;
	}
}

$respon['time']=array( 'start'=>date('Ymd H:i:s'));

$aOrder=array(
'created','username0','username','a.email'
);
$sql="select count(id) c from `mujur_account` a
LEFT JOIN `mujur_register` r ON a.reg_id = r.reg_id
where reg_agent like '{$userlogin['username']}' or
reg_agent like '{$userlogin['accountid']}'
";
/*

agent like '{$userlogin['username']}' or
agent like '{$userlogin['accountid']}'"
*/
$dt=$this->db->query($sql)->row_array();
$respon['time']['count all']=date("Ymd H:i:s");
$respon['recordsTotal']=$dt['c'];
$respon['recordsFiltered']=$dt['c']; //karena tidak ada filter?!

$start=isset($post0['start'])?$post0['start']:0;
$limit=isset($post0['length'])?$post0['length']:11;
$data=array();
   $orders="order by created desc";
   if(isset($post0['order'][0])){
		$col=$post0['order'][0]['column'];
		$order=$post0['order'][0]['dir'];
		$col2=$post0['columns'][$col]['data'];
		if($col==3){
			$col2='a.email';
		}
		if($col==5){
			$col2='d.status';
		}
		$orders="order by {$col2} {$order}, created asc";
		
   }
   $where='1';
$search=isset($post0['search']['value'])?$post0['search']['value']:'';
if($search!=''&&strlen($search)>2){
	$where="a.username like '{$search}%'";
	$where.=" or a.email like '{$search}%'";
	 
	//$where.=" or ad.detail like '%{$search}%'";
	$sql="select count(a.id) c from mujur_account a 
	where ($where) ";
/*
and agent like '{$userlogin['username']}'
left join mujur_accountdetail ad 
	on a.username=ad.username
*/	
	$res=dbFetchOne($sql,1);
	$respon['time']['count filter']=date("Ymd H:i:s");
	$respon['sql'][]=$sql;
	$respon['recordsFiltered']=$res['c'];
}
else{
	logCreate('no search :'.$search);
	$respon['sql'][]=$sql;
}

$sql="select a.id,a.created,d.status status_document from mujur_account a 
left join mujur_accountdocument d on d.email like a.email
LEFT JOIN `mujur_register` r ON a.reg_id = r.reg_id
	where ($where) 
	and (
`reg_agent` like '{$userlogin['username']}' or
`reg_agent` like '{$userlogin['accountid']}'
)
	$orders limit $start,$limit";
/*
and agent like '{$userlogin['username']}'
left join mujur_accountdetail ad 
	on a.username=ad.username
*/	
//logCreate('sql :'.$sql);
$respon['sql'][]=$sql;
$dt=$this->db->query($sql)->result_array();
$respon['time']['query']=date("Ymd H:i:s");
foreach($dt as $row){
	$row['raw']=$detail=$this->account->detail($row['id']);
	$respon['time']['detail_'.$row['id']]=date("Ymd H:i:s");
	$row['firstname']=isset($detail['detail']['firstname'])?$detail['detail']['firstname']:'-';
	$detail['username']=isset($detail['accountid'])&&$detail['accountid']!=''?$detail['accountid'].".":$detail['username'];
	logCreate('search :'.$row['id']);
	
	unset($detail['raw'],$row['raw']);
	foreach($detail as $nm=>$val){
		$row[$nm]=$val;
	}

	$row['status']='Not Active';	
	if($row['status_document']==1)$row['status']='Active';
	if($row['status_document']==2)$row['status']='Review';
	
	$row['action']='';
	$data[]=$row;
}
$respon['time']['show data']=date("Ymd H:i:s");
$respon['data']=$data;
$respon['-']=$post0;  
//$respon[]=$userlogin;
$warning = ob_get_contents();

	if($warning!=''){
		$respon['warning']=$warning;     
	}
ob_end_clean();

unset($respon['warning'],$respon['-'],$respon['userlogin'],$respon['sql']);
$respon['time']['stop']=date("Ymd H:i:s");

if(isset($respon)){
	echo json_encode($respon);
}
else{
	echo json_encode(array());
}