<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
/* 
*/
if ( ! function_exists('_localApi')){
	function _localApi_new($api='',$function='', $data=array()){
		$CI =& get_instance();
		$param=array(
			'api'=>$api,
			'function'=>$function,
			'data'=>$data
		);
		log_info_table('localapi',array('new', $api,$function,$data));
		
		$id_random=dbId('random');
		$url=site_url('rest/'.$param['api']);
		$url.="?r=".$id_random;
		
		$api_name=ucfirst(strtolower($api));
		$controllers=APPPATH.'/controllers/rest/'.$api_name.'.php';
		logCreate('load '.$api_name."| ".$controllers );
		require_once $controllers;
		
		$class = new  $api_name();
		
		$res = $class->index_post($param);
		echo 'new';echo_r($res);exit;
		$param['result']=json_encode($res);
		$param['id']=dbId('api');
		$param['data']=json_encode($data);
		unset($param['api']);
		$table=trim('rest_'.$api);
		if(!$CI->db->table_exists($table)){
			$sql="create table IF NOT EXISTS `$table` like `rest_temp`";
			dbQuery($sql);
		}
		dbInsert($table, $param);
		return $res;
	}

	function _localApi($api='',$function='', $data=array() ){
	$CI =& get_instance();
	//$login= $CI->session->userdata();//'login');
	
	$CI->localapi_model->clean();
	$CI->api_model->clean();
	$member_login= $CI->session->userdata('username') ;
		$param=array(
			'api'=>$api,
			'function'=>$function,
			'data'=>$data //params
		);
		$key_to_find=null;
		log_info_table('localapi',array('old',$api,$function,count($data) ));

		$key=false;
		if($key){
			$res = json_decode($_COOKIE['key_'.$key],true);
		}
		else{
			$id_random=(string) dbId('random');
			
			$saved_session[$id_random]=array(
				'expired'=>strtotime("+1 minutes"),
				'key'=>$key_to_find
			);
			
		//	setcookie('save_key', json_encode($saved_session));
			$url=site_url('rest/'.$param['api']);
			$url.="?r=".$id_random;
			logCreate('localAPI (start) '.$url.' |function:'.$function.' |param:'.json_encode($data) );
			$res =   false;
		//$CI->localapi_model->find_result($api,$function,$data,$member_login);
		//logCreate('localAPI (warning) no cache');

			if($res==false){
				$res= _runApi($url,$param);
				logCreate('localAPI (runApi) result:'.json_encode($res));
				$raw=array(
					'params'=>$data,
					'result'=>$res,
				);
				$raw['api']=$api;
				$raw['functions']=$function;
				$raw['member_login']=$member_login;
			//	$result=$CI->localapi_model->new_data($raw);

			//	echo_r($result);die;
			}
			else{
				logCreate("localAPI (cache) table| $api| $function| $member_login");
			}
			
			logCreate('localAPI (end) '.$url);
			//echo '<br/>'.$url;var_dump($res);

			$param['result']=json_encode($res);
			$param['id']=dbId('api_'.$api);
			$param['data']=json_encode($data);
			unset($param['api']);
			unset($param['id']);
			$table=trim('zrest_'.$api);
			if(!$CI->db->table_exists($table)){
				$sql="create table IF NOT EXISTS `$table` like `rest_temp`";
				dbQuery($sql);
			}
			
			logCreate('localAPI (save) '.count($param));
			dbInsert($table, $param);
			$res[]=$url;
		//	setcookie('key_'.$id_random, json_encode($res) );//$_COOKIE['key_'.$id_random] = $res;
		}
		
		return $res;
	}
}
if ( ! function_exists('_runApi')){
	function _runApi($url, $parameter=array()){
		$CI =& get_instance();
		$CI->load->driver('advforex');
		$res= $CI->advforex->runApi($url,$parameter);
		log_info_table('run_api',array('new',$url,count($parameter) ));
		$result0=isset($res['response'])?$res['response']:false;
		if($result0===false){
			$debug=array(
				'result'=>$res,
				'params'=>$parameter,
			);
			email_problem("run API Error", "target:".$url, $debug);
		}
        else{
            logCreate( 'runAPI: '.json_encode($result0));
        }
		return (array)$result0;
	}

	function _runApi_old($url, $parameter=array()){
	global $maxTime;
	if( $maxTime==null ) $maxTime=10;
	if(isset($parameter['maxTime'])) $maxTime=$parameter['maxTime'];
	log_info_table('run_api',array('old',$url,count($parameter) ));
	
	$CI =& get_instance();
	$dtAPI=array('url'=>$url);
	if(count($parameter)){
		$logTxt="func:_runApi| url:{$url}| param:".http_build_query($parameter,'','&'); 
	}
	else{ 
		$logTxt="func:_runApi| url:{$url}"; 
		$parameter['info']='no post';		
	}
	//$parameter[]=array('server'=>$_SERVER);
	$dtAPI['parameter']=json_encode($parameter);
	logCreate( 'API: '.$logTxt); 
		
	if(count($parameter)){	 	
		logCreate( 'API: '."url:{$url}| param:\n".print_r($parameter,1),'debug');
	}else{ 
		logCreate( 'API: param:'.print_r(parse_url($url),1),'debug');
	}
		$curl = curl_init();
		 
		curl_setopt($curl, CURLOPT_URL, $url  );
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		if($parameter != '' && count($parameter)!=0 ) {
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_TIMEOUT, $maxTime);
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($parameter,'','&'));
			//curl_setopt($ch, CURLOPT_POSTFIELDS, array('file' => '@/path/to/file.ext');
			//if( isset($_SERVER['HTTP_USER_AGENT']) )
			//	curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
			logCreate('API:POST','info');
		}
		else{ 
			logCreate('API:GET','info');
		}
		curl_setopt($curl,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
		$response = curl_exec($curl);

		if (0 != curl_errno($curl)) {
			$response = new stdclass();
			$response->code = '500';
			$response->message = curl_error($curl);
			$response->maxTime = $maxTime;
			$dtAPI['response']=json_encode($response );
			$dtAPI['error']=1;
		}
		else{
			$response0 = $response; 
			$dtAPI['response']= $response ;
			$dtAPI['error']=0;
			$response = json_decode($response,1);
			if(!is_array($response)){
				$response=$response0;
				$dtAPI['error']=1;
			}
			else{
				$dtAPI['error']=0;
			}
		}
		
		curl_close($curl);
		if(!isset($response0)) $response0='?';
		logCreate( 'helper| API| url:'. $url. "|raw:".(is_array($response)?'total array/obj='.count($response):$response0 ) );
		
	    //$CI->db->insert($CI->forex->tableAPI,$dtAPI);
		$sql=$CI->db->insert_string($CI->forex->tableAPI, $dtAPI);
		dbQuery($sql);
		return $response;
			
	}

} else{}

