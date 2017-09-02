<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alto_libs {
private $CI,$driver;
public $params;
	public function valid($module='',$action=''){	
		$driver=$this->CI->altodriver;
		$result=$driver->valid($module,$action);
		logConfig('altolibs|module:'.$module.'|action:'.$action.'|result:'.json_encode($result),'logAlto','debug');
		return $result;
	}

	public function run($module='',$action='', $param=array() ){
		$driver=$this->CI->altodriver;		
		$result = $driver->run($module ,$action , $param);
		 
		logConfig('altoLibs|module:'.$module.'|action:'.$action.'|result:'. json_encode($result),'logCoreAlto','debug'); 
		logConfig('altoLibs|module:'.$module.'|action:'.$action,'logAlto');
		if($result){
			return $result;
		}
		else{ 
			//$result['message'], $result['code']
			return array( 
				'code'=>13,
				'message'=>'unknown result'
			);
		}
	}
	public function __construct($params=array() ){
		$this->params=$params;
		$this->CI =& get_instance();
// Do something with $params
		$this->CI->load->driver('altodriver');
		//$this->driver=$this->CI->altodriver;
    }
}