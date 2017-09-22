<?php
defined('BASEPATH') OR exit('No direct script access allowed');

 class MY_Controller extends CI_Controller {
    function __CONSTRUCT(){
            parent::__construct(); 
            $this->load->library('session');
            $this->load->model('users_model');
            $session = $this->param['session']=$this->session->all_userdata();
            logCreate("core/My |path:".current_url()."|session:".json_encode($session));
//=============BASIC PARAMS=================
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
        
    }
//---------Tidak diketahui kegunaannya?	
	public function runApi(){
		$url=$this->config->item('api_url');		
		$param['app_code']='9912310';
		$param['module']='forex';
		$param['task']='register';
		$result=_runApi($url, $param);
 
	}
	
	function localApi($api='',$function='', $data=array()){
            logCreate('core |localApi | data:'.json_encode($data));
		return _localApi( $api,$function,$data);
	}
	
	function localApi_old($api='',$function='', $data=array()){
		$CI =& get_instance();
		$param=array(
			'api'=>$api,
			'function'=>$function,
			'data'=>$data
		);
		$id_random=dbId('random');
		$url=site_url('rest/'.$param['api']);
		$url.="?r=".$id_random;
		
		$api_name=ucfirst(strtolower($api));
		$controllers=APPPATH.'/controllers/rest/'.$api_name.'.php';
		logCreate('load '.$api_name."| ".$controllers );
		require_once $controllers;
		logCreate('load class');
		$class = new $api_name();
		logCreate('run class');
		$res = $class->index_post($param);
		echo 'new';echo_r($res);exit;
		$param['result']=json_encode($res);
		$param['id']=dbId('api');
		$param['data']=json_encode($data);
		unset($param['api']);
		$table=trim('rest_'.$api);
		if(!$CI->db->table_exists($table)){
			$sql="create table IF NOT EXISTS `$table` like `rest_temp`";
			dbQuery($sql);
		}
		dbInsert($table, $param);
		return $res;
	}
	
	public function data(){
		$this->param['session']=$this->session->all_userdata();
		$this->checkLogin();
		$raw=array();
		$aTime=array(microtime());
	// die('check login');
		$url=$this->config->item('api_url');
		$this->load->helper('api');
		
		$session=$this->param['session'];
	//	$detail=$this->users_model->gets($session['username'] );
		$aTime['user get']=microtime();
        /*
		if($detail==false){
                    $detail=$this->account->detail($session['username'],'accountid');
                    $aTime['account get']=microtime();
                    if($detail==false){
			logCreate('no username','error');
			redirect(site_url("login")."?err=no_user" );
                    }
		}
		else{}
         * $this->param['userlogin']=$detail;
         * 
         */
		
		
		$respon=array(		
			'html'=>print_r($_REQUEST,1), 
		);

		$raw=$this->input->post();
		$type=$driver_name=$this->input->post('type','unknown'); 
		if($type=='unknown'||$type=='')
			$type=$driver_name=strtolower($this->input->get('type','unknown'));

		$message='unknown data type';
		$open= $this->param['folder']."data/".$type."_data";
		if(true){
		//is_file('application/views/'.$open.".php")
			$param=array(
				'post'=>$this->convertData(),
				'get'=>$this->input->get(),
				'post0'=>$this->input->post(),
				'userlogin'=>$this->param['userlogin']
			);
			
			$data=array( $type);
			$valid_drivers= $this->config->item('valid_drivers');
			$aTime['check valid driver']=microtime();
			$driver_core = 'advforex';
		//	$list_driver=$this->{$driver_core}->valid_drivers;
			if( !in_array($driver_name, $valid_drivers)){
				$res =array('status'=>false, $driver_name.'(FAIL)',$driver_core.'(OK)',
				'tambahkan di list driver',$valid_drivers
				);
				$aTime['driver _'.$driver_name.'_ not found']=microtime();

			}
			else{
				$res= $respon_valid = _localApi('datatable','valid', $data );
				$aTime['check _'.$driver_name.'_ valid datatable']=microtime();
			}
			
			$run=false;
			
			if(isset($res['data']['status'])&&$res['data']['status']==true){
				$data[]=$param;
				logCreate('func data run: datatable => execute (start)');
				$res= _localApi('datatable','execute', $data );
				
				$aTime['run localApi']=microtime();
				$ar=isset($res['data'])?$res['data']:$res;
				$ar[]='local api';
				$run=true;
				logCreate('func data run: datatable => execute (done)');
			}
			else{
				$raw=$res;
			}
			//echo "---";echo_r($ar);exit();
			if($run==false){
			//	die( '???');
			}

			if($run==false){
			//old
				$aTime['no api']=microtime();
				if(is_file('application/views/'.$open.".php")){
			//		die($open);
					$raw=$this->load->view($open, $param, true);
					$aTime['run view']=microtime();
					$ar=json_decode($raw,true);
					$ar[]='view '.$open;
				}
				else{
					$ar=false;
				}
			}
			//echo_r($ar);exit();
			if(is_array($ar)){
                            $respon=$ar;
                           // $respon['raw']=array($data,$param,$res );
                            $respon['time']=$aTime;
                            $respon['valid']=isset($respon_valid)?$respon_valid:false;
                            $respon[]='ok';
                            
                            if(ENVIRONMENT != 'development'){
                                unset($respon['sql'],$respon['sql'],$respon['params'],$respon['raw']);
                                $respon['clean']=true;
                            }
			//	logCreate($respon);
                            if(!isset($respon['status'])){ 
                                echo json_encode($respon);
                                exit(); 
                            }

                            if($respon['status']==true){
                                    $ok=1;
                            }
                            else{
                                    $message=isset($respon['message'])?$respon['message']:false;
                            }
			}
			else{
                            logCreate("unknown :".json_encode($raw));
                            $this->errorMessage('267',$raw,$message);
			}
		}
		else{
                    logCreate("unknown :".$open);
		}
		
		if(!isset($ok)){
                    $this->errorMessage('266',$message);
		}
                
                
		
		$this->succesMessage($respon);
	}
	
	protected function convertData(){
	$post=array();
		if(is_array($this->input->post('data'))){
			foreach($this->input->post('data') as $data){
				if(isset($data['name'])){
					$post[$data['name']]=$data['value'];
				}
			}
		}else{}
		return $post;
	}
	
	public function api(){		
		$module=$this->input->post('module');
		$task=$this->input->post('task');
		$appcode=$this->input->post('app_code');
		$aAppcode=$this->config->item('app_code');
		if(array_search($appcode, $aAppcode)!==false){
			$this->load->model('forex_model','modelku');
			$param=$this->input->post('data');
			$function= strtolower($module ).ucfirst(strtolower($task )); 
			$file='views/api/'.$function.'_data.php';
			if(is_file($file)){
				$res =$this->load->view('api/'.$function.'_data', $param,true);
				$respon=json_decode($res,1);
			}else{ 
				$this->errorMessage('277','unknown action');
			}
		}else{ 
			$this->errorMessage('276','unknown app code');
		}
		
		if(isset($respon['succes'])){	
			$this->succesMessage($respon);
		}else{ 
			$respon=array( 
				'raw'=>$res,
				'req'=>$_REQUEST
			);
			$this->errorMessage('334','unknown error',$respon );
		}
	}
	
	protected function succesMessage($respon){
        //    $respon[]='success msg';
            
		echo json_encode(
		  array(
			'status'=>true,
			'code'=>9, 
			'data'=>$respon,
			'message'=>'succes'
		  )
		);
		
		exit();	
	}
	
	protected function errorMessage($code, $message,$data=array()){
		$json=array(
			'status'=>false,
			'code'=>$code, 
			'message'=>$message 
		  );
		  
		if(count($data)!=0) 
			$json['data']=$data;
		
		echo json_encode($json);
		logCreate($json,"error");
		
		exit();
	}
	 
	protected function showView($target='newbase_view'){
		$name=$this->uri->segment(2,'');		
		if($name!=''){
			$jsScript=$this->param['folder'].$this->uri->segment(2).".js";
			$this->param['dataUrl']=  $this->uri->segment(1). "_".$name;
			$this->param['script']=$this->param['type']=$name; 
			
			if(isset($this->param['content'])&&!is_array($this->param['content'])){
				$this->param['load_view']= 
					$this->param['folder'].$this->param['content'].'_view';
				
			}else{}
			 
			
		}else{ 
			 
		}
		 
		$this->load->view($target, $this->param);
	
	}
	
        function checkLogin(){
            $session= $this->session->all_userdata();
            $session2 = $this->session->userdata();
            logCreate('admin |session2:'.json_encode($session2));//$this->param['session'];
            
            if(!isset($session['login'])){
                logCreate('admin |checkLogin | error:'.json_encode($session));
               // echo_r($session);
                js_goto(site_url('login')."?r=session_error");
                die('error?');
                
            }
            
            $login = $this->localapi_model->token_get($session['login']);
            $this->localapi_model->token_update($session['login']);
            //echo_r($session);echo_r($login);exit;
            if(!isset($login['username'])){
                js_goto(site_url('login')."?r=login_not_found");
            }

            $email = $login['email'];
            $user_detail = $this->users_model->getDetail($email);
 
            $this->param['userlogin']= $user_detail;
            $res= _localApi('account','lists',array($login['email']));
            $detail=isset($res['data'])?$res['data']:array();
            $this->param['accounts']=$detail;
            return true;
        }
        
	private function checkLogin_old(){
		$session=$this->param['session'];
		if(isset($session['username'])&&isset($session['password'])){
		$res= _localApi('users','loginCheck',array($session['username'],$session['password']));
		}
		else{
			$res=false;
		}
		$detail=isset($res['data']['valid'])?$res['data']['valid']:false;
		if($detail==false){
			echo_r($res);var_dump($session);exit();
		//	logCreate('no username/accid:'.$session['username'],'error');
			redirect("login");
		}
		//echo_r($res);var_dump($session);exit();
		$session['expire']=strtotime("+20 minutes");
		$array=array( 
			'username'=>$session['username'],
			'password'=>($session['password']),
			'expire'=>$session['expire']
		);
		$this->session->set_userdata($array);
		$res= _localApi('users','detail',array($session['username']));
		$detail=isset($res['data'])?$res['data']:array();
		$this->param['userlogin']=$detail;
		$res= _localApi('account','lists',array($session['username']));
	//	echo_r($res);die();
		$detail=isset($res['data'])?$res['data']:array();
		$this->param['accounts']=$detail;
	}

 }