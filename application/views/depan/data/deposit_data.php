<?php 
if (  function_exists('logFile')){ logFile('view/member/data','deposit_data.php','data'); };
ob_start();
//api_data
$respon=array( 'draw'=>isset($_POST['draw'])?$_POST['draw']:1);
$respon['time'][]=microtime(true);
$respon['raw']=array(date('Y-m-d H:i:s'));

$where_currency='';
$session=$this->session-> all_userdata();
$login = $this->localapi_model->token_get($session['login']);
$respon['raw2'] =array($session, $login);
if(isset($login['users']['u_type'])&&$login['users']['u_type']==7){
    $where_currency=" and currency like '{$login['users']['u_currency']}' ";
}

$sql="select count(id) c from mujur_flowlog where"
        . " types='deposit' $where_currency";

$dt=dbFetchOne($sql);
$respon['sql'][]=$sql;
//$this->db->query($sql)->row_array();
$respon['time'][]=microtime(true);
$respon['recordsTotal']=$dt['c'];
$respon['recordsFiltered']=$dt['c']; //karena tidak ada filter?!

$start=isset($post0['start'])?$post0['start']:0;
$limit=isset($post0['length'])?$post0['length']:11;
$data=array();
//===========order
$order="order by created desc";
if(isset($post0['order'][0])){
	$col= $post0['order'][0]['column'];
	$ord = $post0['order'][0]['dir'];
	if($col==0){
		$order = "order by created $ord";
	}
	if($col==1){
		$order = "order by accountid $ord";
	}
}


   $sql="select * from mujur_flowlog where 	
types='deposit' $where_currency
    $order limit $start,$limit";
$respon['sql'][]=$sql;
$dt=dbFetch($sql);
$respon['time'][]=microtime(true);
//$this->db->query($sql)->result_array();
foreach($dt as $row){
	//$row['url']=substr($row['rawUrl'],0,30);
	//$row['param']=substr($row['rawParam'],0,30);
	$row['raw']=json_decode($row['param']);
	if(isset($row['raw']->userlogin->accountid)&&$row['raw']->userlogin->accountid!='')
		$row['raw']->username=$row['raw']->userlogin->accountid.".";

	if(!isset($row['raw']->username)){
		$row['raw']->username='-';
		$row['status']=-1;
	}

	$row['action']='';
	$row['action']=$row['status']==0?'<input type="button" onclick="depositApprove('.
	  $row['id'].');" value="approved" />
	  <input type="button" onclick="depositCancel('.
	  $row['id'].');" value="Cancel" />':'--';
 
	$row['action'].=$row['status']==0?'<a target="_blank" href="'.site_url('admin/deposit/detail/'.$row['id']).'"><input type="button" value="Detail" /></a>':'';
	$status0=$row['status']==0?'open':'close';

	if($row['status']==1){
		$status0="approved";
	}
//==============accountid
	$row['accountid'] = isset($row['raw']->account )?$row['raw']->account:$row['accountid'];

	if($row['status']==-1||$row['status']==2){
		$status0="cancel";
	}

	$row['detail']=$row['raw']->namerek."<br/>".$row['raw']->bank ." (".$row['raw']->norek.")" ;
	if(isset($row['raw']->info)){
		$row['detail'].="<hr/>".$row['raw']->info;
	}
	
	$row['status']=$status0;
	//unset($row['param']);
	if($row['raw']->order1 != $row['raw']->orderDeposit* $row['raw']->rate){
		$row['raw']->order1=  $row['raw']->orderDeposit* $row['raw']->rate;
	}
	
	$row['currency_detail']=$currency=$this->forex->currency_by_code($row['currency']);
	$row['raw']->orderDeposit0 = $row['raw']->orderDeposit;
	$row['raw']->orderDeposit ='$'.number_format($row['raw']->orderDeposit,2).'<br/> '.$currency['symbol'].number_format($row['raw']->order1,2) .'<br/>Rate  '.$currency['symbol'].number_format($row['raw']->rate,2).'<BR/>'.$currency['name'];
	$row['flowid']=sprintf("%s%04s",date("ymd",strtotime($row['created']) ),$row['id']);
	$data[]=$row;
}

$respon['data']=$data;
$respon['raw'][]=array($userlogin, $post0);
$respon['time'][]=microtime(true);
//echo '<pre>'.print_r($data,1);die();
$warning = ob_get_contents();
ob_end_clean();
if($warning!=''){
    $respon['warning']=$warning;     
}
else{
    unset($respon['raw'],$respon['raw2']);
}

if(isset($respon)){ 
	echo json_encode($respon);
}
else{
	echo json_encode(array());
}