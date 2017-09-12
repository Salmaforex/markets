<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mujur_fix_country extends CI_Driver {
private $urls,$privatekey;
public $CI;
    function __CONSTRUCT(){
        $CI =& get_instance();
        $CI->load->helper('api');
        $CI->load->model('users_model');
        
    }
 
    function account($params){
        $CI =& get_instance();
        $start=isset($params['start'])?$params['start']:0;
        $limit=$params['limit'];
        
        $urls=ciConfig('apiForex_url','forexConfig_new' );
//=============default================
        $debug=array('time'=>array(microtime()),'params'=>$params,'url'=>$urls);
        //debug berisi time eksekusi dan param
        //-------------------//
        $pesan="Ini adalah Pesan";
        $return_code =200; //ignore saja.. saya perlu ini untuk API 
        
//-------------show debug
        $show_debug = isset($params['debug'])&&$params['debug']!=false?true:false;
        unset( $params['debug']);
        
//=============ARRAY KEMBALIAN============= 
        $return=array();
        
        $sql="select * from mujur_account where modified < '2017-09-01' "
                . "order by modified desc "
                . "limit $start,$limit ";
        $debug[]=$sql;
        $res = $CI->db->query($sql)->result_array();
        $debug['time'][]=  microtime();
        $debug['acc']=$res;
        
        $hasil=array();
        
        $url=$urls['get_account'];
        $url_update=$urls['update'];
        
        $params_api =array(
            'privatekey'=>ciConfig('privatekey','forexConfig_new'),
            'accountid'=>'',
                //    'allowlogin'=>1,
                //    'allowtrading '=>1
            'country'=>'Indonesia',
        );
        
        foreach($res as $n=>$row){
            $params_api['accountid']=$row['accountid'];
            $email = $row['email'];
            $users = $CI->users_model->getDetail($email);
            $debug['user'][$n]=$users;
            $modif = $row['modified'];
            
            //$hasil['api'][]='run ok'; //_runApi($url,$params);
            
            $country = isset($users['country'])?trim($users['country']):'';
            if(strtolower($country)=='usd'){
                $country='';
            }
            
            if(true){
                $params_api['country']='Indonesia';
                $hasil[]=array($url_update,$params_api,$modif);
                unset($params_api['country']);
                $hasil[]=array($url,$params_api,$modif);
            }
            
        }
        
        $return['total']=count($res);
        $return['run']=$hasil;
        
        return driver_return($return_code,  $pesan, $return, $debug, $show_debug );
    }
    
    function execute($params=array() ){
        $CI =& get_instance();
        $start=isset($params['start'])?$params['start']:0;
        $limit=$params['limit'];
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
        $urls=ciConfig('apiForex_url','forexConfig_new' );
        $params=array(
            'privatekey'=>ciConfig('privatekey','forexConfig_new'),
            'accountid'=>'',
            'country'=>'Indonesia',
                //    'allowlogin'=>1,
                //    'allowtrading '=>1
        );
        $url=$urls['update'];
        
        $debug[]=array($url,$urls,$params );
        //$return = array('info'=>'perhatikan debug yang di hasilkan' );
        $sql="select accountid, country from ac_account limit $start,$limit ";
        $debug[]=$sql;
        $res = $CI->db->query($sql)->result_array();
        $debug['time'][]=  microtime();
        $debug['acc']=$res;
        
        $hasil=array();
        foreach($res as $row){
            $params['accountid']=$row['accountid'];
            $hasil[]=array($url,$params);
            //$hasil['api'][]='run ok'; //_runApi($url,$params);
            
        }
        
        $return['total']=count($res);
        $return['run']=$hasil;
        //gunakan metode pengembalian lain bila tidak sesuai
        return driver_return($return_code,  $pesan, $return, $debug, $show_debug );
    }
    
    function execute_2($params=array() ){
        $CI =& get_instance();
        $start=isset($params['start'])?$params['start']:0;
        $limit=$params['limit'];
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
        $urls=ciConfig('apiForex_url','forexConfig_new' );
        $params=array(
            'privatekey'=>ciConfig('privatekey','forexConfig_new'),
            'accountid'=>'',
            'country'=>'Indonesia',
                //    'allowlogin'=>1,
                //    'allowtrading '=>1
        );
        $url=$urls['update'];
        
        $debug[]=array($url,$urls,$params );
        //$return = array('info'=>'perhatikan debug yang di hasilkan' );
        $sql="select accountid, country from ac_account2 order by country desc limit $start,$limit ";
        $debug[]=$sql;
        $res = $CI->db->query($sql)->result_array();
        $debug['time'][]=  microtime();
        $debug['acc']=$res;
        
        $hasil=array();
        foreach($res as $row){
            $params['accountid']=$row['accountid'];
            $params['country']=$row['country'];
            $params['raw']=$row;
            $hasil[]=array($url,$params);
            //$hasil['api'][]='run ok'; //_runApi($url,$params);
            
        }
        
        $return['total']=count($res);
        $return['run']=$hasil;
        //gunakan metode pengembalian lain bila tidak sesuai
        return driver_return($return_code,  $pesan, $return, $debug, $show_debug );
    }
}