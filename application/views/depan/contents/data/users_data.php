<?php 
if (   function_exists('logFile')){ logFile('view/member/data','widtdrawal_data.php','data'); };
ob_start();
//api_data
$respon=array( 'draw'=>isset($_POST['draw'])?$_POST['draw']:1);
$aOrder=array(
'created','username0','username','email'
);
$sql="select count(id) c from `mujur_account`";
$dt=$this->db->query($sql)->row_array();
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

		if($col==5){
			$col2='d.status';
		}
 
		$orders="order by {$col2} {$order}, created asc";
		
   }
   $where='1';
$search=isset($post0['search']['value'])?$post0['search']['value']:'';

if($search!=''&&strlen($search)>3){
	$where="a.username like '{$search}%'";
	$where.="or a.accountid like '{$search}%'";
	$where.=" or a.email like '{$search}%'";
	//$where.=" or ad.detail like '%{$search}%'";
	$sql="select count(a.id) c from mujur_account a 
	where $where";
/*
left join mujur_accountdetail ad 
	on a.username=ad.username
*/	
	$res=dbFetchOne($sql,1);
	$respon['sql'][]=$sql;
	$respon['recordsFiltered']=$res['c'];
}
else{
	logCreate('no search :'.$search);
}

$sql="select a.id,a.created,d.status status_document from mujur_account a 
left join mujur_accountdocument d on d.email like a.email
	where $where 
	$orders limit $start,$limit";
/*
left join mujur_accountdetail ad 
	on a.username=ad.username
*/	
//logCreate('sql :'.$sql);
$respon['sql'][]=$sql;
$dt=$this->db->query($sql)->result_array();
foreach($dt as $row){
	$row['raw']=$detail=$this->account->detail($row['id']);
	$row['firstname']=isset($detail['detail']['firstname'])?$detail['detail']['firstname']:'-';
	$full_name=isset($detail['detail']['firstname'])?$detail['detail']['firstname']:'';
	$full_name.=" ". (isset($detail['detail']['lastname'])?$detail['detail']['lastname']:'');
	logCreate('search :'.$row['id']);
	
	unset($detail['raw']);
	foreach($detail as $nm=>$val){ $row[$nm]=$val; }
	$row['status']='Not Active';	
	if($row['status_document']==1)$row['status']='Active';
	if($row['status_document']==2)$row['status']='Review';
	
	$row['action']='';
	$row['full_name']=$full_name;
//accountid
	$row['username']=$row['accountid']!=0?$row['accountid'].".":$row['username'];
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
}
else{
	echo json_encode(array());
}