<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Advforex_user extends CI_Driver {
private $urls,$privatekey;
public $CI;
	function detail($param){
	$CI =& get_instance();
		$respon=array( 'post'=>$_POST );
		$respon['time']['begin' ]=microtime(true);
		$respon['param']=$param;
		$post0=$param['post0'];
		if(isset($post0['email'])&&$post0['email']!=''){
			$respon['null_user']=$CI->users_model->addNullAccount($post0['email']);
			$respon['time']['null user' ]=microtime(true);
		}
		$html='';
		
		//return $respon;
		/*
		$userlogin=isset($param['userlogin'])?$param['userlogin']:false;
		$session=isset($param['session'])?$param['session']:false;
		$detail=$userlogin=$CI->account_model->detail($session['username'],'username');
			if($detail!==false){
				$respon['userlogin']=$detail;
			}
			else{
				$detail=$userlogin=$CI->account_model->detail($session['username'],'accountid');
				if($detail!==false){
					$respon['userlogin']=$detail;
				}
			}
		*/
		
		$show=array();
		if(isset($post0['id'])){
			$detail=false;//$CI->account_model->detail($post0['id']);
			$respon['time']['no id' ]=microtime(true);
		}
		elseif(isset($post0['accountid'])){
			$detail=$CI->account_model->detail_basic($post0['accountid'],'accountid');
			$respon['time']['accid:'.$post0['accountid'] ]=microtime(true);
		}
		else{
			$detail=false;
		}

		$show['email']=$email=false;
		//$respon['account']=$detail;
		if($detail){
			$show['TYPE']=$detail['type'];
			$show['accountid']=$detail['username'];
			$show['email']=$email=$detail['email'];
			$respon['account_detail']=$detail;
		}

		//return $respon;
		if(isset($post0['email'])&&$post0['email']!=''&&$email==false){
			$email=$post0['email'];
		}
		
		if(isset($email)&&$email!=null){
			$respon['email_1']=$email;
			$respon['email']=$email;
			$CI->users_model->addNullDetail($email);
			$respon['time']['null user detail' ]=microtime(true);
			$basic=$CI->users_model->gets($email);
			$respon['time']['get user email' ]=microtime(true);
			$detail=$CI->users_model->getDetail($email);
			$respon['time']['get user detail' ]=microtime(true);
		}
		else{
			$respon['email_2']=$email;
			$basic=$CI->users_model->gets($post0['id'],'u_id');
			$respon['time']['get user id' ]=microtime(true);
			$email=$basic['u_email'];
			$respon['user_basic']=$basic;
			$CI->users_model->addNullDetail($email);
			$respon['time']['null user detail' ]=microtime(true);
			$detail=$CI->users_model->getDetail($email);
			$respon['time']['get user detail' ]=microtime(true);
			$respon['email']=$email;
		}

		$respon['user_detail']=$detail;
		
		$users_id=$detail['users']['u_id'];

		
		$show['Nama Lengkap']=isset($detail['name'])?$detail['name']:'';
	//	$show['Nama Lengkap']=isset($detail['firstname'])?$detail['firstname']:'';
	//	$show['Nama Lengkap'].=isset($detail['lastname'])?' '.$detail['lastname']:'';
		if(trim($show['Nama Lengkap'])=='')$show['Nama Lengkap']='???';

		if(true){
			if($show['email']=='')
				$show['email']=isset($email)?$email:'';

			$show['Alamat']=isset($detail['address'])?$detail['address']:'';
			if(trim($show['Alamat'])=='')$show['Alamat']='???';

			$show['Bank']=isset($detail['bank'])?$detail['bank']:'???';
			$show['No Rekening']=isset($detail['bank_norek'])?$detail['bank_norek']:'???';
			//====status
			$status='Not Active';
			
			if(isset($detail['document']['udoc_status'])){	
				if($detail['document']['udoc_status']==1)$status ='Active';
				if($detail['document']['udoc_status']==2)$status ='Review';
				$status.=anchor_popup( site_url('admin/updateDocument/active/'.$users_id),'<button type="button">Active</button>');;
				$status.=anchor_popup(site_url('admin/updateDocument/review/'.$users_id), '<button type="button">Review</button>');
				$status.= anchor_popup(site_url('admin/show_upload/'. $users_id),'Lihat Dokumen');
			}
			else{
			//	$status = '<pre>'.print_r($detail,1).'</pre>';
			}
			
			$show['Status']=$status;
			if($show['email']!=''&&isset($detail['username']) ){
				$apiRes=false; 			//$CI-> forex_model->apiAccount($detail['username']);
			}
			else{
				$apiRes=false;
			}
			$respon['time']['api res' ]=microtime(true);
			$respon['email']=isset($apiRes['email'])?count($apiRes['email']):null;

		}
		else{
			$apiRes=false;
			//'$'. number_format($detail['balance'],2);
		}
		
		$respon['api_res']=$apiRes;
		$show['Balance']='in progress';

		//$respon['api2']=$apiRes;
		if(true){
			$show['list email']='';
			unset($apiRes);
				$n=0;
			//	$show['xxx']=json_encode( $apiRes['email']);
				$res =$CI-> forex_model->list_email($email, 'to','id,subject,created');
				foreach($res as $row){
					$url0=base_url('admin/send_email/'.$row['id']);
					$n++;
					$subject=$row['subject'];
					$show['send email '.$n]=anchor_popup($url0, $subject). " (".$row['created'].")" ;
				}

		}
		else{
			
		}

		$respon['show']=isset($show)?$show:array();
		$html0='<h3>Detail</h3>';
		$html0.="<table border=1 width=400 class='table'>";

		foreach($show as $nm=>$val){
		$html0.="<tr>
			<td>{$nm}</td><td>:</td><td>&nbsp;{$val}</td>
		</tr>";
		}
		$html0.='</table>';
		$respon['title']='Detail User';


		$html = ob_get_contents();
		ob_end_clean();

		$respon['html']="<div style='min-height:600px;padding:30px;border:1px solid blue;margin:2px'>".$html0.$html."</div>";

		$respon['status']=true;
		//unset( $respon['raw'], $respon['post'], $respon['userlogin'], $respon['show'], $respon['email'] );

		$respon_1 = array(
		'title'=>$respon['title'],
		'html'=>$respon['html'],
		'status'=>$respon['status']
		);
		if(isset($respon_1)){ 
			return $respon;
		}
		else{
			return array();
		}

	}
	
	function detail_old($param){
		$html='';
		$post0=$param['post0'];
		$userlogin=isset($param['userlogin'])?$param['userlogin']:false;
		$session=isset($param['session'])?$param['session']:false;
		$detail=$userlogin=$CI->account_model->detail($session['username'],'username');
			if($detail!==false){
				$respon['userlogin']=$detail;
			}
			else{
				$detail=$userlogin=$CI->account_model->detail($session['username'],'accountid');
				if($detail!==false){
					$respon['userlogin']=$detail;
				}
			}
		
		$detail=$CI->account_model->detail($post0['id']);
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
			$apiRes=$CI->forex_model->apiAccount($post0['id']);
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
		$html0='<h3>Detail</h3>';
		$html0.="<table border=1 width=400 class='table'>";

		foreach($show as $nm=>$val){
		$html0.="<tr>
			<td>{$nm}</td><td>:</td><td>&nbsp;{$val}</td>
		</tr>";
		}
		$html0.='</table>';
		$respon['title']='Detail User';


		$html = ob_get_contents();
		ob_end_clean();

		$respon['html']="<div style='max-height:400px;width:800px;overflow:auto;padding:30px;border:1px solid blue;margin:2px'>".$html0.$html."</div>";

		$respon['status']=true;
		//unset( $respon['raw'], $respon['post'], $respon['userlogin'], $respon['show'], $respon['email'] );

		if(isset($respon)){ 
			return $respon;
		}
		else{
			return array();
		}

	}

    function __CONSTRUCT(){
	$CI =& get_instance();
	$CI->load->helper('api');
	//$CI->config->load('forexConfig_new', TRUE);
    $this->urls = $urls=$CI->config->item('apiForex_url' );
    $this->privatekey = $CI->config->item('privatekey' );
	$CI->load->model('account_model');
	$CI->load->model('forex_model');
    }
	
	function execute($raw_data){
	$CI =& get_instance();
/*----------*/
		$post=$post0=$raw_data['post0'];
		$times=array(microtime());
		ob_start();
		logCreate('start DATA = user approval');
		//api_data
		$respon=array( 
		'raw'=>$raw_data,
		'draw'=>isset($post['draw'])?$post['draw']:1);
		$aOrder=array(
		'created','username0','username','email'
		);

		$sql="select count(u.u_id) c from `mujur_users` u 
		left join mujur_usersdocument d on u.u_email = d.udoc_email";
		$dt=dbFetchOne($sql);
		$times['count 0']=microtime();
                
		//$this->db->query($sql)->row_array();
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
					$col2='u.u_modified';
				}
				if($col==2){
					$col2='u.u_email';
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
			$where="u.u_email like '{$search}%'";
			//$where.=" or a.email like '{$search}%'";
			//$where.=" or ad.detail like '%{$search}%'";
			$sql="select count(u_id) c from mujur_users u 
			left join mujur_usersdetail d on u_email like ud_email
			where $where";
		/*
		left join mujur_accountdetail ad 
			on a.username=ad.username
		*/

			$res=dbFetchOne($sql,1);
			$times['count 2a']=microtime();
			$respon['sql'][]=$sql;
			$respon['recordsFiltered']=$res['c'];
			logCreate('respon:'.json_encode($respon));
		}
		else{
			logCreate('no search :'.$search);
			$times['count 2b']=microtime();
		}

		$sql="select u.u_id id, u_modified created, d.udoc_status status_document, u_email main_email, '-'
		from `mujur_users` u 
		left join mujur_usersdocument d on u.u_email = d.udoc_email
		where $where 

			$orders
		limit $start,$limit";
		/*
                 * 		group by u.u_email
		left join mujur_accountdetail ad 
			on a.username=ad.username
		*/	
		logCreate('sql :'.$sql);
		$respon['sql'][]=$sql;
		$dt=dbFetch($sql,0);
		$times['query']=microtime();
		//$this->db->query($sql)->result_array();
		logCreate('total user approval:'.count($dt)); //exit();
		foreach($dt as $num=>$row){			
			logCreate('search :'.$row['id']);
		//	var_dump($row);exit();
			$row['raw']=$detail=$CI->users_model->getDetail($row['main_email']);
			$times['cari '.$num.' '.$row['main_email']]=microtime();
		//echo_r($detail);exit();
			//detail($row['id']);
			$row['name']=isset($row['name'])?$row['name']:'user forex';
			$row['modified']=isset($detail['user']['u_modified'])?$detail['user']['u_modified']:'-';
	
			unset($detail['raw']);
			foreach($detail as $nm=>$val){ $row[$nm]=$val; }
			$row['status']='Not Active';	
			if($row['status_document']==1)$row['status']='Active';
			if($row['status_document']==2)$row['status']='Review';
			$row['usertotal']= "total :".$detail['totalAccount'];//$row['accountid'].".";
			$row['action']='';
			$row['email']=$row['main_email'].".";
			$account_type ='member type '.$row['users']['u_type'];
			if($row['users']['u_type']==2) $account_type='ADMIN';
			if($row['users']['u_type']==3) $account_type='BACKEND';
			if($row['users']['u_type']==10) $account_type='PARTNER';
			$row['accounttype']=$row['users']['u_type']==1?'MEMBER':'OTHER ('.$account_type.')';
			$data[]=$row;
		}

		$respon['data']=$data;
		$respon['-']=$post0;  
		$warning = ob_get_contents();
		ob_end_clean();
		if($warning!=''){
			$respon['warning']=$warning;     
		}
		logCreate('times advforex user execute:'.json_encode($times));
		if(isset($respon)){
			$respon['times']=$times;
			return $respon;
		}
		else{
			return array();
		}
	}
}