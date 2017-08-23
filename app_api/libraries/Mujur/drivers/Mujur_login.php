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
    $CI->load->model('users_model');
    }
    
    function execute($params){
        $CI =& get_instance();
        $return=array($params,dbId());
        $message = 'nothing to do here';
        $debug=array();
//        return array('code'=>200,'result'=>$return);
        $username=isset($params['username'])?$params['username']:false;
        $password=isset($params['password'])?$params['password']:false;
        if($username==false){
                $respon=array('valid'=>false);
                $respon['token']=null;
                $respon['message']='username not valid';
            return array('code'=>203,'result'=>$respon);
        }
        
        $return[]=array($username,$password);
        
        $return['message']='';
        $exist= $CI->users_model->exist_email($username,2);
        if($exist==false){
                $respon=array('valid'=>false);
                $respon['token']=null;

                $respon['message']='Username or Password Problem';
            return array('code'=>203,'result'=>$respon);
        }
        
        $exist = $CI->users_model->check_login($username, $password);
        $return['exist']=$exist;
        if($exist==false){
                $respon=array('valid'=>false);
                $respon['token']=null;
        
                $respon['message']='Please Check Username or Password ';
            return array('code'=>203,'result'=>$respon);
        }
        
        $return[]=$raw=$this->user_token(array($username));
        
        $respon=array('valid'=>false);
        $respon['raw']=$raw;
        $respon['token']=$raw['token'];
        $respon['message']='Success';
        
        $return=$respon;
                
        if(isset($params['debug'])){
            $debug = $params['debug']===true?true:false;
            $return = $return['data'];
        }
        else{
            $debug=false;
        }
        
        //print_r($return);echo "---";
        return $debug?array('params'=>$params,'debug'=>$debug,'return'=>$return):$return;
       //return array('code'=>200,'result'=>$respon);
        
//==================================not run====================
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
    
    //==================TOKEN
    private function user_token($params){
         $CI =& get_instance();
        $username = $params[0];
        $respon=array('param'=>$params,'token'=>'' );
        $users =$CI->users_model->gets($username);
        $detail= $CI->users_model->getDetail($username);
        foreach($detail as $name=>$val){
             $respon[$name]=$val;
        }
        if(!isset($respon['balance'])){
                //=========
            $respon['totalBalance']='---';
        }
        
        $respon['username']=$username;
        $respon['users']=$users;
        $respon['token']=$CI->localapi_model->token_save($respon);
        
        return $respon;
    }
}
