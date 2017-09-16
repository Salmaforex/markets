<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Advforex_user_detail extends CI_Driver {
private $urls,$privatekey;
public $CI;
	function __CONSTRUCT(){
		$CI =& get_instance();
		$CI->load->helper('api');
		//$CI->config->load('forexConfig_new', TRUE);
		$this->urls = $urls=$CI->config->item('apiForex_url' );
		$this->privatekey = $CI->config->item('privatekey' );

	}

    function execute($param){
	$CI =& get_instance();
	$CI->load->driver('advforex'); /*gunakan hanya bila diperlukan*/
        $driver_core = 'advforex';
        $func_name='detail';
	//$param1=array('post0'=>$param);
	$driver_name='user';
        $result=$CI->{$driver_core}->{$driver_name}->{$func_name}($param);
	return $result;
    }
    
    function detail($params){
        $CI =& get_instance();
        $email=$params[0];
        $apiRes=false;
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
        $respon['api_res']=$apiRes;
        return $respon;
    }

}