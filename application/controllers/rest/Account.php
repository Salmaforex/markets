<?php 
require_once APPPATH.'/libraries/REST_Controller.php';
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
class Account extends REST_Controller {
    function __construct(){
	parent::__construct();
	$this->load->helper('api');
	$this->load->database();
	$this->load->model('account_model');
	$this->load->model('password_model');
	$this->load->model('forex_model');
	$this->load->library('session');
	header('Access-Control-Allow-Origin: *'); 
    }
	
	function index_post($params=false){
		$post=$param=$params==false?$this->post():$params;
		$noAPI=false;
		if($params!=false) $noAPI=true;
		
		unset($params);
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
			if($noAPI){
				return array( $code,$res,$param);
			}
			else{
				save_and_send_rest($code,$res,$param);
			}
		}
		else{
			$res['message']='fungsi tidak diketahui '.$function;
			$code=204;
			if($noAPI){
				return array( $code,$res,$param);
			}
			else{
				save_and_send_rest($code,$res,$param);
			}
		}

	}
	
	function list_simple($params){
		$email=$params[0];
		$res=$this->account_model->get_by_field($email,'email');
	//	return $res;
		$account=array();
		foreach($res as $row){
			if(isset($row['id']))
				$account[]= $this->account_model->gets($row['id'],'id',true);
		}
		//$account['raw']=$res;
		return $account;
	}
	function lists($params){
		$email=$params[0];
		$res=$this->account_model->get_by_field($email,'email');
	//	return $res;
		$account=array();
		foreach($res as $row){
			if(isset($row['id']))
				$account[]= $this->account_model->gets($row['id']);//,'id',true);
		}
		//$account['raw']=$res;
		return $account;
	}

	function list_by_partner($params){
		$accountid=$params[0];
		$res=$this->account_model->get_by_field($accountid,'r.reg_agent');
	//	return $res;
		$account=array();
		foreach($res as $row){
			if(isset($row['id']))
				$account[]= $this->account_model->gets($row['id'],'id',true);
		}
		//$account['raw']=$res;
		return $account;
	}

	function register($params){
		$this->load->driver('advforex'); /*gunakan hanya bila diperlukan*/
		$driver_core = 'advforex';
		$driver_name='register';
		$func_name='simple_register';
		if( !method_exists($this->{$driver_core}->{$driver_name},$func_name) ){
			$output=array('function "'.$func_name.'" unable to declare');
			die(json_encode($output));
		}
		else{
			$row=$params=false;
			$params=$this->{$driver_core}->{$driver_name}->{$func_name}($row);
		//	echo '<pre>';print_r($params);
			return $params;
		}

	}
//========Partner
	function list_patner(){
		
	}
	
	function partner_revenue($params){
		$email=$params[0];
		$res=$this->account_model->get_by_field($email,'email');
		//logCreate('rest account partner_revenue email:'.$email);
		$account=array();
		$revenue=0;
		foreach($res as $row0){
			if(isset($row0['id'])){
				//logCreate('rest account partner_revenue id:'.$row0['id'] );
				$account[]= $row_account=$this->account_model->gets($row0['id']);//,'id',true);
				//$res=_localApi('forex','balance',array($row_account['accountid']));
				//$balance=isset($res['data']['margin'])?$res['data']['margin']:array($res);
				//$revenue+=isset($balance['Equity'])?$balance['Equity']:0;
//===================TotalAgentCommission
				$res=_localApi('forex','summary',array($row_account['accountid']));
				//logCreate('rest account partner_revenue res:'.json_encode($res) );
				$summary=isset($res['data']['summary'])?$res['data']['summary']:array($res);
				//$summary['TotalAgentCommission']
				$revenue+=isset($summary['TotalAgentCommission'])?$summary['TotalAgentCommission']:0;
				//logCreate('rest account partner_revenue rev:'.$revenue);
			}
		}
		//$account['raw']=$res;
		return $revenue;
	}
	
	private function driver_run($array,$driver_name='forex_summary'){
		$this->load->driver('advforex'); /*gunakan hanya bila diperlukan*/
		$driver_core = 'advforex';
		$func_name='execute';
		$list_driver=$this->{$driver_core}->valid_drivers;
		//$func_name='execute';
		if( !in_array($driver_name, $list_driver)){
			$result=array('status'=>false, $driver_name.'(FAIL)',$driver_core.'(OK)',
			'tambahkan di list driver',$list_driver,
			'rest_code'=>245
			);
			return $result;
		}

		if( !method_exists($this->{$driver_core}->{$driver_name},$func_name) ){
			$result=array('status'=>false, $func_name.'(FAIL)',$driver_core.'(ok)', $driver_name.'(OK)',
			'rest_code'=>246);
			return $result;
		}
		else{
			$result=$this->{$driver_core}->{$driver_name}->{$func_name}($array);
			
		}
		return $result;
	}

}