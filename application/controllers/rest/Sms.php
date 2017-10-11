<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//require_once(APPPATH.'/libraries/SmtpApi.php');
class Sms extends CI_Controller {

    private $db_main;

    function index() {
        //echo "start";
        $post= $this->input->post();
        
        $result=array(
            'code'=>200,
            'error'=>FALSE,
            'message'=>NULL,
            'data'=>array()
        );
        
        if(!isset($post['phone'])){
            $result['error']='NO PHONE';
            $result['message']='Tidak ada Telepon yang dimasukkan';
        }
        
        if(!isset($post['message'])){
            $result['error']='Tanpa Pesan';
            $result['message']='Tidak Ada Pesan yang akan dikirim';
        }
        
        //$result['error']='maintenance';
        //$result['message']='Tidak Ada Pesan yang akan dikirim';
            
        if($result['error']===FALSE){
            $params=array(
                    'debug'=>true,
                    'number'=>$post['phone'],
                    'message'=>$post['message'],
                    'header'=>'send from '.$_SERVER['REMOTE_ADDR'],
                //    'local'=>true,
                //    'type'=>'masking'

                );

            $respon =  smsSend($params);
            
            logCreate($respon,'sms');
            if( isset($respon['status']) ){
                $result['data']= isset($respon['status'])?$respon['status']:FALSE;
                //array($params,$respon);//
                $result['message']='SMS SEND SUCCESSFULL';
                logCreate($respon,'SUCCESS SMS');
            }
            else{
                $result['message']='SMS NOT SEND SUCCESSFULL';
                $result['error']='Internal ERROR';
                logCreate($respon,'error SMS');
            }
            $result[]=$params;
        }
        else{
             logCreate($result,'error SMS');
        }
                
        echo json_encode($result);

    }
 
    function send_sms(){
        $post= $this->input->post();
        $hp_send ='628568132429';
        $message ='hello ini test dari localhost';
        $header = 'dari luar';
        $params=array(
                    'debug'=>true,
                    'number'=>$hp_send,
                    'message'=>$message,
                    'header'=>$header,
                //    'local'=>true,
                //    'type'=>'masking'

                );

        $respon = smsSend($params);
        $time[]=  microtime();
        logCreate($respon,'sms');
        print_r($respon);
    }
    
        function __CONSTRUCT() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
    }

}
