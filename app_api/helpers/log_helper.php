<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
/*
membutuhkan config logConfig.php  
*/
 
if ( ! function_exists('logConfig'))
{
  function logConfig($txt,$config0='',$type='debug'){
	$CI =& get_instance();
	$config = $CI->config->item($config0);
	log_message('info','config:'.$config0 );
	if($config){
            $date=date("Ymd");
            $filename= sprintf($config['name'],$date);
            if(isset($config['write'])){
                    logCreate($txt,$type, $config['path'],$filename);
            }else{ log_message('ERROR',"log config not write"); }
	}
	else{		
		$txtError='tidak ditemukan config :'.$config0;
		log_message('ERROR',$txtError);
		logCreate($txt,$type);		
		logCreate($txtError,'error');
	}
  }

}else{}

if ( ! function_exists('logCreateDir')){
	function logCreateDir($dir){
            $a=explode('/',trim($dir));
            $dir_str='';
            log_message('info','create dir |dir basic:'.$dir );
            $n=0;
            foreach($a as $id=>$path){
                    $n++;

            //	if($n>5) die('stop');

                if($path=='') break;
                $dir_str.=($id!=0)?"/$path":$path;
                log_message('info','dir:'.$dir_str );
                if(!is_dir($dir_str)){
                        log_message('info','create dir:'.$dir_str );
                        @mkdir($dir_str); 
                }
                else{ 
        //		logCreate("dir available:{$dir_str}");
                        log_message('info','dir available:'.$dir_str );

                }
            } 
	}
	
}else{}

if ( ! function_exists('logCreate'))
{
  function logCreate($txt,$type='debug',$path='',$filename=''){
	$CI =& get_instance();
	$config = $CI->config->item('logConfig');
	
	if(!isset($config['write'])){
		log_message('info',"log config not write:".json_encode($config));
		return false;
	}
		
	$date=date("Ymd");
	$date.="-".ceil( date("H")/1 );
	$datetime=date("Y-m-d H:i:s");
	if(is_array($txt)){
		$txt=json_encode($txt);
	}
//========IP
	$ip='unknown';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else {
        $ip = isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:null;
    }

	$str="$datetime\t[{$ip}]\t$type\t$txt\n";
	//folder log harus ada
	$target=(trim($path)=='')?$config['path'] :$path ;
	//auto created
	if(!is_dir($target)){
		log_message('info','no dir:'.$target);
		logCreateDir($target); 
	}else{}

	$target.= ( trim($filename)==''? sprintf($config['name'],$date):$filename);
	//@error_log($str,3,$target );

		
	if(!is_file($target)){
		$txt="<?php die('you not allowed to read directly');\t?>\n";
		file_put_contents ($target, $txt,LOCK_EX );
	}else{}
	
	if($target!=''){
		file_put_contents ($target, $str, FILE_APPEND|LOCK_EX );
	}else{}
 
 }
 
}else{}

if(!function_exists('log_info_table')){

    function log_info_table($type, $array){
        logCreate($array, 'log_'.$type);
        return false;
    }
    
}