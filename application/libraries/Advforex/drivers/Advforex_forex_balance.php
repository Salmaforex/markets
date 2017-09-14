<?php //Advforex_forex_summary
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
Advforex_forex_balance
*/
class Advforex_forex_balance extends CI_Driver {
private $urls,$privatekey;
public $CI;
	function execute($row){
	$CI =& get_instance();
	logCreate('Advforex_forex_balance data '.json_encode($row));
            $accountid=$row[0];
            $show_account=isset($row[1])?$row[1]:false;
            $show_sum=isset($row[2])?$row[2]:false;
		if((int)$accountid<600){
			$json='{"AccountID":"'.$accountid.'", "Balance":"0.000000","Credit":"0.000000","Equity":"0.000000","FreeMargin":"0.000000","ResponseCode":"0","ResponseText":"Get margin Demo"}';
			$result['margin']=json_decode($json,true);
			logCreate('Advforex_forex_balance no data '.$accountid);
			return $result;
		}
		$result=array();
		$result['time'][ ]=microtime(true);
		$result[]=$row;
		$result[]=$urls=ciConfig('apiForex_url');
		$url=$urls['get_margin'];
		$params=array(
			'privatekey'=>ciConfig('privatekey'),
			'accountid'=>$accountid
		);
                
		if(defined('LOCAL')){
			$json='{"AccountID":"'.$accountid.'", "Balance":"10.000000","Credit":"30.000000","Equity":"0.000000","FreeMargin":"0.000000","ResponseCode":"0","ResponseText":"Get margin success"}';
			$result['margin']=json_decode($json,true);
		}
		else{
                    $res=_runApi($url,$params);
                    $result['time'][ ]=microtime(true);
                    $result['raw']=$res;
                    logCreate('time forex balance:'.json_encode($result['time']));
                    if((int)$res['ResponseCode']==0){
                        $result=array('margin'=>$res);
                    }
                    else{
                        $result=array('margin'=>array(),'raw'=>$res );
                    }

                    if($show_account){
                        $url=$urls['get_account'];
                        $result['account_forex'] = _runApi($url,$params);
                    }
                    
                    if($show_sum){
                        $url=$urls['sum_trading'];
                        $result['sum_trading'] = _runApi($url,$params);
                    }

		}
                
		$result['time'][ ]=microtime(true);
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