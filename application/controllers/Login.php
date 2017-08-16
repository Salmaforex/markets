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
		js_goto( base_url("login/member")."?msg=login_to_secure" );
		redirect(base_url("login/member"));
	}
	
	function member(){
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
			//echo 'CAPTCHA OK';
			//echo_r($response);echo_r($post);
			$this->session->set_userdata('login', $post);
			//redirect(base_url('member/loginProcess'),1);
			echo "Please Wait, While we process your login";
			js_goto( base_url('member/loginProcess')."?enter=".date("ymdhis") );
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
		$this->load->model('forex_model','forex');
		$this->load->model('country_model','country');
		$this->load->model('account_model','account');
		$defaultLang="english";
		$this->lang->load('forex', $defaultLang);
		$this->param['fileCss']=array(	
			'css/style.css',
			'css/bootstrap.css',
		);
		$this->param['fileJs']=array(
			'js/jquery-1.7.min.js',
		);
		
		$this->param['shortlink']=base_url();
		$this->param['footerJS']=array(	 
			'js/bootstrap.min.js',
			'js/formValidation.min.js',
			'js/scripts.js'
		);
		$this->param['description']="Trade now with the best and most transparent forex STP broker";
		
		$this->param['emailAdmin']=$this->forex->emailAdmin;
		
		//$this->param['session']=$this->session->all_userdata(); 
		$this->param['baseFolder']='depan/';
		if($this->input->post())
			logCreate($this->input->post(),'post');
		
		if($this->session->flashdata('login')){
			$this->param['login']=$this->session->flashdata('login');
			logCreate('session login valid','info');
			logCreate('detail login:'.print_r($this->param['login'],1));
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
		
		$this->param['shortlink']=base_url();
		$this->param['footerJS']=array(	 
			'js/bootstrap.min.js',
			'js/formValidation.min.js',
			'js/scripts.js',
			//'js001/scripts.js'
		);
	}
}