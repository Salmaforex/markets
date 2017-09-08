<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Demo extends CI_Controller {

    function basic($type=false){
        $driver_core = "komunitas";
        $driver_name = "demo";
        $func_name="basic";
        $params = array('server'=>$_SERVER);
        
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
    
    function error_basic($type=1){
        $params=array('now'=>date('Y-m-d H:i:s'));
         $driver_core = "komunitas";
        $driver_name = "demo";
        $func_name="basic";
        
        if($type==1){ //core tidak ada
            $driver_core='error';
        }
        
        if($type==2){ //driver name not exist
            $driver_name='error';
            
        }
        
        if($type==4){ //driver name  exist but no file
            $driver_name='tutor';
            
        }
        
        if($type==3){ //function not exits
            $func_name='error';
        }
        
        $raw = driver_run($driver_core, $driver_name, $func_name, $params);
        echo '<pre>params '.print_r($params,1).'<br/>result:'.print_r($raw,1).'</pre>';
    }
    
    //=================
    function action($type=false){
        $driver_core = "komunitas";
        $driver_name = "demo";
    
        $params = array('time'=>date("Y-m-d H:i:s"));
        
    //==================Tidak memanfaatkan Tenary agar bisa terbaca (next baru pakai TENARY===
        $params['debug']= true;
        $raw = driver_run_action($driver_core, $driver_name, $params);
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