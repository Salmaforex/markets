<?php //Advforex_forex_summary
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Advforex_forex_summary extends CI_Driver {
private $urls,$privatekey;
public $CI;
	function execute($row){
	$CI =& get_instance();
	logCreate('Advforex_forex_summary data '.json_encode($row));
		$accountid=$row[0];
		if((int)$accountid<600){
			$json='{"AccountID":"'.$accountid.'","demo":"1","TotalProfit":"0","TotalTransactions":"0","TotalOpennedTransactions":"0","TotalFloatingTransactions":"0","TotalClosedTransactions":"0","TotalOpennedVolumeTransaction":"0","TotalFloatingVolumeTransaction":"0","TotalClosedVolumeTransaction":"0","TotalVolume":"0","TotalCommission":"0","TotalAgentCommission":"0","TotalWithdrawal":"0","TotalDeposit":"0","ResponseCode":"0","ResponseText":"this is demo"}';
			$result['summary']=json_decode($json,true);
			logCreate('Advforex_forex_summary no data '.$accountid);
			return $result;
		}
		$result=array();
		$result[]=$row;
		$result[]=$urls=ciConfig('apiForex_url');
		$url=$urls['sum_trading'];
		$params=array(
			'privatekey'=>ciConfig('privatekey'),
			'accountid'=>$accountid
		);
		if(defined('LOCAL')){
			$json='{"AccountID":"'.$accountid.'","demo":"1","TotalProfit":"-3.51","TotalTransactions":"24","TotalOpennedTransactions":"22","TotalFloatingTransactions":"0","TotalClosedTransactions":"22","TotalOpennedVolumeTransaction":"23","TotalFloatingVolumeTransaction":"0","TotalClosedVolumeTransaction":"23","TotalVolume":"22","TotalCommission":"0","TotalAgentCommission":"0","TotalWithdrawal":"0","TotalDeposit":"100","ResponseCode":"0","ResponseText":"Success"}';
			$result['summary']=json_decode($json,true);
		}
		else{
			$res=_runApi($url,$params);
			$result['raw']=$res;
			if((int)$res['ResponseCode']==0){
				$result=array('summary'=>$res);
			}
			else{
				$result=array('summary'=>array(),'raw'=>$res );
			}
		}
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