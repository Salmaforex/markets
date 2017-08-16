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

$detail=$this->account->detail($post0['id']);
$respon['raw']=$detail;

$show=array();
$show['TYPE']=$detail['accounttype'];
$show['username']=$detail['username'];
$show['email']=$detail['email'];
$show['Nama Lengkap']=isset($detail['detail']['firstname'])?$detail['detail']['firstname']:'';
$show['Nama Lengkap'].=isset($detail['detail']['lastname'])?' '.$detail['detail']['lastname']:'';
if(trim($show['Nama Lengkap'])=='')$show['Nama Lengkap']='???';

if($userlogin['type']!='agent'){
	$show['Alamat']=isset($detail['detail']['address'])?$detail['detail']['address']:'';
	if(trim($show['Alamat'])=='')$show['Alamat']='???';

	$show['Bank']=isset($detail['detail']['bank'])?$detail['detail']['bank']:'???';
	$show['No Rekening']=isset($detail['detail']['bank_norek'])?$detail['detail']['bank_norek']:'???';
	//====status
	$status='Not Active';
		if(isset($detail['document']['status'])){	
		if($detail['document']['status']==1)$status ='Active';
		if($detail['document']['status']==2)$status ='Review';
		$status.=anchor_popup(site_url('member/updateDocument/active/'.$post0['id']),'<button type="button">Active</button>');;
		$status.=anchor_popup(site_url('member/updateDocument/review/'.$post0['id']),'<button type="button">Review</button>');
		$status.= anchor_popup(site_url('member/show_upload/'.$post0['id']),'Lihat Dokumen');
		}
	$show['Status']=$status;
}
else{
	$show['Balance']='$'. number_format($detail['balance'],2);
}

if($show['email']!=''){
	$apiRes=$this->forex->apiAccount($post0['id']);
}
else{
	$apiRes=false;
}
 
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
unset( $respon['raw'], $respon['post'], $respon['userlogin'], $respon['show'], $respon['email'] );

if(isset($respon)){ 
	echo json_encode($respon);
}
else{
	echo json_encode(array());
}
exit();