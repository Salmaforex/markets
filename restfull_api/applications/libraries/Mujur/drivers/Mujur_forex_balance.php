<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mujur_forex_balance extends CI_Driver {

    private $urls, $privatekey;
    public $CI;

    function __CONSTRUCT() {
        $CI = & get_instance();
        $CI->load->helper('api');
        $CI->load->model('users_model');
        $CI->load->model('account_model');
    }

    function executed($params=FALSE) {
        //return array('data'=>$params);
        $accountid =  $params ;
        if(!$accountid) return array($params);
        //=================SUMMARY=====================
        if ((int) $accountid < 600) {
            $json = '{"AccountID":"' . $accountid . '","demo":"2","TotalProfit":"0","TotalTransactions":"0","TotalOpennedTransactions":"0","TotalFloatingTransactions":"0","TotalClosedTransactions":"0","TotalOpennedVolumeTransaction":"0","TotalFloatingVolumeTransaction":"0","TotalClosedVolumeTransaction":"0","TotalVolume":"0","TotalCommission":"0","TotalAgentCommission":"0","TotalWithdrawal":"0","TotalDeposit":"0","ResponseCode":"0","ResponseText":"this is demo"}';
            $result['summary'] = json_decode($json, true);
            $result[]=$params;
            
            //return $result;
        }
        else{
        
            $result = array();
            $result[] = $params;
            $result[] = $urls = ciConfig('apiForex_url');
            $url = $urls['sum_trading'];
            $params = array(
                'privatekey' => ciConfig('privatekey'),
                'accountid' => $accountid
            );
            if (defined('LOCAL')) {
                $json = '{"AccountID":"' . $accountid . '","demo":"1","TotalProfit":"-3.51","TotalTransactions":"24","TotalOpennedTransactions":"22","TotalFloatingTransactions":"0","TotalClosedTransactions":"22","TotalOpennedVolumeTransaction":"23","TotalFloatingVolumeTransaction":"0","TotalClosedVolumeTransaction":"23","TotalVolume":"22","TotalCommission":"0","TotalAgentCommission":"0","TotalWithdrawal":"0","TotalDeposit":"100","ResponseCode":"0","ResponseText":"Success"}';
                $result['summary'] = json_decode($json, true);
            } else {
                $res = _runApi($url, $params);
                $result['raw'] = $res;
                if ((int) $res['ResponseCode'] == 0) {
                    $result = array('summary' => $res);
                } else {
                    $result = array('summary' => array(), 'raw' => $res);
                }
            }
            $result[]=$urls;
            
        }
        
        //===================MARGIN=====================
        $url = $urls['get_margin'];
        $params = array(
            'privatekey' => ciConfig('privatekey'),
            'accountid' => $accountid
        );
        if (defined('LOCAL')) {
            $json = '{"AccountID":"' . $accountid . '","demo":"1","TotalProfit":"-3.51","TotalTransactions":"24","TotalOpennedTransactions":"22","TotalFloatingTransactions":"0","TotalClosedTransactions":"22","TotalOpennedVolumeTransaction":"23","TotalFloatingVolumeTransaction":"0","TotalClosedVolumeTransaction":"23","TotalVolume":"22","TotalCommission":"0","TotalAgentCommission":"0","TotalWithdrawal":"0","TotalDeposit":"100","ResponseCode":"0","ResponseText":"Success"}';
            $result['margin'] = json_decode($json, true);
        } else {
            $res = _runApi($url, $params);
            $result['raw'] = $res;
            if ((int) $res['ResponseCode'] == 0) {
               $result['margin'] =  $res ;
            } else {
                $result['margin'] =  array() ;
                $result[]=$res;
            }
        }
        //===================account=====================
        $url = $urls['get_account'];
        $params = array(
            'privatekey' => ciConfig('privatekey'),
            'accountid' => $accountid
        );
        if (defined('LOCAL')) {
            $json = '{"AccountID":"' . $accountid . '","demo":"1","TotalProfit":"-3.51","TotalTransactions":"24","TotalOpennedTransactions":"22","TotalFloatingTransactions":"0","TotalClosedTransactions":"22","TotalOpennedVolumeTransaction":"23","TotalFloatingVolumeTransaction":"0","TotalClosedVolumeTransaction":"23","TotalVolume":"22","TotalCommission":"0","TotalAgentCommission":"0","TotalWithdrawal":"0","TotalDeposit":"100","ResponseCode":"0","ResponseText":"Success"}';
            $result['account'] = json_decode($json, true);
        } else {
            $res = _runApi($url, $params);
            $result['raw'] = $res;
            if ((int) $res['ResponseCode'] == 0) {
               $result['account'] =  $res ;
            } else {
                $result['account'] =  array() ;
                $result[]=$res;
            }
        }
        $result[]=$urls;
        
        
        return $result;
    }

}
