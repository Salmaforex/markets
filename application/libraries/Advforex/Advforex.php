<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Advforex extends CI_Driver_Library {
	public $driver_name;
    public $valid_drivers;
    public $CI;
/***
Daftar Fungsi Yang Tersedia :
*	__construct()
*	runApi($url, $parameter=array())
*	check_param($params)
***/
	function __construct(){
        $this->CI =$CI  =& get_instance();
		$driver_prefix='advforex_';
		$valid_drivers= $CI->config->item('valid_drivers');
        $this->valid_drivers = $valid_drivers;
		/*array(
			'api','register','recover','user','userApproval'
		);*/
		//log_message('info','driver:'.json_encode( $this->valid_drivers ));
    }

    function runApi($url, $parameter=array()){
	$CI =& get_instance();
	$CI->load->model('forex_model' );
	$CI->load->library('session');
//===========DEFAULT
	$login= $CI->session->userdata('login');
	$CI->api_model->clean();
	$member_login= isset($login['username'])?$login['username']:'user_forex' ;
//=============
	$maxTime=40;//default
	if(isset($parameter['maxTime'])) $maxTime=$parameter['maxTime'];

	$dtAPI=array('url'=>$url);
	/*if(is_array($url))
		die( print_r($url,1).print_r($parameter,1) );*/

	if(count($parameter)){
		$logTxt="advforex -> runApi | url:{$url}| param:".json_encode($parameter);
		$metode='POST';
            $param=array();
            foreach($parameter as $name=>$val){
                $param[$name]= is_string($val)?trim($val):$val;
            }
		//.http_build_query($parameter,'','&'); 

            logCreate('runApi clean parameter');

            $parameter = $param;
	}
	else{ 
		$logTxt="advforex -> runApi | url:{$url}"; 
		$parameter['info']='no post';
		$metode='GET';
	}
	//$parameter[]=array('server'=>$_SERVER);

	$dtAPI['parameter']=json_encode($parameter);
	logCreate('API: '.$logTxt);
//===============SAVE REST
	$response =  false ; //$CI->api_model->find_result( $url,$parameter,$member_login);
//echo_r($res);die;
	if($response ==false){
		logCreate('runApi (start) '.$url);
/*
	if(count($parameter)){	 	
		logCreate( 'lib API: '."url:{$url}| param:\n".print_r($parameter,1),'debug');
	}
	else{ 
		logCreate( 'lib API: param:'.print_r(parse_url($url),1),'debug');
	}
*/

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url  );
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//================
		if($metode=='POST'){ 
//$parameter != '' && count($parameter)!=0 ) {
			$convert_parameter=http_build_query($parameter,'','&');
		//	echo_r($convert_parameter);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_TIMEOUT, $maxTime);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $convert_parameter);
			if( isset($_SERVER['HTTP_USER_AGENT']) )
				curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			logCreate('lib API:POST','info');
		}
		else{ 
			logCreate('lib API:GET','info');
		}

		logCreate('curl start:'.$url );
		$response = curl_exec($curl);
		logCreate('curl end:'.$url );
		
		if (0 != curl_errno($curl)) {
			$response = new stdclass();
			$response->code = '500';
			$response->message = curl_error($curl);
			$response->maxTime = $maxTime;
			$dtAPI['response']=json_encode($response );
			$dtAPI['error']=1;
			logCreate('curl end:error'  );
		}
		else{
			$response0 = $response; 
			$dtAPI['response']= $response ;
			$dtAPI['error']=0;
			$response = json_decode($response0,1);
			logCreate('curl end total:'.count($response)  );
			if(!is_array($response)){
				$strip_response = stripslashes($response0);
				$response = json_decode($strip_response,1);
				if(!is_array($response)){
					$strip_response = substr($strip_response,1, strlen($strip_response)-2);
					$response = json_decode($strip_response,1);
					if(!is_array($response)){
						logCreate('curl end response:not ok (1)' );
						$dtAPI['response']= $strip_response ;
						$response=$response0;
						$dtAPI['error']=1;
					}
					else{
						logCreate('curl end response: ok (2)' );
					}
				}
				else{
					logCreate('curl end response:not ok (3)' );
					/*OK*/
					$dtAPI['error']=-2;
				}

			}
			else{
				/*OK*/
				logCreate('curl end response:not ok (4)' );
				$dtAPI['error']=-1;
			}

		}
		
		curl_close($curl);
		if(!isset($response0)) $response0='?';
		logCreate('lib advforex -> API |url:'. $url. "|raw:".(is_array($response)?'total array/obj='.count($response):'str length:'.strlen($response0) ) );
	    //$CI->db->insert($CI->forex->tableAPI,$dtAPI);
		if(strlen($dtAPI['response']) < 1000){
			dbInsert($CI->forex_model->tableAPI, $dtAPI);
		}
//===========SAVE TEMP
		$raw=array(
			 'params'=>$parameter,
			 'result'=>$response,
		);

		$raw['functions']=$url;
		$raw['member_login']=$member_login;
		$result=$CI->api_model->new_data($raw);
				
	}
	else{
		logCreate('runApi (cache) '.$url);
		$dtAPI['response']=json_encode($response );
	}
		return array(
	//  	'raw'=>$response0,
			'db_data'=>$dtAPI,
			'response'=>$response
		) ;

    }

	function check_param($params){
		$err='';
		foreach($params as $name=>$param){
			if($param===false){
				$err.="parameter {$name} Not Valid| ";
			}

		}

		if($err!=''){
			log_message('error','advforex check_param :'.$err);
			logCreate('advforex check_param :'.$err,'error');
			return false;
		}

		return true;
	}

}
