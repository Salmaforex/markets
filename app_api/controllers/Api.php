<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once APPPATH.'/libraries/REST_Controller.php';
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
class Api extends REST_Controller {
    function __construct(){
	parent::__construct();
	$this->load->helper('basic');
	$this->load->helper('api');
	$this->load->helper('log');
	$this->load->helper('db');
        $this->load->model('localapi_model');
	$this->load->database();
	header('Access-Control-Allow-Origin: *'); 
    }
	
    function index_post($driver='none'){
        $post=$params=$this->post();
    //    $function=isset($post['function'])?$post['function']:'fail';
        $res=array('message'=>'unknown??', 'param'=>$post,'driver'=>$driver);
        
    //    save_and_send_rest(204,$res,$param);
        logCreate('API/'.$driver.' post:'.json_encode($post));
        
        $driver_core =  isset($post['driver'])?$post['driver']:'mujur';
        $driver_name =  trim($driver);
        $func_name  =   isset($post['function'])?$post['function']:'execute';
        
        $raw = driver_run($driver_core, $driver_name, $func_name , $params );
        $raw[]=date("Y-m-d H:i:s");
        
        $code = isset($raw['code'])?$raw['code']:205;
        unset($raw['code']);
        $result = isset($raw['data'] )?$raw['data'] :$raw;
        
        save_and_send_rest($code, $result, $params);
    }
    
    function index_old(){
        if(method_exists($this, $function)){
            logCreate('function (OK)'.$function);
            $data=isset($post['data'])?$post['data']:array();
            $res=$this->$function($data);
            if(isset($res['rest_code'])){
                $code=$res['rest_code'];
                unset($res['rest_code']);

            }
            else{
                $code=200;
            }
            
            
            $param['function']=$function;
            logCreate('rest/forex code:'.$code.'result'.json_encode($res));
            save_and_send_rest($code,$result,$param);
        }
        else{
            save_and_send_rest(204,$res,$param);
        }
    }

}