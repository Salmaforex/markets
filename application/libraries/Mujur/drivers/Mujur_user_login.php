<?php
//Advforex_user_login.php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mujur_user_login extends CI_Driver {
private $urls,$privatekey;
public $CI;
	function __CONSTRUCT(){
		$CI =& get_instance();
		$CI->load->helper('api');
		//$CI->config->load('forexConfig_new', TRUE);
		$CI->load->helper('formtable');
		$CI->load->helper('form');
		$CI->load->library('session');
	}

	function execute($param){
	$CI =& get_instance();
//==============START
		logCreate('Advforex_user_login |start');
	//	echo_r($CI->param);exit;
		$session=$CI->session->userdata();
		if(!isset($session['username'])||!isset($session['password'])){
			$result=array(
					'session'=>$session,
					'error'=>'NO_VALID_SESSION' ,
					'url'=>site_url('login'),
					
				);
				return $result;
		}
		
		$res= _localApi( 'users','login_check',array($session['username'],$session['password']));
		$detail=isset($res['data']['valid'])?$res['data']['valid']:false;
		if($detail==false){
			logCreate('Advforex_user_login |no username/accid:'.$session['username'],'error');
		//	redirect("login");
			$result=array(
				'users'=>$detail,
				'session'=>$session,
				'error'=>'NO_USERNAME',
				'url'=>site_url('login')
			);
			return $result;
		}
		
		$res= _localApi( 'users','nullDetail',array($session['username']));
		$session['expire']=strtotime("+30 minutes");

		$array=array( 
			'username'=>$session['username'],
			'password'=>($session['password']),
			'expire'=>$session['expire']
		);
		
		$CI->session->set_userdata($array);
		$res_user_detail= _localApi( 'users','detail',array($session['username']));
		$detail=isset($res_user_detail['data'])?$res_user_detail['data']:array();
		//echo_r($res_user_detail);exit;
//=======balance
		if(isset($session['accountid'])){
			$accountid=$session['accountid'];
			//$account_detail = $CI->account->detail($accountid,'accountid') ;
			$detail['balance']=isset($session['balance'])?$session['balance']:0;		
			$detail['summary']=isset($session['summary'])?$session['summary']:0;		
			//isset($account_detail['balance']['Balance'])?$account_detail['balance']['Balance'] :array();

			$detail['accountid']=$accountid;

		}
/*	
		if(isset($detail['typeMember'])&&$detail['typeMember']=='patners'){
			logCreate('Advforex_user_login |partners detected');
			redirect('partner/index/reload');
			
		}
*/
		//$detail['name']='';
		//$CI->param['userlogin']=$userlogin=$detail;
		$email = isset($userlogin['email'])?$userlogin['email']:'';
		if(isset($detail['users']['u_mastercode'])&&$detail['users']['u_mastercode']!=''){
			//OK
		}
		else{
			$CI->users_model->random_mastercode( $email  );
			if($page !='edit_master_password'){
				logCreate('Advforex_user_login |no master code');
				$result=array(
					'users'=>$detail,
					'session'=>$session,
					'error'=>'NO_MASTERCODE',
					'url'=>site_url('login'),
					'msg'=>'Master Code not Valid'
				);
				return $result;
				$CI->session->set_flashdata('notif',array('status'=>false,'msg'=>'Master Code not Valid'));
			//	redirect('member/edit_master_password');
			}
		}
/*
		if(isset($detail['name'])&&trim($detail['name'])!=''){
			//OK
		}
		else{
			if($page =='edit' || $page =='edit_master_password'){
				//ok
			}
			else{
				logCreate('Advforex_user_login |no username');
		//		echo_r($detail);exit;
				$CI->session->set_flashdata('notif',array('status'=>false,'msg'=>'Input Your Name'));
				js_goto( base_url('member/edit').'?r=input_your_name');exit;
			}
		}
*/
		$result=array(
				'users'=>$detail,
				'session'=>$session,
				'error'=>FALSE,
			);
		return $result;
		$res0=  $CI->account->get_by_field($session['username'],'email');
		//==========DETAIL ALL
		$res=array();$agent=false;
		if(defined('LOCAL')){ $agent=9999; }
		if(is_array($res0)){
			foreach($res0 as $row){
				if(isset($row['id'])){
					$data_account=$CI->account->gets($row['id'],'id');
					if(isset($data_account['agent'])&&$data_account['agent']!=''){
						$agent=$data_account['agent'];
					}
					$res[]=$data_account;
				}
				
			}
			
		}

		//$res0= _localApi( 'account','lists',array($session['username']));
		//echo_r($session);echo_r($res); die();
		//$detail=isset($res['data'])?$res['data']:array();
		$CI->param['accounts']=$res;
		$CI->param['agent_default']=$agent;
//==============END
	}
}