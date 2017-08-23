<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Komunitas_demo extends CI_Driver {
private $urls,$privatekey;
public $CI;
    function __CONSTRUCT(){
        $CI =& get_instance();
        $CI->load->helper('api');
        
    }
    
    function basic($params=array()){
        $debug=array('start'=>microtime()); //biarkan saja
        $return=array(); //disarankan array
//=====================DEFAULT START=========
        $return['pesan']='Ini adalah Pesan';
        $return['code']=200; //ignore saja.. saya perlu ini untuk API
        $return['status']=true;//ignore saja.. 
//======================DEfAuLT END==========
        
        $return['pesan']='dijalankan di library / komunitas / komunitas_demo';
        $return['fungsi']= 'menjalankan fungsi basic';
        $return['info']='perhatikan apabila debug dijalankan';
        
        //==================lebih baik dibuat seperti ini
        return isset($params['debug'])&$params['debug']==true?array('params'=>$params,'debug'=>$debug,'return'=>$return):$return;
    }
}