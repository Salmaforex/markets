<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/SmtpApi.php';
class Forex extends CI_Controller {
	public $param,$oApi;
/***
Daftar Fungsi Yang Tersedia :
*	registerApi_old($stat=1)
*	registerApi($stat=1)
*	backupDb()
*	error404()
*	deposit_value()
*	widtdrawal_value()
*	register($agent=false,$raw=false)
*	agent()
*	index()
*	forgot_pass($start=null, $to=null)
*	data()
*	convertData()
*	api()
*	succesMessage($respon)
*	errorMessage($code, $message,$data=array())
*	showView()
*	__CONSTRUCT()
*	fake($status='none')
*	test_email()
*	emailApi($to,$subject,$message)
*	email_send()
*	email_send_1()
***/
	public function registerApi_old($stat=1){
		log_message('info','register Api in session');
		$tmp=$this->load->view('api/forexRegister_data',$this->param,true);
		$res=json_decode($tmp, true); 
		if($stat==1)
			print_r($res);
	}

	function registerApi($stat=1){
		$this->load->driver('advforex'); /*gunakan hanya bila diperlukan*/
		$driver_core = 'advforex';
		$driver_name='register';
		$func_name='execute';
		if( !method_exists($this->{$driver_core}->{$driver_name},$func_name) ){
			$output=array('function "'.$func_name.'" unable to declare');
			die(json_encode($output));
		}
		else{
			$row=$params=false;
			$params=$this->{$driver_core}->{$driver_name}->{$func_name}($row);
			echo '<pre>';print_r($params);
		}
		
	}
	
	public function backupDb(){
		$name1=date("ymdh");
		$name2=time();
	//	echo "$name1<br/>$name2";
		$this->param['tableTarget']='z_api';
		//'zlog_'.time();
		log_message('info','register Api in session');
		$tmp=$this->load->view('api/backup_data',$this->param,true);
		$res=json_decode($tmp, true);
		echo '<pre>';
			print_r($res);
	}
	
	public function error404(){
		logCreate('error 404 link:'.current_url());
		//die('unknown link');
		echo 'unknown link';
		 
		js_goto(site_url('member')."?f=".date("Ymdhis")."&src=".current_url() );
	}
	
	public function deposit_value()
	{
		$curr=$this->input->post('currency');
		if($curr=='')$curr='IDR';
		$def=14000;
		$res=$this->forex->rateNow('deposit',$curr);
		
		if(isset($res['value'])){
			$def=$res['value'];
		}else{}
		//echo $def;
		$result=array('status'=>true, 'value'=>(float)$def,'res'=>$res);
		$result['show_txt']=isset($res['symbol'])?$res['symbol']." ".number_format($res['value'],2):number_format($res['value'],2);
		echo json_encode($result);
	//	exit();
	}
	
	public function widtdrawal_value()	
	{
		$curr=$this->input->post('currency');
		if($curr=='')$curr='IDR';
		$def=13000;
		$res=$this->forex->rateNow('widtdrawal',$curr);
		
		if(isset($res['value'])){
			$def=$res['value'];
		}else{}
		//echo $def;
		$result=array('status'=>true, 'value'=>(float)$def,'res'=>$res);
		$result['show_txt']=isset($res['symbol'])?$res['symbol']." ".number_format($res['value'],2):number_format($res['value'],2);
		echo json_encode($result);
		exit;
		$def=13000;
		$res=$this->forex->rateNow('widtdrawal');
		if(isset($res['value'])){
			$def=$res['value'];
		}else{}
		echo $def;
	//	exit();
	}

	public function register($agent=false,$raw=false)
	{
		redirect(site_url('register_agent/'.$agent.'/'.$agent),1);
		redirect(site_url('welcome/'.$agent.'/'.$agent),1);

		$this->load->library('session');
		$this->param['statAccount']='member';
		if($agent!=false){
			$this->param['fullregis']=true;
			$this->param['statAccount']='agent';
		}
		
		if($raw!='0'){
			$ar=explode("-",$raw);
			logCreate("agent ref:$raw id:{$ar[0]}","info");
			$num=trim($ar[0]);
			$this->session->set_flashdata('agent', $num);
			redirect(site_url('forex/register'),1);
			exit();
		}
		else{
			$num=$info=$this->session->flashdata('agent');
			$this->param['agent']=$num!=''?$num:'';
		}
		$this->param['title']='OPEN LIVE ACCOUNT'; 
		if(!isset($this->param['formTitle'])) 
			$this->param['formTitle']=$this->param['title'];
		$this->param['content']=array(
			'modal',
			'form', 
		);
		$this->showView(); 
		
	}
	
