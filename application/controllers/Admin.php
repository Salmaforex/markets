<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller {
	public $param;
	public $folderUpload;

	public function detail(){
		$this->profile();
	}
        
    public function profile(){
            $this->checkLogin();
            $this->param['title']='SECURE ACCOUNT | Profile'; 
            $this->param['content']=array(
                    'detail', 
            );
            $this->param['footerJS'][]='js/login.js';
            $this->showView(); 

    }

    public function listApi($type=null){
	$types=array('account', 'api', 'deposit', 'widtdrawal',
	'user','agent','approval','partner','patner_revenue');	
//		if(!defined('LOCAL')){
	$this->checkLogin();
//		}

        $this->param['title']='Secure Admin | '.$type; 
        $this->param['content']=array(
                'modal',			
        ) ;

        if(in_array($type, $types)){
                $this->param['content']='api/'.$type;
        }
        else{ 
                $this->param['content']='api/api';
                log_message('error', 'listApi not define');
                redirect(site_url('admin')."?not define");
        }
//datatables		
        $this->param['footerJS'][]='js/jquery.dataTables.min.js';
        $this->param['footerJS'][]='js/api.js';
        $this->param['fileCss']['dataTable']='css/jquery.dataTables.min.css';
        //echo_r(array_keys($this->param));exit();
        $this->showView(); 
    }
    

    
    private function checkLogin_admin(){
        $session=$this->param['session'];
        $login = $this->localapi_model->token_get($session['login']);
        $this->localapi_model->token_update($session['login']);
        //echo_r($session);echo_r($login);exit;
        if(!isset($login['username'])){
            js_goto(site_url('login')."?r=login_not_found");
        }
        
        $this->param['userlogin']= $login;
        return true;
                //$detail = $row['users'];
	//	driver_run($driver_core, $driver_name, $func_name='executed', $params=array());
		logCreate('admin |checkLogin |start');
		$row = driver_run('mujur', 'user_login',  'execute' );
		if($row['error']=='NO_VALID_SESSION'||$row['error']=='NO_USERNAME'){
			logCreate('admin |checkLogin | error:'.$row['error'],'error');
	//		echo_r($row );
			js_goto($row['url']);
		}
		//echo_r($row);exit;
		$page = $this->uri->segment(2);
		$page2 = $this->uri->segment(3);
		$this->param['userlogin']=$detail = $row['users'];
		if(isset($detail['typeMember'])){
			$typeMember=$detail['typeMember'];
			logCreate('admin |checkLogin |type='.$typeMember);
			if($typeMember=='staff'){
				$url=site_url($typeMember.'/'.$page.'/'.$page2)."?from=admin";
				js_goto($url);
			}
			if($typeMember=='member'){
				$url=site_url($typeMember.'/'.$page.'/'.$page2)."?from=admin";
				js_goto($url);
			}
			if($typeMember=='partner'||$typeMember=='patners'){
				$url=site_url( 'partner/'.$page.'/'.$page2)."?from=admin";
				js_goto($url);
			}
		}
		else{}
		logCreate('admin |checkLogin |cek detail='.count($detail) );
		if(isset($detail['name'])&&trim($detail['name'])!=''){
			//OK
		}
		else{
			if($page =='edit' || $page =='edit_master_password'){
				//ok
			}
			else{
				logCreate('admin |checkLogin |no username');
				$this->session->set_flashdata('notif',array('status'=>false,'msg'=>'Input Your Name'));
				js_goto( site_url('admin/edit').'?r=input_your_name');exit;
			}
		}
		
		logCreate('admin |checkLogin |End (1)' );
		$session=$this->param['session'];
		$res0=  $this->account->get_by_field($session['username'],'email');
		//==========DETAIL ALL
		logCreate('admin |checkLogin |detail all (1)' );
		$res=array();$agent=false;
		//if(defined('LOCAL')){ $agent=9999; }
		if(is_array($res0)){
			logCreate('admin |checkLogin |search found:'.count($res0) );
			foreach($res0 as $row){
				if(isset($row['id'])){
					$data_account=$this->account->gets($row['id'],'id');
					if(isset($data_account['agent'])&&$data_account['agent']!=''){
						$agent=$data_account['agent'];
					}
					$res[]=$data_account;
				}
				
			}
			
		}
		else{
			logCreate('admin |checkLogin |search failed' );
		}

		//$res0= $this->localApi( 'account','lists',array($session['username']));
		//echo_r($session);echo_r($res); die();
		//$detail=isset($res['data'])?$res['data']:array();
		logCreate('admin |checkLogin |account list and agent default' );
		$this->param['accounts']=$res;
		$this->param['agent_default']=$agent;
		
	}

	private function checkLogin_old(){
		$session=$this->param['session'];
		$res= _localApi('users','loginCheck',array($session['username'],$session['password']));
		$detail=isset($res['data']['valid'])?$res['data']['valid']:false;
		if($detail==false){
		//	echo_r($res);var_dump($session);exit();
			logCreate('no username/accid:'.$session['username'],'error');
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
        
	private function checkAdmin(){
	//	echo_r($this->param['userlogin']);
		if( $this->param['userlogin']['typeMember']!='admin'){
			//echo_r($this->param['userlogin']);exit;
			log_message('error', 'check Admin not define');
			$url=site_url('member')."?type=".$this->param['userlogin']['typeMember'];
			//die($url);
			js_goto($url);
	//		redirect('member');
		}
	}
	

	function __CONSTRUCT(){
	parent::__construct(); 		
		date_default_timezone_set('Asia/Jakarta');
		$this->param['today']=date('Y-m-d');
		$this->param['folder']='depan/';
		$this->param['active_controller']='admin';
		$this->load->helper('form');
		$this->load->helper('formtable');
		$this->load->helper('language');
		$this->load->helper('api');
		$this->load->helper('db');
		$this->load->model('users_model');
		$this->load->model('forex_model','forex');
		$this->load->model('country_model','country');
		$this->load->model('account_model','account');
		$defaultLang="english";
		$this->lang->load('forex', $defaultLang);
		
		$this->param['fileCss']=array(	
			'css/style.css',
			'css/bootstrap.css',
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
			'js/scripts_new.js'
		);
		$this->param['description']="Trade now with the best and most transparent forex STP broker";
		
		$this->param['emailAdmin']=$this->forex->emailAdmin;
		
		$this->param['session']=$this->session-> all_userdata(); 
		$this->param['baseFolder']='depan/';
		$this->param['noBG']=true;
		$this->folderUpload = 'media/uploads/';
		logCreate('start controller member');
		$this->param['title']='Secure Area';
		//===============BARU=============
		$this->param['fileCss']=array(	
			'css001/style_salmamarkets.css',
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
			'js/scripts_new.js',
			//'js001/scripts.js'
		);
		/*
		if($this->input->post())
			logCreate($this->input->post(),'post');
		*/
	}
	
	function update_password(){
	$this->checkLogin();
	$this->checkAdmin();
		$post=$this->input->post();
	/* in progress... rule password */
		if(!isset($post['password'])) {
			redirect($_SERVER['HTTP_REFERER']."?err=1");
		}
		if(strlen($post['password'])<6) {
			redirect($_SERVER['HTTP_REFERER']."?err=2");
		}
		/*
		if(strlen($post['password'])<6) {
			redirect($_SERVER['HTTP_REFERER']."?err=3");
		}
		*/
	//	echo_r($post);exit;
		$p=_localApi('users','update_password',$post);
		//echo_r($_SERVER['HTTP_REFERER']);
		redirect($_SERVER['HTTP_REFERER']);
	}
	
	function updateDocument($status=null, $userid=null){
            $stat_id=null;
            if($status=='active') $stat_id=1;
            if($status=='review') $stat_id=2;
            if($status=='inactive') $stat_id=0;

            if($stat_id!=null){
                    $data=$this->users_model->gets($userid,'u_id');
                    $username=$data['u_email'];
                    //echo_r($data);die();//die(print_r($data,1));
                    $this->users_model->updateDocumentStatus($username, $stat_id);
                    echo 'status sudah berganti menjadi '.$status;
                    js_goto( $_SERVER['HTTP_REFERER']);
            }
            else{
                    echo 'status tidak diketahui';
                    exit();
            }
	}

	function show_upload($userid=null){
		$users=$this->users_model->gets($userid,'u_id');
		$email=isset($users['u_email'])?$users['u_email']:false;
		$data=$this->users_model->document($email);
		//var_dump($users);echo_r($data);die('--'.$email);
		if(isset($data['filetype'])){
			
			if(is_file($data['udoc_upload'])){
				header('content-type:'.$data['filetype']);
				header('Content-Disposition: attachment; filename="'.url_title($email).'"');
				$txt=file_get_contents( $data['udoc_upload']);
				echo $txt;
				die;
			}
			else{
				
				header('content-type:image/jpeg');
				header('Content-Disposition: attachment; filename="no-file"');
				echo 'no file';
			//	echo_r($data);die('--no file--');
			}
		}
		else{
		//	echo_r($data);die();
		}
	}
	
	function save_users(){
            $params=$post=$this->input->post();
	//	echo_r($post);exit;
            $params['ud_detail']=  json_encode($params['detail'] );
            $this->users_model->update_detail($post['email'],$params);

    //========update detail forex
            $this->load->driver('advforex'); /*gunakan hanya bila diperlukan*/
            $driver_core = 'advforex';
            $driver_name='update_detail';
            $func_name='execute';
            if( !method_exists($this->{$driver_core}->{$driver_name},$func_name) ){
                    $output=array('function "'.$func_name.'" unable to declare');
                    die(json_encode($output));
            }
            else{
                    $row=$params=array($post['email']);
                    $params=$this->{$driver_core}->{$driver_name}->{$func_name}($row);
                    //echo_r($params);
            }
	//
	//	echo 'in progress';exit;
            js_goto($_SERVER['HTTP_REFERER']);
            
	}
	
	function edit_user($id){
            $this->checkLogin();
            $this->load->driver('advforex'); /*gunakan hanya bila diperlukan*/
            $params=array('id'=>$id,'type'=>"user_detail");
            $ar=$this->users_model->gets($id,'u_id' );
            //echo_r($ar);exit;
            $email=$ar['u_email'];
            $param=array(
                'post'=>$this->convertData(),
                'get'=>$this->input->get(),
                'post0'=>array('email'=>$email),
            );
            
            //site_url("admin/data")
            //$res=_runApi(site_url("admin/data"),$params);
            $type=$driver_name="user_edit";
            $driver_core = 'advforex';
            $ar = $this->{$driver_core}->user_edit->execute( $param );
            //echo_r($ar);
            
            $html = $ar['html'];
            $this->param['html']=$html;
            $this->param['title']='Secure Admin | edit '.$email; 
            $this->param['content']=array(
                    'edit_user'
            ) ;
            $this->showView(); 
	}

	function detail_user($id){
            $this->load->driver('advforex'); /*gunakan hanya bila diperlukan*/
            $params=array('id'=>$id,'type'=>"user_detail");
            $ar=$this->users_model->gets($id,'u_id' );
            //echo_r($ar);exit;
            $email=$ar['u_email'];
            $param=array(
                            'post'=>$this->convertData(),
                            'get'=>$this->input->get(),
                            'post0'=>array('email'=>$email),
            );

            $type=$driver_name="user_detail";
            $driver_core = 'advforex';
            $ar = $this->{$driver_core}->user->detail( $param );

            $html = $ar['html'];
            $this->param['html']=$html;
            $this->param['title']='Secure Admin | '.$ar['title']; 
            $this->param['content']=array(
                    'detail_user'
            ) ;
            
            $this->showView(); 
	}

	function send_email($id='',$status=''){
		if($status!='send'){
			$url0=site_url('admin/send_email/'.$id.'/send');
			echo "<h3 align='center'> Apakah email ini akan dikirim ulang? ".anchor($url0,'kirim email bila iya?')."</h3>";
		}
		$p=$this->forex->list_email($id);
		//print_r($p);
		$email=$p[0]['to'];
		$row= $p[0];
		$subject=$row['subject'];
		$message=$row['messages'];

		if($status!='send'){
			echo $message;
		}
		else{
		//	die('wait');
			_send_email($to=$email,$subject ,$message);
			echo "email sudah dikirim";
			/*
			$rawEmail=array(
				$subject, $headers,$message,'email ulang'
			);
			$data=array( 'url'=>json_encode($email),
				'parameter'=>json_encode($rawEmail),
				'error'=>2
			);
			$this->db->insert($this->forex->tableAPI,$data);
			*/
		}
		
	}
	
	function flow_email($id=0){
	$this->load->driver('advforex'); /*gunakan hanya bila diperlukan*/
	$driver_core = 'advforex';
	//$param1=array('post0'=>$param);
	$driver_name='forex_update_balance_credit';
	$func_name = 'send_email';
	$pesan=	$this->{$driver_core}->{$driver_name}->{$func_name}($id);
	echo $pesan;
	//send_email
	}
//============LIST AGENT
	function update_agent($i=null){
		$this->checkLogin();
	//echo 'update';//exit;
		$this->checkAdmin();
		$this->load->model('users_model');
		if($i!=null){
			$row = $this->users_model->gets($i,'u_id');
			$email = $row['u_email'];
			echo_r($row);
			$this->users_model->update_type($email, 10);
                        echo "update agent email ".$email; 
			$this->account_model->update_to_agent($email );
//			exit;
			echo 'update '.$email.  ' please close the page';
			//redirect('admin');
			//exit;
		}
		else{
			echo "no id to edit";
			exit;
		}
                
		$res=$this->account_model->all_agent('email','email');
		foreach($res as $row){
                    if($row['email']!=''){
                        echo "update email:".$row['email'];
                        $this->users_model->update_type($row['email'], 10); //10 adalah agent
//				echo "<br/> update $row[email]";
                    }
		}
		$url=site_url('admin')."?update_agent=okk"; 
		js_goto($url);
		//redirect('admin');
	}
	
	function agent_list(){
		$this->update_agent();
	}
//===========================TARIF
        public function tarif(){		
		$this->checkLogin();
		if($this->input->post('rate')){
			$post= $this->input->post();
			$stat=$this->forex->rateUpdate($post);
			if($stat===false)die('error');
			redirect(site_url('admin/tarif'));
			exit();
		}else{}
		
		$this->param['title']='Salma forex | Tarif'; 
		$this->param['content']=array(
			'modal',
			'tarif', 
		);
/*
		$this->param['rate']=array(
			'mean'=>ceil( ($this->forex->rateNow('deposit')['value'] + $this->forex->rateNow('widtdrawal')['value']	)/2),
			'deposit'=>$this->forex->rateNow('deposit')['value'],
			'widtdrawal'=>$this->forex->rateNow('widtdrawal')['value']
		);
*/
//datatables		
		$this->param['footerJS'][]='js/jquery.dataTables.min.js';
		$this->param['footerJS'][]='js/tarif.js';
		$this->param['fileCss']['dataTable']='css/jquery.dataTables.min.css';
		$this->showView();
	}
//===========================TARIF
        public function currency($page='',$code=''){		
            $this->checkLogin();
            if($this->input->post('name')){
                    $post= $this->input->post();
            //        echo_r($post);die();
                    $stat=$this->forex->currency_new($post);
            //        if($stat===false)die('error');
                    redirect(site_url('admin/currency'));
                    exit();
            }else{}
            
    //=========================
        if($page=='approved'){
            $this->forex->currency_approve($code);
            redirect(site_url('admin/currency'));
        }
        
        if($page=='disable'){
            $this->forex->currency_disable($code);
            redirect(site_url('admin/currency'));
        }
        
        
            

            $this->param['title']='Salma forex | Currency'; 
            $this->param['content']=array(
                    'modal',
                    'currency', 
            );

//datatables		
            $this->param['footerJS'][]='js/jquery.dataTables.min.js';
            $this->param['footerJS'][]='js/tarif.js';
            $this->param['fileCss']['dataTable']='css/jquery.dataTables.min.css';
            $this->showView();
	}

	public function index(){
		logCreate('cek login');
		$this->checkLogin();
		logCreate('cek login OK');
		$this->param['title']='SECURE ACCOUNT | Dashboard';
		$this->param['content']=array( 'welcome');
		
		$this->param['footerJS'][]='js/login.js';
		$this->showView('newbase_view');
	}	
	
	public function billing(){
		redirect('admin/index');
		//belum jadi
	}
	public function complaint(){
		redirect('admin/index');
		//belum jadi
	}
	function new_account(){
		redirect('admin/index');
		//belum jadi
	}
	
	function deposit($content='',$dep_id=0){
		logCreate('cek login');
		$this->checkLogin();
		logCreate('cek login OK');
		if($content=='approve'){
			//echo_r($_SERVER);die;
			$data=array( 'depositProcess');
			$param=array(
				'post'=>$this->convertData(),
				'get'=>array('type'=>'depositProcess'),
				'post0'=>array('status'=>'approve','id'=>$dep_id),
				'userlogin'=>$this->param['userlogin']
			);
			$data[]=$param;
			logCreate('func data run: datatable => execute (start)');
			$res= _localApi('datatable','execute', $data );
			$dt = $this->forex->flow_data($dep_id);
			if($dt['status']!=1){
				redirect($_SERVER['HTTP_REFERER']."?stat=failed");
			}
			die('success. Please close this tab');
			echo_r($data);echo_r($res);echo_r($dt);die;	
			$dt = $this->forex->flow_data($dep_id);
			echo_r($dt);
			$param=array( );
			
			if(defined('_DEV_')){
				$param['dev']=true;
			}
			$vol=(int)$dt['detail']['orderDeposit'];		
			$param['accountid']		=	$dt['detail']['account'];
			//$param['volume']		=	$vol; 			 
			$param['privatekey']	=	$this->forex->forexKey();
			$param['description']	= 	'Deposit '.$vol.' '.date("H:i:s");
			$param['volume']	= 	"+".$vol;
			
			$url=$this->forex->forexUrl('updateBalance');
			 
			die;
		}
		
		if($content=='cancel'){
			$this->load->driver('advforex');
			$driver_core = 'advforex';
			$func_name='send_email';
			$driver_name='forex_update_balance_credit';
			$result=$this->{$driver_core}->{$driver_name}->{$func_name}($dep_id);// echo_r($result);die;
			//echo_r($_SERVER);die;
			$data=array( 'depositProcess');
			$param=array(
				'post'=>$this->convertData(),
				'get'=>array('type'=>'depositProcess'),
				'post0'=>array('status'=>'cancel','id'=>$dep_id),
				'userlogin'=>$this->param['userlogin']
			);
			$data[]=$param;
			logCreate('func data run: datatable => execute (start)');
			$res= _localApi('datatable','execute', $data );
			$dt = $this->forex->flow_data($dep_id);
			if($dt['status']!=-1){
				redirect($_SERVER['HTTP_REFERER']."?stat=failed");
			}
			die('success. Please close this tab');
			
		}
		$this->param['title']='SECURE ACCOUNT | Dashboard';
		$this->param['content']=array( 'transaksi/deposit_detail');
		$this->param['flow_id']=$dep_id;
		
		$this->param['footerJS'][]='js/login.js';
		$this->showView('newbase_view');
	}
	
	function widthdrawal($content='',$dep_id=0){
		logCreate('cek login');
		$this->checkLogin();
		logCreate('cek login OK');
		$this->load->driver('advforex');
		if($content=='approve'){
			//echo_r($_SERVER);die;
			$data=array( 'widtdrawalProcess');
			$param=array(
				'post'=>$this->convertData(),
				'get'=>array('type'=>'widtdrawalProcess'),
				'post0'=>array('status'=>'approve','id'=>$dep_id),
				'userlogin'=>$this->param['userlogin']
			);
			$data[]=$param;
			logCreate('func data run: datatable => execute (start)');
			$res= _localApi('datatable','execute', $data );
			$dt = $this->forex->flow_data($dep_id);
			if($dt['status']!=1){
				redirect($_SERVER['HTTP_REFERER']."?stat=failed");
			}
			die('success. Please close this tab'); 
		}
		
		if($content=='cancel'){
			$driver_core = 'advforex';
			$func_name='send_email';
			$driver_name='forex_update_balance_credit';
			$result=$this->{$driver_core}->{$driver_name}->{$func_name}($dep_id);// echo_r($result);die;
			//echo_r($_SERVER);die;
			$data=array( 'widtdrawalProcess');
			$param=array(
				'post'=>$this->convertData(),
				'get'=>array('type'=>'widtdrawalProcess'),
				'post0'=>array('status'=>'cancel','id'=>$dep_id),
				'userlogin'=>$this->param['userlogin']
			);
			$data[]=$param;
			logCreate('func data run: datatable => execute (start)');
			$res= _localApi('datatable','execute', $data );
			$dt = $this->forex->flow_data($dep_id);
			if($dt['status']!=-1){
				redirect($_SERVER['HTTP_REFERER']."?stat=failed");
			}
			die('success. Please close this tab');
			
		}
		
		$this->param['title']='SECURE ACCOUNT | Dashboard';
		$this->param['content']=array( 'transaksi/widthdrawal_detail');
		$this->param['flow_id']=$dep_id;
		
		$this->param['footerJS'][]='js/login.js';
		$this->showView('newbase_view');
	}
//================PROFILE=================
    public function edit($warn=0){
        $this->checkLogin();
        if($this->input->post('rand')){
            $rand=$this->input->post('rand');
            $param=array(
                    'type'=>'updateDetail',
                    'data'=>array(					 
                    ),
                    'recover'=>true
            );
            $this->param['post']=$post=$param['post']=$this->input->post();
            $user_detail = $this->users_model->getDetail($post['email'],'ud_email',true) ;
            //echo_r($user_detail);echo_r($post);
            foreach($post['detail'] as $name=>$values){
                    $user_detail[$name]=$values;
            }
            //echo_r($user_detail);exit;
            $params=array('ud_detail'=>json_encode($user_detail));

            $this->users_model->update_detail($post['email'],$params);
    //========update detail forex
            $this->load->driver('advforex'); /*gunakan hanya bila diperlukan*/
            $driver_core = 'advforex';
            $driver_name='update_detail';
            $func_name='execute';
            if( !method_exists($this->{$driver_core}->{$driver_name},$func_name) ){
                    $output=array('func.tion "'.$func_name.'" unable to declare');
                    die(json_encode($output));
            }
            else{
                    $row=$params=array($post['email']);
                    $params=$this->{$driver_core}->{$driver_name}->{$func_name}($row);
                    $this->load->model('localapi_model');
                    $this->localapi_model->clean_member_login($post['email']);

                    //echo_r($params);exit;
            }
            redirect(site_url('admin/edit')."?rand=$rand");

            if(isset($result['status'])&&(int)$result['status']==1){
                    redirect(site_url('admin/detail'));
            }
            else{ 
                    redirect(site_url('admin/edit'));
            }
        }
        else{ 
            $this->param['title']='EDIT SECURE ACCOUNT'; 
            $this->param['content']=array(
                    'detail_edit', 
            );
            $this->param['footerJS'][]='js/login.js';
            $this->param['warning']=$warn;
            $this->load->driver('advforex'); /*gunakan hanya bila diperlukan*/
                    $driver_core = 'advforex';
                    $func_name='detail';
            //$param1=array('post0'=>$param);
            $param=array( );
            $driver_name='user';
            $this->showView(); 
        }

    }

    public function editpassword(){
        $this->checkLogin();
        if($this->input->post('rand')){
                $post0=$this->input->post();
                $userlogin=$this->param['userlogin'];
        //	echo '<pre>'.print_r($userlogin,1);exit();
                $data=array(
                        'user'=>$userlogin,
                        'password'=>$post0['main1']
                );
                $message = $this->load->view('email/emailPasswordChange_email', $data, true);
                //die($email);
                _send_email($to=$userlogin['email'],$subject='[Salmamarkets] Cabinet Password',$message);
                //echo_r($post0);exit;
                $post=array(
                'password'=>$post0['main1'],
                'email'=>$userlogin['email']
                );
                $p=_localApi('users','update_password',$post);
                //echo_r($p);
                //echo 'not valid';
                redirect(site_url("admin/editPassword"));
                exit;
        }
        $this->param['title']='Secure Account | PASSWORD Edit'; 
        $this->param['content']=array(
                'passwordEdit', 'modal'
        );
        $this->param['footerJS'][]='js/login.js';
        $this->showView();
    }
    
    public function edit_master_password(){
        $this->checkLogin();
        if($this->input->post('rand')){
                $post0=$this->input->post();
                $userlogin=$this->param['userlogin'];
        //	$notif=$CI->session->flashdata('notif');
                if($post0['oldcode']!=$userlogin['users']['u_mastercode']){
                        $this->session->set_flashdata('notif',array('status'=>false,'msg'=>'current code not valid'));
                        redirect(site_url('admin/edit_master_password')."?p=".$userlogin['users']['u_mastercode']);
                }
                if($post0['code']!=$post0['code2']){
                        $this->session->set_flashdata('notif',array('status'=>false,'msg'=>'new code not valid'));
                        redirect(site_url('admin/edit_master_password')."?p=444" );
                }


                $data=array(
                        'user'=>$userlogin,
                        'password'=>$post0['code']
                );
                $message = $this->load->view('email/emailMasterCodeChange_email', $data, true);
                //die($message);

                _send_email($to=$userlogin['email'],$subject='[Salmamarkets] Master Code Change',$message);
                $post=array(
                'password'=>$post0['code'],
                'email'=>$userlogin['email']
                );
                $p=_localApi('users','update_master_password',$post);
                //echo_r($p);exit;
                $this->session->set_flashdata('notif',array('status'=>true,'msg'=>'Success'));
                redirect(site_url("admin/edit_master_password?success=".date("Ymd") ));
                exit;
        }

        $userlogin=$this->param['userlogin'];
        if(isset($userlogin['users']['u_mastercode'] )&&trim($userlogin['users']['u_mastercode'])!='' ){
                $this->param['title']='Secure Account | PASSWORD Edit'; 
                $this->param['content']=array(
                        'masterpassword_edit', 'modal'
                );
                $this->param['footerJS'][]='js/login.js';
                $this->showView();
        }
        else{
                $userlogin=$this->param['userlogin'];
                $this->users_model->random_mastercode( $userlogin['email'] );
                redirect(site_url("admin/edit_master_password?reload=".date("Ymd") ));
                exit;
        }
    }
//================================

    public function loginProcess(){
            $login=$post=$this->session->userdata('login');
            $res= _localApi('users','exist',array($login['username']));
            $respon_data=isset($res['data'])?$res['data']:array();
            //echo_r($respon_data);exit();
            if(!$respon_data){
                    $login['message']="Username Not Valid";
                    $this->session->set_flashdata('login', $login);
                    redirect(site_url('login/member'),1);
            }
            else{
                    $login['valid']=$respon_data;
                    $res= _localApi('users','login',array($login['username'],$login['password']));
                    //redirect($_SERVER['HTTP_REFERER'],1);
                    $login['executeLogin']=$res;
                    $respon_data=isset($res['data'])?$res['data']:array();
                    //echo_r($respon_data);exit();
                    $session['expire']=strtotime("+20 minutes");
                    $array=array( 
                            'username'=>$login['username'],
                            'password'=>($res['data']['password']),
                            'expire'=>$session['expire']
                    );
                    $this->session->set_userdata($array);
                    js_goto(site_url('member')."?r=reload_login");
                    //redirect(site_url('member'),1);

            }
    //	echo_r($login);
    }

}