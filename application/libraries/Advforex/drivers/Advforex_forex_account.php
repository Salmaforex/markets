<?php

//Advforex_forex_summary
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
  Advforex_forex_balance
 */

class Advforex_forex_account extends CI_Driver {

    private $urls, $privatekey;
    public $CI;

    function execute($row) {
        $CI = & get_instance();
        logCreate('Advforex_forex_account data ' . json_encode($row));
        $accountid = (int) $row[0];
        if ($accountid < 600) {//tidak perlu ngehit!!
            $json = '{"AccountID":"' . $accountid . '","UserName":"MT4 ", "Address":"","ZipCode":"","Email":"DO NOT CHANGE","Country":"United States","Phone":"","AgentID":"0","Leverage":"100","Group":"manager","AllowLogin":"1","AllowTrading":"1","Balance":"0.000000","Credit":"0.000000","ResponseCode":"0","ResponseText":"Get user success"}';
            $result['account'] = json_decode($json, true);
            logCreate('Advforex_forex_account no data ' . $accountid);
            return $result;
        }
        $result = array();
        $result[] = $row;
        $result[] = $urls = ciConfig('apiForex_url');
        $url = $urls['get_account'];
        $params = array(
            'privatekey' => ciConfig('privatekey'),
            'accountid' => $accountid
        );
        if (true) {
            $res = _runApi($url, $params);
            logCreate($url);
            $result['raw'] = $res;
            if ((int) $res['ResponseCode'] == 0) {
                $result = array('account' => $res);
            } else {
                $result = array('account' => array(), 'raw' => $res);
            }
        }

        return $result;
    }

    function __CONSTRUCT() {
        $CI = & get_instance();
        $CI->load->helper('api');
        //$CI->config->load('forexConfig_new', TRUE);
        $this->urls = $urls = $CI->config->item('apiForex_url');
        $this->privatekey = $CI->config->item('privatekey');
    }

}
