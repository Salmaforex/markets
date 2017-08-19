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
		}else{ log_message('info',"log config not write"); }
	}
	else{		
		$txtError='tidak ditemukan config :'.$config0;
		log_message('info',$txtError);
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
//	log_message('info', 'filename:'.$target);
	$target.= ( trim($filename)==''? sprintf($config['name'],$date):$filename);
	//@error_log($str,3,$target );
/*
	if($target==''){
		log_message('info', 'filename:'.$target.'(null?)|str:'.$str );
	}else{}
	log_message('info', 'filename:'.$target.'(null?)|str:'.$str );
*/
		
	if(!is_file($target)){
		$txt="<?php die('you not allowed to read directly');\t?>\n";
		file_put_contents ($target, $txt,LOCK_EX );
	}else{}
	
	if($target!=''){
		file_put_contents ($target, $str, FILE_APPEND|LOCK_EX );
	}else{}
 
 }
 
}else{}


if(!function_exists('email_problem')){
	function email_problem($title='', $msg='', $debug=array()){
		$emails =ciConfig('email_for_report');
		$title ='[WARNING] '.$title;
		$headers = "From: noreply@salmaforex.com\r\n";
		$headers .= "Reply-To: noreply@salmaforex.com\r\n"; 
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		
		$message="Date ".date("Y-m-d H:i:s")."<br/>\nProblem occurs:<br/>\n".$msg;
		$message.="<hr/>Detail:\n<pre>".print_r($debug,1)."</pre>";
		
		logCreate('email problem title:'.$title);
		foreach($emails as $email){
			batchEmail( $email, $title, $message , $headers );
		}
		
		log_info_table('problem',array($title,$msg));
		logCreate('email problem title:'.$title.' | '.strip_tags($msg).' |debug:'.json_encode($debug));
	}
	
}

if(!function_exists('log_info_table')){
	function log_info_table($type, $array){
            $CI =& get_instance();
            $input = array();//'tmp0'=>$type);
            foreach( $array as $n0=>$val){
                    $n= $n0+1;
                    $val_input=is_string($val)?$val:json_encode($val);
                    if(strlen($val_input)>250){
                            $val_input=substr($val_input,0,250);
                    }
                    $input['tmp'.$n]=$val_input;
            }

            $input['param']=json_encode($array);
            
            //log_info_table_make($type);

            $tablename='y_'.trim($type)."s".date("Y");
            logCreate('log info table:'.$tablename);
            if(!$CI->db->table_exists($tablename)){
                logCreate('log info not found:'.$tablename);
                $fields = array(
                          'id'=>array( 
                                'type' => 'BIGINT','auto_increment' => TRUE),

                          'param'=>array( 'type' => 'longtext'),				   
                          'modified'=>array( 'type' => 'timestamp'),
                        );
                $CI->dbforge->add_field($fields);
                $CI->dbforge->add_key('id', TRUE);
                $CI->dbforge->create_table($tablename,TRUE);
                $str = $CI->db->last_query();	

                logConfig("create table:$str");
                $sql="ALTER TABLE `$tablename` ENGINE=MyISAM";

//===========NAMBAH BEBERAPA tmp input============
                for($i=1;$i<=30;$i++){
                        $sql="ALTER TABLE `{$tablename}` ADD `tmp{$i}` varchar(255) NULL;";
                        dbQuery($sql);
                }

                logCreate('log info create:'.$tablename);
//================================================
            }

            $sql = dbInsert($tablename, $input);
            logCreate('log insert:'.$tablename);
	}
        
    function log_info_table_make($type){
          $CI =& get_instance();
        $tablename='y_'.trim($type)."s".date("Ym");
        for($i=1;$i<=12;$i++){
            $bln=  sprintf("%02s",$i);
            $tablename = 'y_'.trim($type)."s".date('Y').$bln;             
            if(!$CI->db->table_exists($tablename) ){
                logCreate('log_info_table_make not found:'.$tablename);
                $fields = array(
                          'id'=>array( 
                                'type' => 'BIGINT','auto_increment' => TRUE),
                          'param'=>array( 'type' => 'longtext'),				   
                          'modified'=>array( 'type' => 'timestamp'),
                        );
                $CI->dbforge->add_field($fields);
                $CI->dbforge->add_key('id', TRUE);
                $CI->dbforge->create_table($tablename,TRUE);
                $str = $CI->db->last_query();	

                logConfig("create table:$str");
                $sql="ALTER TABLE `$tablename` ENGINE=MyISAM";

//===========NAMBAH BEBERAPA tmp input============
                for($i=1;$i<=30;$i++){
                        $sql="ALTER TABLE `{$tablename}` ADD `tmp{$i}` varchar(255) NULL;";
                        dbQuery($sql);
                }

                logCreate('log_info_table_make create:'.$tablename);
//================================================
            }
        }
    }
}
