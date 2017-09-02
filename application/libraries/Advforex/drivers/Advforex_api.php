<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Advforex_api extends CI_Driver {
private $urls,$privatekey;
public $CI;
	function get_account($row=false){
		if($row==false) return false;
		$CI =& get_instance();

		$param=array('privatekey'=>$this->privatekey);
		$param['accountid']=$row===false?7000001:$row;
		$url=$this->urls['get_account'];
		$result=$CI->advforex->runApi($url,$param);
		return $result;
	}

	function register($row=false){
		if($row==false) return false;
		$CI =& get_instance();

	}

	function volume_add($row=false){
		if($row==false) return false;
		$CI =& get_instance();
		$param=array('privatekey'=>$this->privatekey);
		$param['accountid']=isset($row['accountid'])?$row['accountid']:false;
		$param['volume']=isset($row['volume'])?'+'.$row['volume']:false;
		$param['description']='credit in:'.$param['volume'] ;
		if(!$CI->advforex->check_param($param)){
			return false;
		}
		$url=$this->urls['updatecredit'];
		$result=$CI->advforex->runApi($url,$param);
		return $result;
	}

	function volume_out($row=false){
		if($row==false) return false;
		$CI =& get_instance();
		$param=array('privatekey'=>$this->privatekey);
		$param['accountid']=isset($row['accountid'])?$row['accountid']:false;
		$param['volume']=isset($row['volume'])?'-'.$row['volume']:false;
		$param['description']='credit out:'.$param['volume'] ;
		if(!$CI->advforex->check_param($param)){
			return false;
		}
		$url=$this->urls['updatecredit'];
		$result=$CI->advforex->runApi($url,$param);
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