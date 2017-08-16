<?php 
if (   function_exists('logFile')){ logFile('view/member/data','userApproval_data.php','data'); };
ob_start();

logCreate('start DATA = user approval');
//api_data
$respon=array( 'draw'=>isset($_POST['draw'])?$_POST['draw']:1);
$respon['debug']='view';
$aOrder=array(
'created','username0','username','email'
);

$respon['docUpdate']=$this->users_model->addNullDocument();
$sql="select count(u.u_id) c from 
`mujur_users` u
left join mujur_usersdocument ud 
on u.u_email = ud.udoc_email";
$respon['sql'][]=$sql;
$dt=dbFetchOne($sql);
//$this->db->query($sql)->row_array();
$respon['recordsTotal']=$dt['c'];
$respon['recordsFiltered']=$dt['c']; //karena tidak ada filter?!
logCreate('respon:'.json_encode($respon));

$start=isset($post0['start'])?$post0['start']:0;
$limit=isset($post0['length'])?$post0['length']:11;
$data=array();
   $orders="order by ud.modified desc";
   if(isset($post0['order'][0])){
		$col=$post0['order'][0]['column'];
		$order=$post0['order'][0]['dir'];
		$col2=$post0['columns'][$col]['data'];
		if($col==0){
			$col2='ud.modified';
		}
		if($col==2){
			$col2='u.u_email';
		}
		if($col==3){
			$col2='ud.ud_email';
		}
		if($col==5){
			$col2='ud_status';
		}
		$orders="order by {$col2} {$order} ";
		
   }
   $where='1';
$search=isset($post0['search']['value'])?$post0['search']['value']:'';
if($search!=''&&strlen($search)>2){
	$where="u.u_email like '{$search}%'";
	//$where.=" or a.email like '{$search}%'";
	//$where.=" or ad.detail like '%{$search}%'";
	$sql="select count(u.u_id) c 
	from mujur_users u 
	left join mujur_usersdocument ud 
	on ud.udoc_email like u.u_email
	where $where";
/*
left join mujur_usersdetail ad 
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

$sql="select u.u_id id, ud.`modified`,ud.`udoc_status` status_document, u.`u_email` main_email, ud.`id` doc_id
from 
mujur_users u 
left join `mujur_usersdocument` ud 
on ud.udoc_email = u.u_email
where $where 
	$orders limit $start,$limit";
/*
left join mujur_usersdetail ad 
	on a.username=ad.username
*/	
logCreate('sql :'.$sql);
$respon['sql'][]=$sql;
$dt=dbFetch($sql);
//$this->db->query($sql)->result_array();
logCreate('total user approval:'.count($dt)); //exit();
foreach($dt as $row){
	$row['raw']=$detail=$this->users_model->getDetail($row['main_email']);
	$row['firstname']=isset($detail['firstname'])?$detail['firstname']:'-';
	
	logCreate('search :'.$row['id']);
	
	unset($detail['raw']);
	foreach($detail as $nm=>$val){ $row[$nm]=$val; }
	$row['status']='Not Active';	
	if($row['status_document']==1)$row['status']='Active';
	if($row['status_document']==2)$row['status']='Review';
	$row['username']=$row['accountid'].".";
	$row['action']='';
	$row['email']=$row['main_email'].".";
	$row['created']=$row['modified'];
	$row['accounttype']=$row['users']['u_type']==1?'MEMBER':'OTHER';
	$data[]=$row;
}

$respon['data']=$data;
$respon['-']=$post0;  
$warning = ob_get_contents();
ob_end_clean();
if($warning!=''){
	$respon['warning']=$warning;     
}

//unset($respon['sql'], );
if(isset($respon)){ 
	echo json_encode($respon);
}
else{
	echo json_encode(array());
}