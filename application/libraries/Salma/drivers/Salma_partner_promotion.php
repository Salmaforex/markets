<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * Salma_partner_promotion
 */

class Salma_partner_promotion extends CI_Driver {
private $urls,$privatekey;
public $CI;
    function __CONSTRUCT(){
        $CI =& get_instance();
        $CI->load->helper('api');
        //$CI->config->load('forexConfig_new', TRUE);
        $CI->load->helper('formtable');
        $CI->load->helper('form');
        $CI->load->library('session');
    }
    
    //put your code here
    function executed($params){
        $return = array();
        $CI =& get_instance();
        
        $type=$params[0];
        $userlogin = $params[1];

        $return['title']='SECURE ACCOUNT | List Member';
        $contents=array('partner/partner_promotion');

        //$userlogin =$return['userlogin'];
        if(!isset($contents))
                redirect('partner/detail');
//	$email = $return['userlogin']['email'];
//	$return['accounts']=  $this->account->gets($email );
        $return['content']=$contents;

//=============URL REGISTER
        $res= _localApi('account','list_simple',array($userlogin['email']));
//echo_r($res);
        $account =   isset($res['data'])?$res['data']:array();
        $total_members=0;
        $name=isset($userlogin['name'])&&$userlogin['name']!=''?$userlogin['name']:'User Forex';

        $account_id=200;
        foreach($account as $row0){
            $account_id = $row0['accountid'];
            if(strtoupper($row0['type'])=='AGENT'){
            //	$url_register=base_url('register/'.$account_id.'_'.url_title($name);
                break;
                
            }
            
        }
        
        $return['url_js_partner']=site_url('partner/js/partner_promotion').'?account_id='.$account_id.'&name='.$name ;
        return $return;
    }
}