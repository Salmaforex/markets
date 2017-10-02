<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
class Guest extends MY_Controller {
/***
Daftar Fungsi Yang Tersedia :
*	home($raw=false,$agent=false)
*	register()
*	recover($id=0)
*	forgot()
*	__CONSTRUCT()
***/
    public function home($agents=null, $raw=0)
    {
        $params = array('home', $agents, $raw);
        $params['debug']=true;
        //$params[1]=$this->input->get('agent');
        //$result=driver_run_action('salma', 'guest', $params);
        
        $options =array(
            'parent'=>'salma',
            'sub'=>'guest',
            'params'=>$params,
            'debug'=>true,
        //    'mode'=>'api'
        );
        
        //print_r($options);die;
        $result = $this->basic->driver_action($options);
        //driver_run_action
        //libraries/salma/drivers/salma_guest_home
         
        if(isset($result['data'])){
            $this->parse_params($result['data']);
            
        }
        
        $this->param['get'] = $this->input->get();
        //echo 'https://secure.salmamarkets.com/register/7901875_Gunawan-Wibisono';
        //echo '<pre>';print_r($this->param);exit;
        $this->showView('newbase001_view');
    }
    
    function register_agent($agents=NULL,$raw=NULL)
    {
        $params = array('agent', $agents, $raw);
        $params['function']="register";
        
        $params['debug']=true;
        
        //$result=driver_run_action('salma', 'guest', $params);
        //driver_run_action
        $options =array(
            'parent'=>'salma',
            'sub'=>'guest',
            'params'=>$params,
            'debug'=>false,
        //    'mode'=>'api'
        );
        
        $result = $this->basic->driver_action($options);
        //libraries/salma/drivers/salma_guest_agent
        //echo_r($result);exit;
        $this->parse_params($result['data']);
        $this->showView('newbase001_view');
   /*     
       
		$this->showView();
    * 
    */
    }


    public function agent(){
        $params = array('agent', $agents, $raw);
        $params['debug']=true;
        $result=driver_run_action('salma', 'guest', $params);
        //driver_run_action
        //libraries/salma/drivers/salma_guest_home
        //echo_r($result);exit;
        $this->parse_params($result['data']);
        $this->showView('newbase001_view');
        /*
        $this->load->library('session');
        $this->param['fullregis']=true;
        $this->param['statAccount']='agent';
        $this->param['agent']=false;
        $this->param['showAgent']=false;
        $this->param['showForm']=false;

        $this->param['title']='OPEN PATNER ACCOUNT'; 
        if(!isset($this->param['formTitle'])) 
                $this->param['formTitle']=$this->param['title'];
        $this->param['content']=array(
                'welcome', 
        );
        $this->showView();

         * 
         */
    }