	public function agent()
	{
		$this->param['formTitle']="Open Patner Account";

		redirect(site_url('guest/agent'),1);
		$this->register(true,true);

	}
	
	public function index()
	{
		//if(!isset($this->session))echo 'error<pre>';var_dump($this->session);var_dump($_SESSION);die('--');
		redirect(site_url('welcome'));
		$this->register();
	}
	
	public function forgot_pass($start=null, $to=null){
		$iDays= 24*3600;
		if($start==null){ 
			$start='2016-07-02'; 
		}
		if($to==null){
			$i10day=$iDays*10;
			$to=date("Y-m-d",strtotime($start)+$i10day);			
		}
		
		for($i=strtotime($start);$i<=strtotime($to);$i+=$iDays){
			$sDate=date('Y-m-d',$i);
			$data=$this->account->lists($sDate, 'created');
			foreach($data['data'] as $row){
				$post=array('email'=>$row['email']);
				$respon=$this->load->view('guest/data/forgot_data',array('post'=>$post),true);
				$json=@json_decode($respon,true);
				$respon=is_array($json)?$json:$respon;
				if($respon['status']!=1){
					logCreate('forgot_pass status!=0|'.json_encode($respon));//echo '<pre>'.print_r($respon,1);die();
				}
			}
			logCreate('forgot_pass ('.$sDate.') data:'.json_encode($data));//echo '<pre>==='.print_r($data,1);die();
		}
		echo 'done '.$start.'('.strtotime($start).') - '.$to;
	}
	
	public function data()
	{
		$url=$this->config->item('api_url');
		$this->load->helper('api');
		$respon=array(		
			'html'=>print_r($_REQUEST,1), 
		);
		$type=$this->input->post('type','unknown'); 
		$message='unknown data type';
		$raw=$this->convertData(); 
		
		if($type=='request'){
			$respon['title']='NEW LIVE ACCOUNT (CREATED)';
			if($raw['statusMember']=='AGENT'){
				$respon['title']='NEW PATNER ACCOUNT (CREATED)';
			}
			$param['data']=$this->convertData();
			$stat=$this->forex->saveData($param['data'],$message);

			if($stat!==false){
				
				$respon['html']="Your Opening Live Account Was Sent Successfully. Please Check your Email in few minutes. Thanks.<br/>
				Pembukaan Akun Tranding Anda telah Sukses. Silahkan Cek Kembali Email Anda beberapa saat lagi";
				$ok=1;
				if($raw['statusMember']=='AGENT'){
					$respon['html']='Your Opening Patner Account Was Sent Successfully. Please Check your Email in few minutes. Thanks.<br/>
				Pembukaan Akun Patner Anda telah Sukses. Silahkan Cek Kembali Email Anda beberapa saat lagi';
				}
				$url=$this->config->item('api_url');		
				$param['app_code']=$this->config->item('app_code')[0];
				$param['module']='forex';
				$param['task']='register';
//-------------------TESTED				
				$result=_runApi($url, $param); //test
				$this->registerApi(0);
			}
		}
		
		$open= $this->param['folder']."data/".$type."_data";
		if(is_file('views/'.$open.".php")){
			$param=array(
				'post'=>$this->convertData(),
				'get'=>$this->input->get(),
				'post0'=>$this->input->post()
			);
			$raw=$this->load->view($open, $param, true);
			$ar=json_decode($raw,true);
			if(is_array($ar)){
				$respon=$ar;				
				logCreate($respon);
				if(!isset($respon['status'])){ 
					echo json_encode($respon);exit(); 
				}
				if($respon['status']==true){
					$ok=1;
				}
				else{
					$message=$respon['message'];
				}
			}
			else{
				logCreate("unknown :".htmlentities($raw));
				$this->errorMessage('267',$raw,$message);
			}
		
		}
		if(!isset($ok)){
			$this->errorMessage('266',$message);
		}
		
		$this->succesMessage($respon);
	}
	
	private function convertData()
	{
	$post=array();
		if( $this->input->post('data') ){
			foreach($this->input->post('data') as $data){
				$post[$data['name']]=$data['value'];
			}
		}
		return $post;
	}
	