if ( ! function_exists('batchEmail')){
	function batchEmail( $to='', $subject='', $message='', $headers=''){
	logCreate('batchEmail to:'.$to."| subject:$subject",'email');
	$CI =& get_instance();
	//$CI->load->model('forex_model');
	
		$arr=array('to'=>trim($to), 'subject'=>$subject,'message'=>base64_encode($message),'headers'=>$headers);
		$json=json_encode($arr);
		//echo '<br>'.$json;
		$id0=date("ymd").'000' ;
		$id=dbId('mail',(int)$id0);
		$target="media/email/".$id.".txt";
		//echo '<br>target:'.$target;
		//file_put_contents($target, $json);

		$data=array(
			'subject'=> $subject ,
			'to'=> $to ,
			'headers'=>  $headers ,
			'status'=>1
		);
		$data['messages']= $message ;
		//$sql="insert into mujur_email(`subject`,`to`,`headers`) values('".addslashes($subject)."','".addslashes($to)."','".addslashes(json_encode($headers))."')";
		//$sql="insert into mujur_email(`subject`,`to`,`header`) values('".addslashes($subject)."','".addslashes($to)."','".addslashes($json)."')";
		//$data['status']=0;
		$table=$CI->forex_model->tableBatchEmail;
		dbInsert($table, $data);
		log_info_table('batchmail',array($to,$subject));
		//$sql=$CI->db->insert_string($table, $data);
		//dbQuery($sql);
		//return true;
	}
	
} else{}


if ( ! function_exists('return_rest')){
function return_rest($code=200,$data=array()){
	header('Content-Type: application/json; charset=utf-8;');
	$response=array('status_code'=>$code,
		'data'=>$data
	);
	
	echo json_encode($response);
	exit;
}
}

if ( ! function_exists('save_rest')){
function save_rest($param=array(),$result=false){
	$CI =& get_instance();
	$CI->load->model('forex_model');
	$dtAPI['url']='rest ';
	$dtAPI['url'].=isset($param['api'])?$param['api']:'???';
	$dtAPI['url'].="/";
	$dtAPI['url'].=isset($param['function'])?$param['function']:'???';

	$dtAPI['parameter']=isset($param['data'])?json_encode($param['data']):'???';
	$dtAPI['response']=json_encode($result);
	$dtAPI['error']=-10;

	$sql= $CI->db->insert_string($CI->forex_model->tableAPI, $dtAPI);
	dbQuery($sql);
}
}

function save_and_send_rest($code, $data, $param){
	save_rest($param, $data);
	return_rest($code, $data);
	
}

if(!function_exists('_send_email')){
function _send_email($to='',$subject,$message,$from='noreply@salmaforex.com' ){
	$CI =& get_instance();
	$CI->load->model('forex_model');
	//$emailAdmin = $CI->forex->emailAdmin ;

	$headers = "From: {$from}\r\n";
	$headers .= "Reply-To: {$from}\r\n";
	//$headers .= "CC: susan@example.com\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

	//if(isset($show_local))echo $message;
	//===============================
	{
		if(!is_array($to))$to=array($to);
		foreach($to as $email){
			batchEmail($email, $subject, $message, $headers);
		}

	}
	
	log_info_table('savemail',array($to,$subject));
}
}
