<?php 
if (   function_exists('logFile')){ logFile('view/member/data','widtdrawal_data.php','data'); };
ob_start();
logCreate('start DATA = user approval');
//api_data
$respon=array( 'draw'=>isset($_POST['draw'])?$_POST['draw']:1);
$aOrder=array(
'created','username0','username','email'
);

$sql="select count(a.id) c from `mujur_account` a 
join mujur_accountdocument d on d.email = a.email";
$dt=$this->db->query($sql)->row_array();
$respon['recordsTotal']=$dt['c'];
$respon['recordsFiltered']=$dt['c']; //karena tidak ada filter?!
logCreate('respon:'.json_encode($respon));

$start=isset($post0['start'])?$post0['start']:0;
$limit=isset($post0['length'])?$post0['length']:11;
$data=array();
   $orders="order by d.modified desc";
   if(isset($post0['order'][0])){
		$col=$post0['order'][0]['column'];
		$order=$post0['order'][0]['dir'];
		$col2=$post0['columns'][$col]['data'];
		if($col==0){
			$col2='d.modified';
		}
		if($col==2){
			$col2='a.accountid';
		}
		if($col==3){
			$col2='d.email';
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
	join mujur_accountdocument d on d.email like a.email
	where $where";
/*
left join mujur_accountdetail ad 
	on a.username=ad.username
*/


	$res=dbFetchOne($sql,1);
	$respon['sql'][]=$sql;
	$respon['recordsFiltered']=$res['c'];
	logCreate('respon:'.json_encode($respon));
}
else{
	logCreate('no search :'.$search);
}

$sql="select a.id,a.created,d.status status_document, d.email main_email, d.id accdoc_id
from mujur_account a 
join mujur_accountdocument d on d.email = a.email
	where $where 
	$orders limit $start,$limit";
/*
left join mujur_accountdetail ad 
	on a.username=ad.username
*/	
logCreate('sql :'.$sql);
$respon['sql'][]=$sql;
$dt=$this->db->query($sql)->result_array();
logCreate('total user approval:'.count($dt)); //exit();
foreach($dt as $row){
	$row['raw']=$detail=$this->account->detail($row['id']);
	$row['firstname']=isset($detail['detail']['firstname'])?$detail['detail']['firstname']:'-';
	
	logCreate('search :'.$row['id']);
	
	unset($detail['raw']);
	foreach($detail as $nm=>$val){ $row[$nm]=$val; }
	$row['status']='Not Active';	
	if($row['status_document']==1)$row['status']='Active';
	if($row['status_document']==2)$row['status']='Review';
	$row['username']=$row['accountid'].".";
	$row['action']='';
	$row['email']=$row['main_email'].".";
	$data[]=$row;
}

$respon['data']=$data;
$respon['-']=$post0;  
$warning = ob_get_contents();
ob_end_clean();
if($warning!=''){
	$respon['warning']=$warning;     
}

if(isset($respon)){ 
	echo json_encode($respon);
}else{
	echo json_encode(array());
}