	public function api()
	{		
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
	
	private function succesMessage($respon)
	{
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
	
	private function errorMessage($code, $message,$data=array())
	{
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
	 
	private function showView(){
		$name=$this->uri->segment(2,'');		
		if($name!=''){
			$jsScript=$this->param['folder'].$this->uri->segment(2).".js";
			$this->param['dataUrl']=  $this->uri->segment(1). "_".$name;
			$this->param['script']=$this->param['type']=$name;
			
			$this->param['openScript']=$jsScript;
 			
			if(isset($this->param['content'])&&!is_array($this->param['content'])){
				$this->param['load_view']= 
					$this->param['folder'].$this->param['content'].'_view';
				
			}else{}
 			
		}else{ 
			$controller=$this->uri->segment(1);
			if($controller=='')$controller='forex';
			redirect(site_url().$controller."/index","refresh");	
		}
		 
		$this->load->view('base_view', $this->param);
	
	}
	
	function __CONSTRUCT(){
	parent::__construct(); 
	//	$this->load->library('session');
		date_default_timezone_set('Asia/Jakarta');
		$this->param['today']=date('Y-m-d');
		$this->param['folder']='forex/';
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
			 
		);
		$this->param['fileJs']=array(
			
		);
		
		$this->param['shortlink']=site_url();
		$this->param['footerJS']=array(			
			
		);
 
		$this->param['description']="Trade now with the best and most transparent forex STP broker";
		 
		$this->param['emailAdmin']=$this->forex->emailAdmin; 
		 
		if($this->input->post())
			logCreate($this->input->post(),'post');
		
		$sPubKey= $this->config->item('sendpulse_pubkey');
		$this->oApi = new SmtpApi($sPubKey);
	}
	
	public function fake($status='none')
	{ 
		if(!defined('LOCAL')){
			redirect(site_url());
		}
	/*	
		if($this->input->get('privatekey')!=$this->forex->forexKey()){
			$message="there is nothing to see but us tree";
			$this->errorMessage('341',$message);
		}
	*/	
		if(defined('LOCAL')){
			if($status=='none'){
				$res=array(
					'responsecode'=>0,
					'accountid'=>'9'.date("Ymdhis"),
					'masterpassword'=>date("his"),
					'investorpassword'=>date("dmy"),
				);//$res= "1;11001724"; 
				 
			}
			
			if($status=='activation'){
				$res="1";
			}
			if($status=='update'){
				$res0='{"responsecode":"0","accountid":"9'.date("Ymdhis").'"}';
				$res=json_decode($res0);
			}
			if($status=='updateBalance'){
				$res0='{"balance":"'.rand(111111,999999) .'","responsecode":"0","accountid":"7001189"}';
				$res=json_decode($res0);
			}
			$raw=array();
		/*	
			if(!isset($res)){ 
				$res='1;11001724'; 
				
				$id=$this->forex->accountActivation(5,$raw);
				$res.="id:$id";
			}
			$this->succesMessage($res);
		*/
			echo json_encode($res);
		}else{ 
			echo "no respond";
		}
	}

	public function test_email(){
		$oApi = $this->oApi;
		$from= $this->config->item('email_from');

    $aEmail = array(
        'html' => '<p>Hello world. ini cuma testing</p>',
        'text' => 'Hello world. ini cuma testing',
        'encoding' => 'UTF-8',
        'subject' => 'judul ada disini',
        'from' => array(
            'name' => $from['name'],
            'email' => $from['email'],
        ),
        'to' => array(
            array(
                'email' => 'gundambison@gmail.com'
            ),
        )
/*		,
        'bcc' => array(
            array(
                'name' => 'Recipient Name',
                'email' => 'recipient3@example.com'
            ),
            array(
                'email' => 'recipient4@example.com'
            ),
        ), */
    );
//	echo '<pre>'.print_r($aEmail,1);die();
    $res = $oApi->send_email($aEmail);
	var_dump($res);
    if ($res['error']){ // check if operation succeeds
		logCreate('email test gagal');
        die('Error: ' . $res['text']);
    } else {
		logCreate('email test berhasil');
        // success
		echo 'berhasil<pre>'.print_r($res,1).print_r($aEmail,1);
    }


	}
	function emailApi($to,$subject,$message){
		$sPubKey= $this->config->item('sendpulse_pubkey');
		$email_from= $this->config->item('email_from');
		//var_dump($sPubKey);
		$oApi = new SmtpApi($sPubKey);
		$res = $oApi->ips();
		if ($res['error']){ // check if operation succeeds
			return ('Error: <pre>' . print_r($res,1));
		}
		
		$aEmail = array(
			'html' => $message,
			'text' => strip_tags($message),
			'encoding' => 'UTF-8',
			'subject' => $subject,
			'from' => array(
				'name' => 'No reply',
				'email' => $email_from,
			),
			'to' => array(
				array(
					'email' => $to
				),
			),
			'bcc' => array(
				array(
					'name' => 'Admin Devel',
					'email' => 'admin@dev.salmaforex.com'
				),
			),
		);
		$res = $oApi->send_email($aEmail);
		if ($res['error']){ // check if operation succeeds
			return  ('Error: <pre>' . print_r($res,1));
		}
		else {
			if (empty($res['data'])){
				// empty array – email address is not specified
				return 'empty'.print_r($res,1);
			} else {
				return json_encode($res['data']);
			}
		}
		return true;
	}

	function email_send(){
		$target="media/email";
		$max=10;
//==========silakan dinaikkan
		$sql="INSERT INTO  `mujur_accountdocument` (
`email` ,
`status` ,
`upload` ,
`filetype` ,
`modified`
)
select a.email, '0', 'media/uploads/xxxx', 'image/jpeg', now()
from mujur_account a left join mujur_accountdocument ad on a.email=ad.email where ad.id is null and a.email like '%@%'";
		dbQuery($sql);
		$n=0;
		$not_valid=array(".","..");
		if ($handle = opendir($target)){
			while (false !== ($entry = readdir($handle))) {
				echo "\n<br>Read :".$entry;
				if( array_search($entry, $not_valid)===false){
					echo "|allow";
					logCreate('file:'.$entry);
					$txt=file_get_contents($target.'/'.$entry);
					$json=@json_decode($txt,true);

					if(is_array($json)&&isset($json['to'])){
						$json['message']=isset($json['message'])?base64_decode( $json['message'] ):'';
						echo '<hr/>'.implode('<p/>',$json);						
						$OK=true;
						//------check email
						
						//------lebih dari max?
						if($OK)
							$OK=$n<$max?true:false;

						if($OK){
							$this->emailApi(trim($json['to']), $json['subject'], $json['message']);
						//@mail(trim($json['to']), $json['subject'], $json['message'], $json['headers']);
							echo '|send email';
							$n++;
		//hapus
							$rawEmail=array(
								$json['subject'], $json['headers'],$json['message'],'send email'
							);
							$data=array( 'url'=> $json['to'],
								'parameter'=>json_encode($rawEmail),
								'error'=>2
							);
							$this->db->insert($this->forex->tableAPI,$data);
							unlink($target.'/'.$entry);
						}
					}
					else{
						echo 'not email';
					}

				}else{}

			}

		}else{}
		
		$arr=array('to'=>'satu', 'subject'=>'subjek','message'=>base64_encode('hello world'),'headers'=>'this is headers');
		//echo '<br>'.json_encode($arr);
		//$res=$this->emailApi("gundambison@gmail.com", "test email ".date("Y-m-d"), "pesan random ");echo 'send:';echo_r($res);
	}

	function email_send_1(){
		$target="media/email";
		$max=10;
//==========silakan dinaikkan
		$sql="INSERT INTO  `mujur_accountdocument` (
`email` ,
`status` ,
`upload` ,
`filetype` ,
`modified`
)
select a.email, '0', 'media/uploads/xxxx', 'image/jpeg', now()
from mujur_account a left join mujur_accountdocument ad on a.email=ad.email where ad.id is null and a.email like '%@%'";
		dbQuery($sql);
		$n=0;
		$not_valid=array(".","..");
		if ($handle = opendir($target)){
			while (false !== ($entry = readdir($handle))) {
				echo "\n<br>Read :".$entry;
				if( array_search($entry, $not_valid)===false){
					echo "|allow";
					logCreate('file:'.$entry);
					$txt=file_get_contents($target.'/'.$entry);
					$json=@json_decode($txt,true);

					if(is_array($json)&&isset($json['to'])){
						$json['message']=isset($json['message'])?base64_decode( $json['message'] ):'';
						echo '<hr/>'.implode('<p/>',$json);						
						$OK=true;
						//------check email
						
						//------lebih dari max?
						if($OK)
							$OK=$n<$max?true:false;

						if($OK){
							
							@mail(trim($json['to']), $json['subject'], $json['message'], $json['headers']);
							echo '|send email';
							$n++;
		//hapus
							$rawEmail=array(
								$json['subject'], $json['headers'],$json['message'],'send email'
							);
							$data=array( 'url'=> $json['to'],
								'parameter'=>json_encode($rawEmail),
								'error'=>2
							);
							$this->db->insert($this->forex->tableAPI,$data);
							unlink($target.'/'.$entry);
						}
					}
					else{
						echo 'not email';
					}

				}else{}

			}

		}else{}
		
		$arr=array('to'=>'satu', 'subject'=>'subjek','message'=>base64_encode('hello world'),'headers'=>'this is headers');
		//echo '<br>'.json_encode($arr);
		//batchEmail('satu@gdsdas.com','subject','message saya','headers');
	}

}
