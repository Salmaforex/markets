<?php
defined('BASEPATH') OR exit('No direct script access allowed');

 class MY_Controller extends CI_Controller {
	function __CONSTRUCT(){
		parent::__construct(); 
		$this->load->library('session');
		$this->load->model('users_model');
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
		$detail=$this->users_model->gets($session['username'] );
		$aTime['user get']=microtime();
		if($detail==false){
			$detail=$this->account->detail($session['username'],'accountid');
			$aTime['account get']=microtime();
			if($detail==false){
			logCreate('no username','error');
			redirect(site_url("login")."?err=no_user" );
			}
		}
		else{}
		$this->param['userlogin']=$detail;
		
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
				$respon['raw']=array($data,$param,$res );
				$respon['time']=$aTime;
				$respon['valid']=isset($respon_valid)?$respon_valid:false;
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
	
	private function checkLogin(){
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

	private function checkLogin_old(){
		$session=$this->param['session'];
		logCreate('controller:member |checkLogin |username:'.$session['username'] );
		$detail=$this->account->detail($session['username'],'username');
		logCreate('username found:'.count($detail) );
		if($detail==false){
			logCreate('session accountid:'.$session['username']);
			$detail=$this->account->detail($session['username'],'accountid');
		}
		
		if($detail==false){
			logCreate('no username/accid:'.$session['username'],'error');
			redirect("login");
		}
		else{}
		logCreate('username:'.$session['username'],'error');
		$post=array();
		if(isset($session['expire'])){
			if($session['expire']<strtotime("now")){
				logCreate('User Expired '.$session['expire']." vs ". strtotime("now") );
				$post['message']='Please Login Again';
				$this->session->set_flashdata('login', $post);
				$array=array( 
					'username'=>null,
					'password'=>null,
					'expire'=>strtotime("+12 minutes")
				);
				$this->session->set_userdata($array);
				
				redirect("login/member");
			}
			else{
				$session['expire']=strtotime("+10 minutes");
				logCreate('add User Expired '.$session['expire']  );
			}
		}
		else{
			logCreate('User don\'t have Expired' );
			$post['message']='Your Login Has expired?';
			$this->session->set_flashdata('login', $post);
			$array=array(  
					'expire'=>strtotime("+12 minutes")
				);
				$this->session->set_userdata($array);
			redirect(base_url("member"));
			$session['expire']=strtotime("+10 minutes");
		}
		
		if($session['password']==$detail['masterpassword']){
			logCreate('password OK:'.$session['username'],'error');
			$array=array( 
				'username'=>$session['username'],
				'password'=>($session['password']),
				'expire'=>$session['expire']
			);
			$this->session->set_userdata($array);
			$this->param['detail']=$this->param['userlogin']=$detail;
			$uniqid=url_title(trim($detail['id']).' '.$session['username'],'-');
			$this->param['urlAffiliation']=base_url('register/'.$uniqid);
		}
		else{
			logCreate('wrong password','error');
			$post['message']='Please Login Again';
			$this->session->set_flashdata('login', $post);
			redirect("login");			
		}
		
	}
	 
 }