    public function register(){
        $time=array(microtime());
    //$this->load->model('users_model');
            $post=$this->input->post();
    //echo_r($post);exit;
            if(count($post)==0) redirect('welcome');

        $ar_key=array('name','city','state','zip','address','phone');
        $hp_send = $post['phone'];
        $register_text ="Welcome to Salma Markets.\n";
        $time[]=  microtime();
        foreach($ar_key as $key){
            $values=isset($post[$key])?trim($post[$key]):'';
            if($values==''){
                    $post['message']='Provide with Your Detail ('.$key.')';
                    $this->session->set_flashdata('error_message', 'Please Provide with Your Detail ('.$key.')');
                    $this->session->set_flashdata('register', $post);
                    logCreate('parameter invalid: please click check:'.$key,'error');
                    redirect($_SERVER['HTTP_REFERER'],1);
            }
            
        }
        $time[]=  microtime();
//==========TELEPON=====================
        if(strlen($post['phone'])<6){
                $post['message']='Provide with Valid Phone Number';
                $this->session->set_flashdata('error_message', 'Provide with Your Phone Number');
                $this->session->set_flashdata('register', $post);
                logCreate('parameter invalid: phone |value:'.$post['phone'],'error');
                redirect($_SERVER['HTTP_REFERER'],1);
                
        }
        $time[]=  microtime();
//==========email valid=================
        $post['email']=isset($post['email'])?trim($post['email']):'';
        $email = $post['email'];
        
        if($post['email']==''){
                $post['message']='Provide with Your Email';
                $this->session->set_flashdata('error_message', 'Provide with Your Email');
                $this->session->set_flashdata('register', $post);
                logCreate('parameter invalid: email Blank','error');
                redirect($_SERVER['HTTP_REFERER'],1);
                
        }
        elseif(isset($post['email'])){
                $post['email']=trim($post['email']);
                
        }
        $time[]=  microtime();

        if(!isset($post['accept'])){
                $post['message']='Please Click Accept';
                $this->session->set_flashdata('error_message', 'please click accept');
                $this->session->set_flashdata('register', $post);
                logCreate('parameter invalid: please click accept','error');
                redirect($_SERVER['HTTP_REFERER'],1);
                die('-not valid-');
                redirect('welcome');
        }
        $res=array();

        if( defined('LOCAL')){
                logCreate('Local.. hapus user email:'.$email );
                //$res= _localApi('users','erase',array($post['email']));
                $this->users_model->erase($post['email']);
        }

        //$res= _localApi('users', 'exist', array($post['email']));
        //echo_r($res);	die('user exist?');
        $res=$this->users_model->exist($post['email']);
        $time[]=  microtime();
        //echo_r($res);
        //die('user exist?');

        $respon_data=isset($res['data'])?$res['data']:array();
        if(!$respon_data){
        //!$res=$this->users_model->exist($post['email'])){
        //no email found
        }
        else{
            $post['message']='Email Already on Our System';
            $this->session->set_flashdata('error_message', 'Email Already on Our System');
            $this->session->set_flashdata('register', $post);
            logCreate('parameter invalid: email exist','error');
            redirect($_SERVER['HTTP_REFERER'],1);
        }
        $time[]=  microtime();

//==========SAVE REGIS===================
        $res=array();
        $this->load->model('password_model');
        $res['users']=$this->users_model->register($post,false);
        if($post['statusMember']=='AGENT'){
            $this->users_model->update_type($email, 10 );
            logCreate('register as agent:'.$email,'agent');

        }
        $time[]=  microtime();

        $message='unknown';
        $res['message']=$message;

        $email_data=array(
                'username'=>$post['email'],
                'email'=>$post['email'],
                'password'=>$res['users']['masterpassword'],
                'mastercode'=>$res['users']['mastercode'],
        );
        //echo_r($res);echo_r($email_data);exit;
        //$this->load->view('email/emailRegister_email',$email_data);
        logCreate('active email:'.$post['email']);
        $this->users_model->active_email($post['email']);
        $time[]=  microtime();

        //echo_r($res);die();
        //$res_acc= _localApi('account','register',$post);
        //echo_r($res_acc);die();

        $email=$post['email'];
        logCreate('simple register email:'.$post['email']);
        $this->load->driver('advforex'); /*gunakan hanya bila diperlukan*/
        $driver_core = 'advforex';
        $driver_name='register';
        $func_name='simple_register';
        logCreate('start Registeration','register');
        $time[]=  microtime();
        if( !method_exists($this->{$driver_core}->{$driver_name},$func_name) ){
                $output=array('function "'.$func_name.'" unable to declare');
                die(json_encode($output));
        }
        else{
                $row=$params=false;
                logCreate("RUN {$driver_core}->{$driver_name}->{$func_name} ");
                $res_account=$this->{$driver_core}->{$driver_name}->{$func_name}($post);
                
        }
        $time[]=  microtime();
        logCreate('end Registeration','register');
/*
[account] => Array
(
    [AccountID] => 2500042
    [MasterPassword] => r2lKwon
    [InvestorPassword] => uw6nnLc
    [ResponseCode] => 0
    [ResponseText] => Create user success
)
*/
        if(defined('LOCAL')&&!isset($res_account['account']) ){
                $res_account['account']=array(
                'AccountID'=>'2000fake',
                'MasterPassword'=>'xxxxxx',
                'InvestorPassword'=>'zzzzzzz',
                'ResponseCode'=>0,
                );
            logCreate('local Registeration','register');

        }
        $time[]=  microtime();

        if($email_data){
            $account_data=array();
                if($res_account['account']!=false){
                    $email_data['account']=$account_data=$res_account['account'];
                }
                //$email_data['show']=true;
                $email_data['name']=$post['name'];
                //$email_data['mastercode']=$post['name'];

                //echo_r($email_data);
                if(defined('LOCAL')){
                //	$email_data['show_local']=true;
                }

                logCreate('email_data:'.print_r($email_data,1));
                
                $register_text .="Hello {$email_data['name']}\n";
                
                //$register_text .="Your personal area Access\n".base_url('login/member');
                $register_text .="\nLogin : {$email_data['username']}\n";
                $register_text .="Pass : {$email_data['password']}\n";
                $register_text .="Master code : {$email_data['mastercode']}\n";
                $register_text .="Please Update Your Personal Area Password and MT4 Account\n";
                $register_text .="Account id : {$account_data['AccountID']}\n";
                $register_text .="Trading : {$account_data['MasterPassword']}\n";
                $register_text .="Investor : {$account_data['InvestorPassword']}\n";
                //$register_text .="For help, please login to your Salma Markets account and click in Live Support icon for Support.";
    //====================SMS===================
                $params=array(
                    'debug'=>true,
                    'number'=>$hp_send,
                    'message'=>$register_text."\nSincerely, CS.",
                    'header'=>'register',
                //    'local'=>true,
                    'type'=>'masking'

                );

                $respon = smsSend($params);
                $time[]=  microtime();
                logCreate($respon,'sms');
                
                $params=array($hp_send, $email_data['username'], $account_data['AccountID']);
                $params[] = my_ip();
                $params[] = $time;

                log_info_table('regis_user', $params);

                $this->load->view('email/emailRegister_email',$email_data);
                $email_data['no_sms']=true;
//====================SMS ACCOUNT===================
                
                $this->load->view('email/emailRegister_account',$email_data);

        //	exit();
                $post['message']='Register Success. We will send to your email ('.$email.') detail';
                //$this->session->set_flashdata('register', $post);
                $register=array(
                    'data'=>$res_account,
                    'email'=>$email_data,
                    'post'=>$post
                );

                $this->session->set_userdata($register);
                
                logCreate('register:'.print_r($register,1));
                logCreate('register sukses','info');
                redirect(site_url('guest/success'));
        //	redirect($_SERVER['HTTP_REFERER'],1);
        }
        else{
                $post['message']='Register Failed. Try contact our CS';
                $this->session->set_flashdata('register', $post);
                logCreate('register sukses','info');
                redirect($_SERVER['HTTP_REFERER'],1);
        }

        //echo_r($post);die(); 
        $stat=false;

    }

