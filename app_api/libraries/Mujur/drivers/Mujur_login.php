<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mujur_login extends CI_Driver {
private $urls,$privatekey;
public $CI;
    function __CONSTRUCT(){
    $CI =& get_instance();
    $CI->load->helper('api');
    }
    
    function execute($params){
        $return=array($params,dbId());
//        return array('code'=>200,'result'=>$return);
        $username=isset($params['username'])?$params['username']:false;
        $password=isset($params['password'])?$params['password']:false;
        if($username==false){
                $respon=array('valid'=>false);
                $respon['password']=null;
            return array('code'=>203,'result'=>$respon);
        }
        
        $return[]=array($username,$password);
        
        return array('code'=>200,'result'=>$return);
        
        $exist=$this->users_model->exist($username,2);
        logCreate('exist:'.json_encode($exist));
        $status=$this->users_model->login_check($username, $password);
        if($status==true){
                $respon=array('valid'=>true);
                $respon['password']= $this->users_model->gets($username)['u_password']; 
        }
        else{
                $respon=array('valid'=>false);
                $respon['password']=null;//sha1("$password|zzzz")."|zzzz";
        }
        $respon['status']=$status;
        return $respon;
    }
}
