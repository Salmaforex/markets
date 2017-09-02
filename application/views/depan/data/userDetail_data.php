<?php 
$respon=array( 'post'=>$_POST );
$html='';
$session=$this->param['session'];
/*
		$detail=$userlogin=$this->account->detail($session['username'],'username');
		if($detail!==false){
			$respon['userlogin']=$detail;
		}
*/
/*
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
*/
//========NANTI DIPERBAIKI

$user_id=$post0['id'];
//print_r($user_id);die();
$user0=  _localApi('users','get_id',array($user_id));
$respon['raw']=$user0;
$detail=isset($user0['data'])?$user0['data']:array();

//$this->user_model->gets($post0['id'],'u_id');
//$email=$user0['u_email'];
//$detail=$this->user_model->getDetail($email);
//account->detail($post0['id']);
//$respon['raw']=$detail;

$show=array();
//$show['raw']='<pre>'.print_r($detail,1)."</pre>";
$show['TYPE']=isset($detail['accounttype'])?$detail['accounttype']:'';
$show['username']=$email=$detail['email'];

$this->users_model->addNullDetail( $detail['email']);

$show['Nama Lengkap']=isset($detail['firstname'])?$detail['firstname']:'';
$show['Nama Lengkap'].=isset($detail['lastname'])?' '.$detail['lastname']:'';
if(trim($show['Nama Lengkap'])=='')
	$show['Nama Lengkap']='???';
if(!isset($userlogin['type']))$userlogin['type']=false;
if($userlogin['type']!='agent'){
	$show['Alamat']=isset($detail['address'])?$detail['address']:'';
	if(trim($show['Alamat'])=='')$show['Alamat']='???';

	$show['Bank']=isset($detail['bank'])?$detail['bank']:'???';
	$show['No Rekening']=isset($detail['bank_norek'])?$detail['bank_norek']:'???';
	//====status
	$status='Not Active (upload document)';
	if(isset($detail['document']['status'])){	
		if($detail['document']['status']==1)$status ='Active';
		if($detail['document']['status']==2)$status ='Review';
	}
		$status.=anchor_popup(site_url('admin/updateDocument/active/'.$post0['id']),'<button type="button">Active</button>');;
		$status.=anchor_popup(site_url('admin/updateDocument/review/'.$post0['id']),'<button type="button">Review</button>');
		$status.= anchor_popup(site_url('admin/show_upload/'.$post0['id']),'Lihat Dokumen');

	$show['Status']=$status;
}
else{
	$show['Balance']='$'. number_format($detail['balance'],2);
}
/*
if($show['email']!=''){
	$apiRes=$this->forex->apiAccount($post0['id']);
}
else{
	$apiRes=false;
}
*/ 
$respon['email']=isset($apiRes['email'])?count($apiRes['email']):null;
//$respon['api2']=$apiRes;
if($userlogin['type']!='agent'){
	if(isset($apiRes['email'])&&is_array($apiRes['email'])){
		$n=0;
	//	$show['xxx']=json_encode( $apiRes['email']);
		
		foreach($apiRes['email'] as $dataApi){
			$url0=base_url('member/send_email/api/'.$dataApi['id']);
			$n++;
			$detail=json_decode($dataApi['parameter'],true);
			$subject=$detail[0];
			$message=$detail[2];
			$headers=$detail[1];
			$show['send email '.$n]=anchor_popup($url0, $subject). " (".$dataApi['created'].")" ;
		}

	}else{}
	
}
else{
	
}

$respon['show']=isset($show)?$show:array();
?><h3>Detail</h3>

<form method="POST" action="<?=site_url('admin/update_password');?>">'
<table border=1 width=400 class='table'>
<tr>
	<td>Password</td>
	<td>:</td>
	<td>
	<input type=password name='password' />
	<input type=hidden name='email' value='<?=$email;?>' />
	<button>Ganti Password</button>
	</td>
</tr>
</table>
</form>
<hr/>
<table border=1 width=400 class='table'>
<?php 
foreach($show as $nm=>$val){?>
<tr>
	<td><?=$nm;?></td><td>:</td><td>&nbsp;<?=$val;?></td>
</tr>
<?php 
}
?>
</table>
<?php 
$respon['title']='Detail User';


$html = ob_get_contents();
ob_end_clean();

$respon['html']="<div style='max-height:400px;width:800px;overflow:auto;padding:30px;border:1px solid blue;margin:2px'>".$html."</div>";

$respon['status']=true;
//unset( $respon['raw'], $respon['post'], $respon['userlogin'], $respon['show'], $respon['email'] );
$respon_all['data']=$respon;
if(isset($respon)){ 
	echo json_encode($respon_all);
}
else{
	echo json_encode(array());
}
exit();