        public function register_old(){
		$respon['title']='NEW LIVE ACCOUNT (CREATED)';
			if($post['statusMember']=='AGENT'){
				$respon['title']='NEW PATNER ACCOUNT (CREATED)';
			}
		$params=$post;
		if( isset($post['accept']))$stat=true;
		logCreate('register post:'.print_r($post,1));
		if($stat==true)
			$stat=$this->forex->saveData($params,$message);
		
		if($stat==false){
			echo 'balik ke welcome';
			//print_r($_SERVER);
			$post['message']='Please Review Your Input';
			$this->session->set_flashdata('register', $post);
			logCreate('parameter invalid','error');
			redirect($_SERVER['HTTP_REFERER'],1);
		}
		else{
			echo 'menuju login';
			logCreate('parameter valid and success input into register table','success');
			$this->session->set_flashdata('notif', array('status' => true, 'message' => 'Sukses!'));
			redirect(site_url('login'),1);
		}
	}
	
	public function recover($id=0){
//	echo '<pre>'.$id;
		$this->param['title']='Recover your Live Account';
		$this->param['title']='Recover your Secure Account';
		$this->param['content']=array(
			'modal',
			'recover', 
		);
		$this->param['recoverId']=$id;
		//$detail=$this->account->recoverId($id);
		$this->load->driver('advforex'); /*gunakan hanya bila diperlukan*/
        $driver_core = 'advforex';
        $driver_name='recover';
        $func_name='execute';
					
		if($id!=0){ 	
			$url=base_url("depan/data");
			//reset
//			print_r($detail);die();
			//=================DRIVER
            if( !method_exists($this->{$driver_core}->{$driver_name},$func_name) ){
				$output=array('function "'.$func_name.'" unable to declare');
            die(json_encode($output));
            }
            else{
				$row=$params=$id;
				$params=$this->{$driver_core}->{$driver_name}->{$func_name}($row);
            //echo_r($params);exit;
				if($params==false){
					$forgot = array('status'=>false, 'message'=>'Please Create New Recover Request');
					$this->session->set_flashdata('forgot', $forgot);
					redirect('guest/forgot');
				}

				$respon = $params['respon'];
            }

					
		//	exit();
			$params['mastercode']=$params['user']['users']['u_mastercode'];
			$this->param['raw']=$params;
			$this->param['respon']=$respon;
			//array('code'=>266,'message'=>'Please Check you email');
                        $email = $params['user']['email'];
                        
                        $respon_user = driver_run('advforex', 'user_detail','detail',array($email));
                        $phone_user = isset($respon_user['user_detail']['phone'])?$respon_user['user_detail']['phone']:FALSE;
                        
			$email_data=array();
			$email_data['params']=$params;
			$email_data['users']=$params['user'];
			$email_data['masterpassword']=$params['masterpassword'];
			$email_data['mastercode']=$params['user']['users']['u_mastercode'];
                        
                        $sms_text="Confirmation to Recover Personal Area password\nHello {$params['user']['name']},\n";
                        $sms_text.="Thank you for submitting, Here your detail:";
		//	echo_r($email_data);exit; $users['email']
                        
                        $sms_text .="\nLogin: {$params['user']['email']}\n";
                        $sms_text .="Password: {$email_data['masterpassword']}\n";
                        $sms_text .="Master code: {$email_data['mastercode']}\n";
                        
                        //====================SMS===================
                        $params=array(
                           'debug'=>true,
                            'number'=>$phone_user,
                            'message'=>$sms_text."Sincerely, System.",
                            'header'=>'recover user'
                        //    'local'=>true,
                        //  'type'=>'masking'

                        );

                        //$respon = smsSend($params);
                        
                        unset($params['message'], $params['debug']);
                        $params['email']=$email;
                        $params[] = my_ip();
                        $params[] = 'request';
                        log_info_table('recover_user', $params);
                        
			$pesan=$this->load->view('email/email_recover_email',$email_data,true);
		//	die($pesan);
			_send_email($email, $subject='[Salmamarkets] New User Detail',$pesan);
		}
		else{ 
			$this->param['raw']=array('invalid');
		}
		//var_dump($detail);exit;
		$params=$this->{$driver_core}->{$driver_name}->clean( );
		$this->param['footerJS'][]='js/login.js';
		$this->showView(); 
	}
	
