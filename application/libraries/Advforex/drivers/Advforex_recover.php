<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Advforex_recover extends CI_Driver {
private $urls,$privatekey;
public $CI;
function execute($id){
	$CI =& get_instance();
	$CI->load->model('users_model');
	$CI->load->model('password_model');
	$CI->load->model('password_model');
	$detail=$CI->account_model->recoverId($id);
	if($detail===false) return false;
	$email = $detail['u_email'];
	$data=array($detail);
	$users=$CI->users_model->exist($email );

	$data['respon']=array('code'=>-1,'message'=>'unknown');
	$data['user']=$users=$CI->users_model->getDetail($email );
	
	if($users){
		$resPass= $CI->password_model->random(2);
		$pass1=$resPass[0]['password'];
		$pass2=$resPass[1]['password'];
		$pass3=rand(1234,9876);
		$input['u_password']=sha1("{$pass1}{$pass3}|{$pass2}")."|".$pass2 ;
		$data['masterpassword']=$pass1.$pass3;
		$CI->users_model->updatePass($email, $input['u_password']);
		$data['respon']=array('code'=>1,'message'=>'Password Updated');
	}
	else{}
	return $data;
	foreach($users as $user){
		if(!isset($user['id'])){
				continue;
		}
		$detail = $CI->account->detail( $user['id']);

	//	echo ("<hr/>".print_r($user ));
	//	$CI->account->noPass($user['id']);

		$sql="select password from {$CI->forex->tablePassword} order by rand() limit 2";
		$quePass=dbFetch($sql);
		$invPass=$quePass[0]['password'];
		$masterPass=$quePass[1]['password'];
		//print_r($detail);die();
		$param=array( );
		$param['accountid']=$detail['accountid'];
		$param['masterpassword']="$masterPass".($id%100) ;
		$param['investorpassword']=$invPass.($id%100)  ;
		$input = array(
				'investorpassword' => md5( $param['investorpassword'] ),
				'masterpassword'=>md5( $param['masterpassword'] )
		);
		$where = "id=".(int)$detail['id'];
		//print_r($param);die();	 
			$param['privatekey']	=$CI->forex->forexKey();
			
			$url=$CI->forex->forexUrl('update');
		//$url.="?".http_build_query($param);
			$result0= _runApi($url,$param );/*update logic*/
		//==========SAVE============
			$dtAPI=array(
				'url'=>'recover ('.$detail['accountid'] .')',
				'parameter'=>json_encode($param),
				'response'=>json_encode($result0),
				'error'=>'-1'
			);
		$sql=$CI->db->insert_string($CI->forex->tableAPI, $dtAPI);
        dbQuery($sql);
        //$CI->db->insert($CI->forex->tableAPI,$dtAPI);
			
			logCreate("update password result:".print_r($result0,1));
			$data['api'][]=array($url,$param,$result0);
			$sql = $CI->db->update_string($CI->forex->tableAccount, $input, $where);
			dbQuery($sql,1);
		
		
			$param[]=array(
				'type'=>'login',
				'data'=>array(
					array('name'=>'username', 'value'=>$detail['username'])
				),
				'recover'=>true
			);
			$param2=array( 
				'username'=>$detail['accountid'],
				'masterpassword'=>$param['masterpassword'],
				'investorpassword'=>$param['masterpassword'],
				'email'=>$detail['email']
			);
			$param2['emailAdmin']=$CI->forex->emailAdmin;
			
			$tmp=$CI->load->view('depan/email/emailAccount_view',$param2,true);
			$data['info']='Your password have been update. Please Check Your Email ('.$detail['email'].')';
		$change++;
	}
	if($change!=0){
		$data['respon']=array('code'=>266,'message'=>'Please Check you email');;
	
	}
//-----------LAKUKAN POST KE SITE UTAMA
			$source=isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'-';
			$detail='click from :('.$source.')';

		$sql="update `{$CI->account->tableAccountRecover}` 
		set  detail='$detail' , `expired`='0000-00-00'
		where id='$id'";
		dbQuery($sql,1);
        $data['api']= array('code'=>266);
	return $data; 
}

function requesting($email=''){
	$CI =& get_instance();
//====================
		$CI->load->model('forex_model','forex');
		$CI->load->model('users_model','user');
		$CI->load->model('account_model','account');
		$CI->load->model('country_model','country');
		$defaultLang="english";
		$CI->lang->load('forex', $defaultLang);
//====================

	$data=array();
	$data['user']=$users=$CI->user->exist($email);
//=======================
	$data['error']=false;
	$data['detail']=$detail=$CI->user->gets($email);
	
		if($users!==false){
			$params['recoverid']=$recoverid=$CI->account->recover($detail);
			$params['raw']=$detail;
			$params['post']=array('email'=>$email);
			
		//	$raw=$CI->load->view('depan/email/emailRecover_view',$params,true);
		}
		else{ 
			$data['error']="The Email Not Found in Our Database .  Please check your input.";
		}
	$old_data=$data;
	if($data['error']===false){
		$data['result']=array(
			'raw'=>$old_data,
			'detail'=>$detail,
			'message'=>'You Will receive an e-mail with instruction about how to recover your password in few minutes.',
			'title'=>'Your Request has been sent Successfully',
			'status'=>true,
			'recoverid'=>$recoverid
		);
		
	}
	else{
		$data['result']=array(
			'message'=>$data['error'],
			'status'=>false,
			'title'=>'Warning',
		);
		if(isset($data['code'])){
			$data['result']['code']= $data['code'];
		}else{}
	}
//==============================
	logCreate("respon forgot_data:".json_encode($data));

	if(isset($data['result'])){ 
	//	echo json_encode($data['result'] );
	}else{
	//	echo json_encode(array());
	}
//========================
	$content=false;
	$result=array(
		'data'=>$data,
		'body'=>$content,
	);
	return $result;
}

function clean(){
	$CI =& get_instance();
	$CI->account->cleanRecover();
}
	function __CONSTRUCT(){
		$CI =& get_instance();
		$CI->load->helper('api');
		//$CI->config->load('forexConfig_new', TRUE);
		$this->urls = $urls=$CI->config->item('apiForex_url' );
		$this->privatekey = $CI->config->item('privatekey' );

	}
}