<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sms extends CI_Controller {
    function index($page='satu'){
        $this->load->model('ujian_model');
    //========= 
    //$this->load->database();
        $url='http://advance.forex/index.php/rest/sms';
        $parameter=array(
            'phone'=>'628568132429',
            'message'=>'hello world. ini dikirim dari '.site_url()
        );
        
        $result= _runApi($url,$parameter);
        print_r($result);
    }
    

  
/*
 * Perbaikan berkat contoh Harjito
 */        
    function __construct(){
	parent::__construct();
	$this->load->helper([ 'basic', 'api','log','db','url']);
        
    //    $this->load->model('localapi_model');
    //	$this->load->database();
    //  header('Access-Control-Allow-Origin: *'); 
    }
}