	public function forgot(){
		$post=$this->input->post();
		if($post){
                    $respon = driver_run('advforex', 'user_detail','detail',array($post['email']));
                    $phone_user = isset($respon['user_detail']['phone'])?$respon['user_detail']['phone']:FALSE;
                    //echo_r($respon);die('stop');
                    $sms_text =isset($respon['user_detail']['name'])?"Hello ".$respon['user_detail']['name'].",\n":"Recover Password.\n";
                    
//			echo 'proses melakukan forgot password '.print_r($post,1);
                    $forgot = array('status'=>false, 'message'=>'Email Not Found');
        //=================DRIVER
                    $this->load->driver('advforex'); /*gunakan hanya bila diperlukan*/
                    $driver_core = 'advforex';
                    $driver_name='recover';
                    $func_name='requesting';
                    if( !method_exists($this->{$driver_core}->{$driver_name},$func_name) ){
                        $output=array('function "'.$func_name.'" unable to declare');
                        die(json_encode($output));
                    }
                    else{
                        $row=$params=$post['email'];
                        $params=$this->{$driver_core}->{$driver_name}->{$func_name}($row);

							//print_r($post);print_r($params);
                        $respon = $params['data']['result'];
                        //echo_r($respon);die('stop'.$phone_user);
                        
                    }
			//echo_r($respon);die("{$driver_core}->{$driver_name}->{$func_name}");
                    if($respon['status']==1){
                        $token=dbId('token');
                        //exit;
                        $ar=array('email'=>$post['email'], 'token'=>$respon['recoverid']);
                        $json=json_encode($ar);
                        $url=site_url('guest/forgot_process')."?t=".base64_encode($json);
                        
                        $sms_text.="Thank you for submitting Recovery form, Here your detail:\n";
                        $sms_text.=site_url('guest/recover/'.$respon['recoverid']);
                        $sms_text.="\nClick Here to Generate Your Recovery Password. The link Expired Soon\nIgnore if you not request this";
                        
                        //====================SMS===================
                        $params=array(
                           'debug'=>true,
                            'number'=>$phone_user,
                            'message'=>$sms_text."Sincerely, System.",
                            'header'=>'request recover'
                        //    'local'=>true,
                        //  'type'=>'masking'

                        );

                    //    $respon = smsSend($params);
                         unset($params['message'], $params['debug']);
                        $params['email']=$post['email'];
                        $params[] = my_ip();
                        $params[] = 'request';
                        log_info_table('recover_user', $params);
                
                        redirect($url,1);
                        //==============success
                            
				$email_data=array( );
				$email_data['show_html']=true;
				//$this->load->view('email/email_recover',$email_data);
				die('---');
				$forgot = array('status'=>true, 'message'=>'Please check your Inbox or Spam Folder');
				//'Silakan periksa email anda. Terutama pada SPAM'
                    }
 
			$this->session->set_flashdata('forgot', $forgot);
			redirect($_SERVER['HTTP_REFERER'],1);
			exit('');
            die('stop');
            
			$respon=$this->load->view('guest/data/forgot_data',array('post'=>$post),true);
			$json=@json_decode($respon,true);
			$respon=is_array($json)?$json:$respon;
//			die('<pre>'.print_r($respon,1));
/*
			$this->load->driver('advforex');
			$forgotPassword=$this->advforex->member->forgot()?false:true;
*/			

		}
		else{
			
		}
                
		$this->param['title']='Recover your Live Account'; 
		$this->param['content']=array(
			'modal',
			'forgot', 
		);
		$this->param['footerJS'][]='js/login.js';
		$this->showView(); 
	}
	
