<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Log_libs {
private $CI;
	function config($txt,$config0='',$type='debug'){
	$CI =& get_instance();
	$config = $CI->config->item($config0);
	 
	if($config){
		$date=date("Ymd");
		$filename= sprintf($config['name'],$date);
		if(isset($config['write']))
			$this->create($txt,$type, $config['path'],$filename);
	}
	else{
		$txt='tidak ditemukan config :'.$config0;
		$this->create($txt,'error');
	}
  }

  private function createDir($dir){
		$a=explode('/',trim($dir));
		$dir_str='';
		foreach($a as $id=>$path){
			if($path=='') break;
			$dir_str.=($id!=0)?"/$path":$path; 
			if(!is_dir($dir_str)){
				@mkdir($dir_str); 
			}else{ $this->create("dir avaiable:{$dir_str}");}
		} 
		
  }
  
  function create($txt,$type='debug',$path='',$filename=''){
	$CI =& get_instance();
	$config = $CI->config->item('logConfig');
	
	if(!isset($config['write']))
		return false;
		
	$date=date("Ymd");
	$datetime=date("Y-m-d H:i:s");
	if(is_array($txt))
		$txt=json_encode($txt);
	
	$str="$datetime\t$type\t$txt\n";
	//folder log harus ada
	$target=(trim($path)=='')?$config['path'] :$path ;
	//auto created
	if(!is_dir($target)){
		$this->createDir($target);
	}else{}
	
	$target.= ( trim($filename)==''? sprintf($config['name'],$date):$filename);
	//@error_log($str,3,$target );
	if($target==''){
		log_message('error', 'filename:'.$target.'(null?)|str:'.$str );
	}
	
	if(!is_file($target)){
		$txt="<?php\n\tdie('you not allowed to read directly');\t?>\n";
		file_put_contents ($target, $txt,LOCK_EX );
	}else{}
	
	if($target!=''){
		file_put_contents ($target, $str, FILE_APPEND|LOCK_EX );
	}else{}
 
 }
 
  
  public function __construct(  )
  {
		$this->params=$params;
		$this->CI =& get_instance(); 
  }
	
}