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
}