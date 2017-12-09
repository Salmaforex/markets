<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ujian extends CI_Controller {
    function index($page='satu'){
        $this->load->model('ujian_model');
    //========= 
    //$this->load->database();
        $data=array(  );
        $this->load->helper('url');
        
    //tentukan parameter
        $data['post']= $post = $_POST;
        $data['user_id']= isset($_POST['user_id'])?$_POST['user_id']:'';
        $data['files'] = $files =isset($_FILES)?$_FILES:false;
        
    //kondisi dalam coding
        if(isset($files['images'])){
            $type = isset($files['images']['type'])?$files['images']['type']:false;
            $data['upload_txt']="file yang diupload tidak diketahui";
            
            if($type=='image/png'){
                $data['upload_txt'] = "anda upload dokumen png";
                
            }
            
            if($type=='image/jpeg'){
                $data['upload_txt'] = "anda upload dokumen jpg";
                
            }
            
            if(!isset($data['upload_txt'])){
                $data['upload_txt']="file yang diupload tidak diketahui";
                
            }
            
        }
    //memunculkan nama filename yang di post!!
        if(isset($post['filename'])){
            $data['info'] = "nama filename _{$post['filename']}_";
             
        }
        else{
            $data['info']= "file dijalankan di ".current_url();

        }
        
        $this->load->view('test_ujian',$data);
    }
    
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
  
/*
 * Perbaikan berkat contoh Harjito
 */        
    function __construct(){
	parent::__construct();
	$this->load->helper([ 'basic', 'api','log','db']);
        
    //    $this->load->model('localapi_model');
    //	$this->load->database();
    //  header('Access-Control-Allow-Origin: *'); 
    }
}