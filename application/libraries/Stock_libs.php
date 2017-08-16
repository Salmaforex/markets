<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_libs {
private $CI;
public $params;
	public function categoryDetail($params=array()){
		$driver=$this->CI->stockdriver;
		return $driver->categoryDetail($params); 
	}
//============OBJECT	
	public function objectDetail($params){
		if(count($params)==0) return false;
		$driver=$this->CI->stockdriver;
		return $driver->objectDetail($params);
	}
	
	public function objectStock($params){
		if(count($params)==0) return false;
		$driver=$this->CI->stockdriver;
		return $driver->objectStock($params);
	}
	
	public function objectEdit($params){
		if(count($params)==0) return false;
		$driver=$this->CI->stockdriver;
		return $driver->objectEdit($params);
	}
	
	public function objectData($params){
		if(count($params)==0) return false;
		$driver=$this->CI->stockdriver;
		$data=$driver->objectData($params);
		logCreate('libs:'.json_encode($data));
		return $data;
	}
	
	public function objectNew($params=array()){
		if(count($params)==0) return false;
		$driver=$this->CI->stockdriver;
		return $driver->objectNew($params); 
	}
 		
	public function __construct($params=array() ){
		$this->params=$params;
		$this->CI =& get_instance();
// Do something with $params
		$this->CI->load->driver('stockdriver');
    }
}