	function forgot_process(){
		$raw=$this->input->get('t');
		$ar=json_decode(base64_decode($raw),true);
		//print_r($ar);
		$email=$ar['email'];
		$recoverid=$ar['token'];
		$raw=_localApi('users','detail',array($email));
		$user=isset($raw['data'])?$raw['data']:array();
		$email_data=array($ar,'email'=>$email);
		$email_data['recoverid']=$recoverid;
		$email_data['user']=$user;
		//$email_data['show_html']=true;
		//echo_r($email_data);exit;
		$forgot = array('status'=>true, 'message'=>'Please Check Your Email ('.trim($email).'). Including SPAM Folder if not found. ');
		//$forgot['message'].=site_url('guest/recover/'.$recoverid);
		//'<pre>'.print_r($email_data,1).'</pre>';
		//$this->load->view('email/email_recover',$email_data);
		if( defined('LOCAL')){
			$forgot['message'].="link : ".site_url('guest/recover/'.$recoverid);
			//http://advance.forex/index.php/guest/recover/201628707
		}
		$pesan = $this->load->view('email/email_recover_user_email',$email_data,true);
		//die($pesan);
		_send_email($email,$subject='[Salmamarkets] Recover User Acoount',$pesan);

		$this->session->set_flashdata('forgot', $forgot);
		redirect(site_url('guest/forgot'),1);
	}

