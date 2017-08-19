<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Demo
 *
 * @author gunawan
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Demo extends MY_Controller {
	public $param;
	public $folderUpload;
    function __CONSTRUCT(){
    parent::__construct(); 		
        date_default_timezone_set('Asia/Jakarta');
        $this->param['today']=date('Y-m-d');
        $this->param['folder']='demo/';
        $this->load->helper('form');
        $this->load->helper('formtable');
        $this->load->helper('language');
        $this->load->helper('api');
        $this->load->helper('db');
        $this->param['baseFolder']='demo/';
        $this->param['content']=array(  );
        $this->param['list_option']=array(
            'login'=>'test login',
            'user_detail'=>'user detail by email'
        );
        $this->param['post']=$this->input->post();

    }
    
    public function index(){
        $this->param['title']='SECURE ACCOUNT | Dashboard';
        $this->param['content'][]=  'info' ;

        //$this->param['footerJS'][]='js/login.js';
        //echo_r($this->param);exit;
        $this->showView('demo_view');
    }	    
    
    public function login(){
        $post=$this->param['post'];
        if(isset($post['token_form'])){
            $url = ciConfig('salma_api').'/login';
            $params=array(
                'username'=>$post['username'],
                'password'=> ($post['password'])
            );
            
            $result = _runApi($url,$params);
            if(!is_array($result)){
                $result=array($result);
            }
            $result[]=$url;
            $this->param['result']=$result;
        }
        
        $this->param['title']='SECURE ACCOUNT | Dashboard';
        $this->param['content'][]=  'login' ;

        //$this->param['footerJS'][]='js/login.js';
        //echo_r($this->param);exit;
        $this->showView('demo_view');
    }	
}
