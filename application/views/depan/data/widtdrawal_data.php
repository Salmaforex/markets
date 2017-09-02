<?php 
if (   function_exists('logFile')){ logFile('view/member/data','widtdrawal_data.php','data'); };
ob_start();
//api_data
$respon=array( 'draw'=>isset($_POST['draw'])?$_POST['draw']:1);
$respon['time'][]=microtime(true);

$where_currency='';
if($userlogin['u_type']==7){
    $where_currency=" and currency like '{$userlogin['u_currency']}' ";
}

$sql="select count(id) c from mujur_flowlog where
types='widtdrawal' $where_currency";

$dt=dbFetchOne($sql);
$respon['time'][]=microtime(true);
//$this->db->query($sql)->row_array();
$respon['recordsTotal']=$dt['c'];
$respon['recordsFiltered']=$dt['c']; //karena tidak ada filter?!

$start=isset($post0['start'])?$post0['start']:0;
$limit=isset($post0['length'])?$post0['length']:11;
$data=array();
   $order="order by created desc";
$sql="select * from mujur_flowlog where 	
types='widtdrawal' $where_currency $order limit $start,$limit";

$dt=dbFetch($sql);//$this->db->query($sql)->result_array();
$respon['time'][]=microtime(true);

foreach($dt as $row){
	//$row['url']=substr($row['rawUrl'],0,30);
	//$row['param']=substr($row['rawParam'],0,30);
	$row['raw']=json_decode($row['param']);
//========account id
	$row['accountid'] = isset($row['raw']->account )?$row['raw']->account:$row['accountid'];

	if(!isset($row['raw']->username)){
		$row['raw']->username='-';
		$row['status']=-1;
	}
	if(isset($row['raw']->userlogin->accountid)&&$row['raw']->userlogin->accountid!='')
		$row['raw']->username=$row['raw']->userlogin->accountid.".";
	
	$row['action']=$row['status']==0?'<input type="button" onclick="widtdrawalApprove('.
	$row['id'].');" value="approved" />
	  <input 
	type="button" onclick="widtdrawalCancel('.
	  $row['id'].');" value="Cancel" />':'--';
//=========ACTION YG DIGUNAKAN=========	  
	$row['action'].=$row['status']==0?'<a target="_blank" href="'.site_url('admin/widthdrawal/detail/'.$row['id']).'"><input type="button" value="Detail" /></a>':'';
	  
	$status0=$row['status']==0?'open':'close';
	if($row['status']==1){
		$status0="approved";
	}
	
	if($row['status'] < 0||$row['status']==2){
		$status0="cancel";
	}
	$row['detail']=$row['raw']->namerek."<br/>".$row['raw']->bank ." (".$row['raw']->norek.")" ;
	
	if(isset($row['raw']->info)){
		$row['detail'].="<hr/>".$row['raw']->info;
	}

	$row['status']=$status0;
	unset($row['param']);
	if($row['raw']->order1 != $row['raw']->orderWidtdrawal* $row['raw']->rate){
		$row['raw']->order1=  $row['raw']->orderWidtdrawal* $row['raw']->rate;
	}
	//$row['raw']->orderWidtdrawal0 = $row['raw']->orderWidtdrawal;
	//$row['raw']->orderWidtdrawal ='Rp'.number_format($row['raw']->order1,2).'<br/>$'.number_format($row['raw']->orderWidtdrawal,2).'<br/>Rate Rp'.number_format($row['raw']->rate,2); 
	//$row['flowid']=sprintf("%s%04s",date("ymd",strtotime($row['created']) ),$row['id']);
        //=======new========
        $row['currency_detail']=$currency=$this->forex->currency_by_code($row['currency']);
	$row['raw']->orderWidtdrawal0 = $row['raw']->orderWidtdrawal;
	$row['raw']->orderWidtdrawal ='$'.number_format($row['raw']->orderWidtdrawal,2).'<br/> '.$currency['symbol'].number_format($row['raw']->order1,2) .'<br/>Rate  '.$currency['symbol'].number_format($row['raw']->rate,2).'<BR/>'.$currency['name'];
	$row['flowid']=sprintf("%s%04s",date("ymd",strtotime($row['created']) ),$row['id']);
        
	$data[]=$row;
}

$respon['data']=$data;
$respon['-']=$post0; 
$respon['time'][]=microtime(true);
//echo '<pre>'.print_r($data,1);die();
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