	function __CONSTRUCT(){
            parent::__construct();
            
            $this->param['today']=date('Y-m-d');
            $this->param['folder']='guest001/';
            $this->param['baseFolder']='guest001/';
            $this->load->helper('form');
            $this->load->helper('formtable');
            $this->load->helper('language');
            $this->load->helper('api');
            $this->load->helper('db');
            $this->load->model('forex_model','forex');
            $this->load->model('account_model','account');
            $this->load->model('country_model','country');
            $defaultLang="english";
            $this->lang->load('forex', $defaultLang);
            $this->param['fileCss']=array(	
                    'css001/style_salmamarkets.css',
                    'css001/bootstrap.css',
            );
            $this->param['fileJs']=array(
                    'js/jquery-1.7.min.js',
            );

            $this->param['shortlink']=base_url();
            $this->param['footerJS']=array(	 
                    'js/bootstrap.min.js',
                    'js/formValidation.min.js',
                    'js001/scripts.js'
            );

            $this->param['description']="Trade now with the best and most transparent forex STP broker";

            $this->param['emailAdmin']=$this->forex->emailAdmin; 
            logCreate('Guest Controllers','start');
            logCreate(current_url(),'url');
	}
/*==========================*/
	public function register_agent_old($agent=false,$raw=false){
		$this->load->library('session');
		$this->param['fullregis']=true;
		$this->param['statAccount']='agent';
		$this->param['fullregis']=true;
		$this->param['statAccount']='agent';
		
		if($this->session->flashdata('register')){
			$this->param['register']=$this->session->flashdata('register');
			logCreate('session register valid','info');
		}
		
		if($raw!='0'){
			$ar=explode("-",$raw);
			logCreate("agent ref:$raw id:{$ar[0]}","info");
			$num=trim($ar[0]);
			$this->session->set_flashdata('agent', $num);
			logCreate('parameter agent:'.$num,'info');
			redirect(base_url('welcome'),1);
			exit();
		}
		else{
			$num=$info=$this->session->flashdata('agent');
			$this->param['agent']=$num!=''?$num:'';
		}

		//$this->param['showAgent']=true;
		$this->param['title']='Open Live Account'; 
		if(!isset($this->param['formTitle'])) 
			$this->param['formTitle']=$this->param['title'];
		$this->param['content']=array(
			//'modal',
			'welcome', 
		);
		$this->showView();
		//$this->showView('newbase001_view');
	}

    function success(){
            $this->load->library('session');
            $this->param['session'] = $login=$this->session->userdata( ); 
            //echo_r($this->param['session']);exit;
            $this->param['fullregis']=true;
            $this->param['statAccount']='agent';
            $this->param['agent']=false;
            $this->param['showAgent']=false;
            $this->param['showForm']=false;

            $this->param['title']='Register Success'; 

            $this->param['content']=array(
                    'success', 
            );
            $this->showView();
    }
        
    private function parse_params($params){
        foreach($params as $name => $values){
            $this->param[$name]=$values;
        }
    }
    
}