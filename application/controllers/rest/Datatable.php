<?php 
require_once APPPATH.'/libraries/REST_Controller.php';
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
class Datatable extends REST_Controller {

    function __construct(){
	parent::__construct();
	$this->load->helper('api');
	$this->load->database();
	$this->load->model('users_model');
	$this->load->model('password_model');
	$this->load->model('forex_model');
	header('Access-Control-Allow-Origin: *'); 
    }

	function index_post(){
		$post=$param=$this->post();
		$function=isset($post['function'])?$post['function']:'fail';
		$res=array('message'=>'unknown', 'param'=>$post);
		logCreate('post '.json_encode($post));
		if(method_exists($this, $function)){
			logCreate('function (OK)'.$function);
			$data=isset($post['data'])?$post['data']:array();
			$res=$this->$function($data);
			if(isset($res['rest_code'])){
				$code=$res['rest_code'];
				unset($res['rest_code']);
				
			}
			else{
				$code=200;
			}
			$param['function']=$function;
			save_and_send_rest($code,$res,$param);
		}
		else{
			save_and_send_rest(204,$res,$param);
		}
	}

	function valid($param){
		$driver_name=isset($param[0])? strtolower($param[0]) :'';

		$this->load->driver('advforex'); /*gunakan hanya bila diperlukan*/
		$driver_core = 'advforex';
		$func_name='execute';
		$list_driver=$this->{$driver_core}->valid_drivers;
		if( !in_array($driver_name, $list_driver)){
			$result=array('status'=>false, $driver_name.'(FAIL)',$driver_core.'(OK)',
			'tambahkan di list driver',$list_driver
			);
		}
		else{
		//$func_name='execute';
			if( !method_exists($this->{$driver_core}->{$driver_name},$func_name) ){
				$result=array('status'=>false,'datatable (FAIL)', $driver_name.'(OK)', $driver_core.'(OK)',
				'fungsi tidak tersedia');
	 
			}
			else{
				$result=array('status'=>true,'datatable (ok)', $driver_name.'(OK)', $func_name.'(OK)');
				//$this->{$driver_core}->{$driver_name}->{$func_name}($row);
				
			}
		}
		return $result;
	}

	function execute($param){
		$driver_name=isset($param[0])?strtolower( $param[0]) :'';
		$row = isset($param[1])?$param[1]:array();

		$this->load->driver('advforex'); /*gunakan hanya bila diperlukan*/
		$driver_core = 'advforex';
		$func_name='execute';
		$list_driver=$this->{$driver_core}->valid_drivers;
		//$func_name='execute';
		if( !method_exists($this->{$driver_core}->{$driver_name},$func_name) ){
			$result=array('status'=>false,'datatable (ok)', $driver_name.'(OK)', $func_name.'(FAIL)');
 
		}
		else{
/*
	$driver_name='forex_update_balance_credit';
	logCreate('adv deposit process start');
		$result=$CI->{$driver_core}->{$driver_name}->{$func_name}($param);
	logCreate('adv deposit process end');
*/
			if($driver_name=='depositprocess'){
				$driver_name='forex_update_balance_credit';
				logCreate('ganti target driver');
			}

			logCreate("rest/datatable |Start |{$driver_core}->{$driver_name}->{$func_name}");
			$result=$this->{$driver_core}->{$driver_name}->{$func_name}($row);
			logCreate("rest/datatable |End |{$driver_core}->{$driver_name}->{$func_name}");
		}
		return $result;
	}
}