<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mujur_account extends CI_Driver {
private $urls,$privatekey;
public $CI;
    function __CONSTRUCT(){
    $CI =& get_instance();
    $CI->load->helper('api');
    $CI->load->model('users_model');    
    $CI->load->model('account_model');
    }
    
    function detail($params=array() ){
        $CI =& get_instance();
//=============default================
        $debug=array('time'=>array(microtime()),'params'=>$params);
        //debug berisi time eksekusi dan param
        //-------------------//
        $pesan="Ini adalah Pesan";
        $return_code =200; //ignore saja.. saya perlu ini untuk API 
        
//-------------show debug
        $show_debug = isset($params['debug'])&&$params['debug']!=false?true:false;
        unset( $params['debug']);
        
//=============ARRAY KEMBALIAN============= 
        $accountid= $params['accountid'];
        $return = array('info'=>'perhatikan debug yang di hasilkan' );
        $return['detail'] =$account= $CI->account_model->gets($accountid,'accountid');
        $user = $CI->users_model->gets($account['email'],'u_email');
        $detail = $CI->users_model->getDetail($account['email']);
        $return['users']=array(
            $user, $detail
        );
        
        //gunakan metode pengembalian lain bila tidak sesuai
        return driver_return($return_code,  $pesan, $return, $debug, $show_debug );
    }
    
    function all($params=array() ){
        $CI =& get_instance();
//=============default================
        $debug=array('time'=>array(microtime()),'params'=>$params);
        //debug berisi time eksekusi dan param
        //-------------------//
        $pesan="Ini adalah Pesan";
        $return_code =200; //ignore saja.. saya perlu ini untuk API 
        
//-------------show debug
        $show_debug = isset($params['debug'])&&$params['debug']!=false?true:false;
        unset( $params['debug']);
        
//=============ARRAY KEMBALIAN=============  
        $return = array('info'=>'perhatikan debug yang di hasilkan' );
        $limit=isset($params['limit'])?$params['limit']:10;
        $start=0;
        
        $return['total'] =$total=$CI->account_model->get_all(array('count_all'=>true));
        $debug['time']['total 1']=  microtime();
        $limit=$total;
        $raw = $CI->account_model->get_all(array(),$limit,$start);
        $debug['time']['show all 1']=  microtime();
        /*
         * 
        $user = $CI->users_model->gets($account['email'],'u_email');
        $detail = $CI->users_model->getDetail($account['email']);
        $return['users']=array(
            $user, $detail
        );
        */
        $data=array();
        foreach($raw as $account){
            if($account['email']==''){
                $debug['no email'][]=$account['accountid'];
                continue;
            }
            $user = $CI->users_model->gets($account['email'],'u_email');
            $debug['time']['get_'.$account['email']]=  microtime();
            $detail = $CI->users_model->getDetail($account['email']);
            $debug['time']['get_detail_'.$account['email']]=  microtime();
            $country=isset($detail['country']['country_name'])?$detail['country']['country_name']:'Indonesia' ;
            $data[]=array(
                'accountid'=>$account['accountid'],
                'country'=>$country,
                'code'=>isset($detail['country']['country_code'])?$detail['country']['country_code']:'-'  
            );
            if(!isset($detail['country']['country_code'])){
                $debug['not ok'][]=$account['email'];
            }
        }
        
        $debug['time']['done' ]=  microtime();
        
        $return['account']=$data;
        //gunakan metode pengembalian lain bila tidak sesuai
        return driver_return($return_code,  $pesan, $return, $debug, $show_debug );
    }
}
