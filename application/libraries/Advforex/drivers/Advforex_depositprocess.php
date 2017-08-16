<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Advforex_depositprocess extends CI_Driver {
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
		$func_name='execute';
		//$param1=array('post0'=>$param);
		$driver_name='forex_update_balance_credit';
		logCreate('adv deposit process start');
			$result=$CI->{$driver_core}->{$driver_name}->{$func_name}($param);
		logCreate('adv deposit process end');
		return $result;
	}

}