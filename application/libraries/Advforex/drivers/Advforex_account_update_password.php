<?php //Advforex_update_detail
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Advforex_account_update_password extends CI_Driver {
private $urls,$privatekey;
public $CI;
	function execute($row){
	$CI =& get_instance();
		$accountid=$row[0];
		$masterpassword=$row[1];
		$investorpassword=$row[2];
		$result=array();
		$result[]=$row;
		$result[]=$urls=ciConfig('apiForex_url');
		$url=$urls['update'];

		//$res=_localApi('account','lists',array($email));
		//$accounts = isset($res['data'])?$res['data']:array();
		//$users = $CI->users_model->getDetail($email);
		 
		$params=array(
			'privatekey'=>ciConfig('privatekey'),
			'accountid'=>$accountid,
			'masterpassword'=>$masterpassword,
			'investorpassword'=>$investorpassword,
			'allowlogin'=>1,
			'allowtrading '=>1
		);

		$accountid='';
		
		if(defined('LOCAL')){
			$result['local']=1;
			
			$result['params']=$params;
			$accounts=array();

		}
		else{
			
			
		}
		$result['run']=_runApi($url,$params);
		return $result;
	}

	function __CONSTRUCT(){
	$CI =& get_instance();
	$CI->load->helper('api');
	//$CI->config->load('forexConfig_new', TRUE);
    $this->urls = $urls=$CI->config->item('apiForex_url' );
    $this->privatekey = $CI->config->item('privatekey' );

	}
}