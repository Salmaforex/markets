<?php //Advforex_update_detail
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Advforex_update_detail extends CI_Driver {
private $urls,$privatekey;
public $CI;
	function execute($row){
	$CI =& get_instance();
		$email=$row[0];
		$result=array();
		$result['time']['begin']=microtime(true);
		$result[]=$row;
		$result[]=$urls=ciConfig('apiForex_url');
		$url=$urls['update'];

		$res=_localApi('account','lists',array($email));
		$accounts = isset($res['data'])?$res['data']:array();
		$result['time']['list_acc']=microtime(true);
		$result[]=$accounts;
		$users = $CI->users_model->getDetail($email);
		$result['time']['detail']=microtime(true);
		$params=array(
			'privatekey'=>ciConfig('privatekey'),
			'accountid'=>0,
			'username'=>$users['name'],
			'address'=>$users['address'],
			'zipcode'=>$users['zipcode'],
			'country'=>$users['country'],
			'phone'=>$users['phone'],
			'allowlogin'=>1,
			'allowtrading '=>1
		);
		$accountid='';
		
		if(defined('LOCAL')){
			$json='{"AccountID":"'.json_encode($accountid).'", "Balance":"0.000000","Credit":"0.000000","Equity":"0.000000","FreeMargin":"0.000000","ResponseCode":"0","ResponseText":"Get margin success"}';
			//$result['margin']=json_decode($json);
			$result['local']=1;
			
			$result['users']=$users;
			//$result['accountid to execute(local)']=$accounts;
			//$accounts=array();

		}
		else{
		//	$res=_runApi($url,$params);
			
		}
		$result['time']['start_ex']=microtime(true);

		foreach($accounts as $row){
			$accountid=$row['accountid'];
			$params['accountid']=$accountid;
		//	echo_r($params);
			if(defined('LOCAL')){
				$res=array('url'=>$url,'param'=>$params );
				logCreate('update account(local):'.json_encode($res ));
				$result['time']['no api '.$accountid]=microtime(true);
			}
			else{
				$res=_runApi($url,$params);
				$result['time']['run api '.$accountid]=microtime(true);
				logCreate('update account:'.json_encode($res));
			}
			
			$result['raw'][]=$res;
			if(isset($res['ResponseCode'])&&(int)$res['ResponseCode']==0){
				$result['valid'][]=$accountid;
			}
			elseif(defined('LOCAL')){
				$result['valid(local)'][]=$accountid;
			}
			else{
				$result['not valid'][]=$accountid;
			}
		}
		$result['time']['end']=microtime(true);
		$result['account to execute']=$accounts;
		logCreate('time update detail:'.json_encode($result['time']));
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