<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ujian extends CI_Controller {
    
/**
 * index 
 *
 * Halaman yang digunakan untuk ujian / test
 *
 * @param	string	$page 	nama halaman yang akan di load
 * 
 * @return	void
*/
    function index($page){
        $this->load->model('ujian_model');
    //========= 
    //$this->load->database();
        $data=array(  );
        $this->load->helper('url_salah');
        
    //tentukan parameter
        $data[post]= $post = $_POST;
        $data[user_id]= isset($_POST[user_id])?$_POST[user_id]:'';
        $data[files] = $files =isset($_FILES)?$_FILES:false;
        
    //kondisi dalam coding
        if(isset($files[images])){
            $type = isset($files[images][type])?$files[images][type]:false;
            $data[upload_txt]="file yang diupload tidak diketahui";
            
            if($type=='image/png'){
                $data[upload_txt] = "anda upload dokumen png";
                
            }
            
            if($type=='image/jpeg'){
                $data[upload_txt] = "anda upload dokumen jpg";
                
            }
            
            if(!isset($data[upload_txt])){
                $data[upload_txt]="file yang diupload tidak diketahui";
                
            }
            
        }
    //memunculkan nama filename yang di post!!
        if(isset($post[filename])){
            $filename = $post[filename];
        //apabila nama file yang tadi di input adalah test123
        //yang keluar harus _test123_
            $data[info] = "nama filename _$filename_"; 
             
        }
        else{
            $data[info]= "file dijalankan di ".current_url();

        }
        
        $this->load->view('test_ujian',$data);
/*****
 * Silakan menulis dibawah ini link dimana kamu mendapatkan jawaban untuk problem kamu
 * google/bing/ dan lain-lain diperbolehkan?!?
 * 
 */
    }
    
    function basic($type=false){
        $driver_core = "komunitas";
        $driver_name = "demo";
        $func_name="basic";
        $params = array('server'=>$_SERVER);
        
    //==================Tidak memanfaatkan Tenary agar bisa terbaca (next baru pakai TENARY===
        
        if($type=='debug'){
            $params[debug]=TRUE;
        }
        else{
            $params[debug]=FALSE;            
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