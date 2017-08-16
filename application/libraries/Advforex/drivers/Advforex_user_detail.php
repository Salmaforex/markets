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

}