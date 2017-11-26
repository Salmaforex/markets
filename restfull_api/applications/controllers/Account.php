<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once APPPATH . '/libraries/REST_Controller.php';
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
/**
 * Description of Account
 *
 * @author gunawan
 */
class Account extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('api');
        $this->load->database();
        $this->load->helper('basic');
       // $this->load->library('session');
        header('Access-Control-Allow-Origin: *');
        //logCreate('rest user : server'.print_r($_SERVER,1));
    }
    //put your code here
    function balance_post(){
        $post    = $this->post()  ;
        $respon['raw']=array($post) ;
        $respon['err_code']=FALSE; //wajib ada
        $respon['message']=NULL; //wajib ada
        $input=isset($post['accountid'])?$post['accountid']:FALSE;
        if($input){
            $respon['raw'][]=$balance=driver_run($driver_core='mujur', $driver_name='forex_balance', $func_name='executed',$input);
            $respon['summary']=isset($balance['summary'])?$balance['summary']:NULL;
            $respon['margin']=isset($balance['margin'])?$balance['margin']:NULL;
            $respon['account']=isset($balance['account'])?$balance['account']:NULL;
            unset($respon['raw']);
            $this->response($respon,200);
        
        }
        else{
            $respon['error']=1;
            $respon['message']='no id';
            $this->response($respon,202);
        }
    }
}
