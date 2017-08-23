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
        echo '<pre>'.print_r($raw,1).'</pre>';
    }
    
    //=================
    function action($type=false){
        $driver_core = "komunitas";
        $driver_name = "demo";
        $func_name="basic";
        $params = array('server'=>$_SERVER);
        
    //==================Tidak memanfaatkan Tenary agar bisa terbaca (next baru pakai TENARY===
        $params['debug']= $type=='debug'?true:false;
        $raw = driver_run_action($driver_core, $driver_name, $func_name, $params);
        echo '<pre>'.print_r($raw,1).'</pre>';
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