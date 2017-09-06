<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */ 
defined('BASEPATH') OR exit('No direct script access allowed');

class Runonce extends CI_Controller {

    function index(){
        $this->get_all_account();
    }
    
    function get_all_account(){
        //==========tarik 100 data saja
        $driver_core = "mujur";
        $driver_name = "account";
        $func_name="all";
        $params = array('limit'=>5,'debug'=>true );
        
        $raw = driver_run($driver_core, $driver_name, $func_name, $params);
        echo '<pre>params '.print_r($params,1).'<br/>result:'.print_r($raw,1).'</pre>';
    }
    
    function basic($type=false){
        $driver_core = "mujur";
        $driver_name = "account";
        $func_name="detail";
        $params = array('accountid'=>$this->input->get('accountid') );
        
    //==================Tidak memanfaatkan Tenary agar bisa terbaca (next baru pakai TENARY===
        if($type=='debug'){
            $params['debug']=TRUE;
        }
        else{
            $params['debug']=FALSE;            
        }
        
        $raw = driver_run($driver_core, $driver_name, $func_name, $params);
        echo '<pre>params '.print_r($params,1).'<br/>result:'.print_r($raw,1).'</pre>';
    }
/*
 * Perbaikan berkat contoh Harjito
 */        
    function __construct(){
	parent::__construct();
	$this->load->helper([ 'basic', 'api','log','db','url']);
        
    //    $this->load->model('localapi_model');
	$this->load->database();
	//header('Access-Control-Allow-Origin: *'); 
    }
}