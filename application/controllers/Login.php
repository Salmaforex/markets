<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {
/***
Daftar Fungsi Yang Tersedia :
*	index()
*	member()
*	__CONSTRUCT()
***/
    public $param;	
    function index(){
    //    echo_r($_SERVER);exit;
        js_goto( site_url("login/member")."?msg=login_to_secure" );
        redirect(site_url("login/member"));
    }

    function member(){
        logCreate('login| member| from:'.$_SERVER['HTTP_REFERER']);
        $this->param['title']='Login Secure Area';
        $this->param['show_open_live']=true;
        $this->param['content']=array(
                'modal',
                'login', 
        );
        //$this->param['footerJS'][]='js/login.js';

        $this->showView('newbase001_view'); 

    }

    function process(){
        $captcha_answer = $this->input->post('g-recaptcha-response');
        $post=$this->input->post();
        $response['success']=true;

        if(!defined('LOCAL')){
        // Verifikasi input recaptcha dari user
        //	$response = $this->recaptcha->verifyResponse($captcha_answer);
                logCreate('recaptcha:'.json_encode($response),'info');
                $response['success']=true; 
        }
        else{
                $response['success']=true;
        }

        // Proses
        //echo_r($response);
        log_message('debug','login process| res:'.json_encode($response));
        if( $response['success']  ){
            // Code jika sukses
            $response = $this->valid();
            //echo_r($response);exit;
           
            if($response['status']===false){
                js_goto( site_url('login/member')."?err=".$response['code'] );
                
            }
            
             $login = $this->localApi( 'users','token',array($post['username']));
           // echo_r($response);echo_r($post);echo_r($login);exit;
            $data_login = $login['data']['token'];
            $this->session->set_userdata('login', $data_login);
           //exit;
            //$this->session->set_userdata('username', $post['username']);
            logCreate('login| process| input session :'.count($data_login));
            $type = $login['data']['typeMember'];
            
            //redirect(site_url('member/loginProcess'),1);
            echo "Please Wait, While we process your login";
            logCreate('login| process| goto :'.$type);
        
            js_goto( site_url($type)."?enter=".date("ymdhis") );
            js_goto( site_url('member/loginProcess')."?enter=".date("ymdhis") );
        } 
        else{
                // Code jika gagal
                //echo 'CAPTCHA ERROR';
            $post['message']='Make sure CAPTCHA success';
            $this->session->set_flashdata('login', $post);
            //logCreate('parameter invalid','error');
            //redirect($_SERVER['HTTP_REFERER']."?cap=1",1);
            js_goto( $_SERVER['HTTP_REFERER']  );
        }
    }

    function __CONSTRUCT(){
    parent::__construct();
//		logCreate('controller Login','info');
        $this->load->library('recaptcha');
        $this->load->library('session');
        date_default_timezone_set('Asia/Jakarta');
        $this->param['today']=date('Y-m-d');
        $this->param['folder']='depan/';
        $this->load->helper('form');
        $this->load->helper('formtable');
        $this->load->helper('language');
        $this->load->helper('api');
        $this->load->helper('db');
//        $this->load->model('forex_model','forex');
//        $this->load->model('country_model','country');
//        $this->load->model('account_model','account');
        $defaultLang="english";
        $this->lang->load('forex', $defaultLang);
        $this->param['fileCss']=array(	
                'css/style.css',
                'css/bootstrap.css',
        );
        $this->param['fileJs']=array(
                'js/jquery-1.7.min.js',
        );

        $this->param['shortlink']=site_url();
        $this->param['footerJS']=array(	 
                'js/bootstrap.min.js',
                'js/formValidation.min.js',
                'js/scripts.js'
        );
        $this->param['description']="Trade now with the best and most transparent forex STP broker";

        $this->param['emailAdmin']=$this->forex_model->emailAdmin;
        $this->param['login_page']=true;

        //$this->param['session']=$this->session->all_userdata(); 
        $this->param['baseFolder']='depan/';
        if($this->input->post())
                logCreate($this->input->post(),'post');

        if($this->session->flashdata('login')){
                $this->param['login']=$this->session->flashdata('login');
                logCreate('session login valid','info');
                logCreate('detail login:'.  json_encode($this->param['login'],1));
        }else{}
                        $this->param['fileCss']=array(	
                'css001/style.css',
                'css001/bootstrap.css',
                'css/ddaccordion.css'
        );
        $this->param['fileJs']=array(
                'js/jquery-1.7.min.js',
                'js/ddaccordion.js'
        );

        $this->param['shortlink']=site_url();
        $this->param['footerJS']=array(	 
                'js/bootstrap.min.js',
                'js/formValidation.min.js',
                'js/scripts.js',
                //'js001/scripts.js'
        );
    }
    
    private function valid(){
        $post = $this->input->post();
        $res= $this->localApi( 'users','exist',array($post['username']));
        //echo_r($res);exit;
        $respon_data=isset($res['data'])?$res['data']:false;

        if( $respon_data===false){
            $login['message']="Username Not Valid";
            $this->session->set_flashdata('login', $login);
            logCreate('debug','email not valid| res:'.json_encode($res));
            return array('status'=>false,'code'=>'email_not_valid');
            //    redirect(site_url('login/member')."?err=email_not_valid",1);
        }
        else{
            logCreate('debug','res:'.json_encode($res) .json_encode($post));
            $login['valid']=$respon_data;
            $res= $this->localApi( 'users','login',array($post['username'],$post['password']));
            
            $respon_data=isset($res['data'])?$res['data']:array();
            if(isset($respon_data['valid'])&&$respon_data['valid']===false){
                    $login['message']="username / password   Not Valid";
                    $this->session->set_flashdata('login', $login);
                    logCreate( 'username / password   Not Valid |res:'.json_encode($res),'error');
                return array('status'=>false,'code'=>'user_pass_not_valid');
                //    redirect(site_url('login/member')."?err=user_pass_not_valid",1);
                //    exit;

            }
            
            return array('status'=>true,'code'=>'user_ok');
            //redirect($_SERVER['HTTP_REFERER'],1);
            $login['executeLogin']=$res;
            $respon_data=isset($res['data'])?$res['data']:array();
            //echo_r($respon_data);exit();

            $session['expire']=strtotime("+20 minutes");
            $array=array( 
                    'username'=>$login['username'],
                    'password'=>($res['data']['password']),
                    'expire'=>$session['expire'],
                    'login'=>array()
            );
            //echo_r($array);exit;
            $this->session->set_userdata($array);

            redirect(site_url('member')."?enter=".date("Ymdhis"),1);

        }
            //echo_r($login);
    }

}