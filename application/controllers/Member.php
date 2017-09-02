<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends MY_Controller {
	public $param;
	public $folderUpload;
/***
Daftar Fungsi Yang Tersedia :
*	loginProcess()
*	loginProcess_old()
*	uploads($warn=0)
*	edit($warn=0)
*	editPassword()
*	edit_master_password()
*	editPassword_old()
*	forgot()
*	recover($id=0)
*	recover_old($id=0)
*	history($status='none')
*	deposit($status='none')
*	widtdrawal($status=null)
*	updateDocument($status=null, $userid=null)
*	show_upload($id=null)
*	show_profile($userid=null)
*	login()
*	logout()
*	detail()
*	profile()
*	index()
*	listApi($type=null)
*	tarif()
*	checkLogin()
*	checkLogin_old()
*	send_email($status='',$id='')
*	__CONSTRUCT()
*	account_list($accountid=0)
*	account_id($accountid=0)
*	account_password($accountid)
*	register($type)
*	register_post($type=null)
*	register_detail($type)
***/
	
    function __CONSTRUCT(){
    parent::__construct(); 		
            date_default_timezone_set('Asia/Jakarta');
            $this->param['today']=date('Y-m-d');
            $this->param['folder']='depan/';
            $this->param['active_controller']='member';
            $this->load->helper('form');
            $this->load->helper('formtable');
            $this->load->helper('language');
            $this->load->helper('api');
            $this->load->helper('db');
            $this->load->library('session');
            $this->load->model('forex_model','forex');
            $this->load->model('country_model','country');
            $this->load->model('account_model','account');
            $defaultLang="english";

            $all_session = $this->session->userdata();
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

            $this->param['session']=$all_session; 
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

    public function loginProcess(){
        $login=$post=$this->session->userdata('login'); //$this->param['session']['login'];

        $res= $this->localApi( 'users','exist',array($login['username']));
        //echo_r($res);exit;
        $respon_data=isset($res['data'])?$res['data']:false;

        if( $respon_data===false){
                $login['message']="Username Not Valid";
                $this->session->set_flashdata('login', $login);
                logCreate('debug','res:'.json_encode($res));
                redirect(site_url('login/member')."?err=email_not_valid",1);
        }
        else{
            logCreate('debug','res:'.json_encode($res));
            logCreate('debug','login:'.json_encode($login));
            $login['valid']=$respon_data;
            $res= $this->localApi( 'users','login',array($login['username'],$login['password']));
            //echo_r($res);die;
            //logCreate('api local| users| login| '.json_encode($res));
            $respon_data=isset($res['data'])?$res['data']:array();
            if(isset($respon_data['valid'])&&$respon_data['valid']===false){
                    $login['message']="username / password   Not Valid";
                    $this->session->set_flashdata('login', $login);
                    logCreate( 'username / password   Not Valid |res:'.json_encode($res),'error');
                    redirect(site_url('login/member')."?err=user_pass_not_valid",1);
                    exit;

            }
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

    public function uploads($warn=0){
            $this->checkLogin();

            if($this->input->post('rand')){
                    $post=$this->input->post();
                    $session=$this->session-> all_userdata(); 
                    $rand=dbId();
    //		echo_r($_POST);echo_r($_FILES);exit;
                    $files=$_FILES['doc'];
                    //var_dump($files);die();
                    if($files['size']>550000){
                            $post['message']="document upload to big";
                            $this->session->set_flashdata('login', $post);
                            redirect(site_url('member/uploads/'.$rand));exit();
                    }
                    if(isset($_FILES['profile_pic'])){
                            $files=$_FILES['profile_pic'];
                            if($files['size']>550000){
                                    $post['message']="profile picture upload to big (less than 500kb)";
                                    $this->session->set_flashdata('login', $post);
                                    redirect(site_url('member/uploads/'.$rand));exit();
                            }
                    }

                    $user=$this->param['userlogin'];
                    $rand=$user['users']['u_id'];

                    $files=$_FILES['doc'];
                    $file_data=array();
                    if($files['tmp_name']!=''){
                            $filename="doc_".url_title($user['email'])."_".$rand.".tmp";//$session['username']
                            copy($files['tmp_name'],$this->folderUpload.$filename);
                            $url=  $this->folderUpload.$filename  ;
                            $file_data=array(
                                    'udoc_status'=>0,
                                    'udoc_upload'=>$url,
                                    'filetype'=>$files['type']
                            );
                            logCreate('upload bukti dokumen');
                    }

                    if(isset($_FILES['profile_pic'])){
                            $files=$_FILES['profile_pic'];
                            if($files['tmp_name']!=''){
                                    $filename="pp_".url_title($user['email'])."_".$rand.".tmp";
                                    copy($files['tmp_name'],$this->folderUpload.$filename);
                                    $url2=  $this->folderUpload.$filename  ;
                                    $type2 = $files['type'];
                                    $file_data['profile_pic']=$url2;
                                    $file_data['profile_type']=$type2;
                                    logCreate('upload pp');
                            }
                    }

                    //$url,$files['type']
            //	echo_r($file_data);exit;
                    $this->users_model->updateDocument($user['email'], $file_data);
                    //echo_r($user);echo_r($post);echo_r($file_data);exit;
                    //exit('file:'.$url);
                    redirect(site_url('member/profile')."?msg=save_profile");
            }

            $this->param['title']='UPLOAD DOCUMENT'; 
            $this->param['content']=array(
                            'detailUpload', 
            );
            $this->param['footerJS'][]='js/login.js';
            $this->param['warning']=$warn;
            $this->showView();
    }

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
                    echo_r($user_detail);echo_r($post);
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
                    redirect(site_url('member/edit')."?rand=$rand");
                    /*
                    $param['username']= $this->param['detail']['username'];
                    $param['post']['detail']= $this->param['detail']['detail'];

                    $url=$this->forex->forexUrl('local');

                    $param['data']=$this->convertData();

                    $ar=$this->load->view('depan/data/updateDetail_data',$param,true);
                    $result=json_decode($ar,1); 
                    */
                    if(isset($result['status'])&&(int)$result['status']==1){
                            redirect(site_url('member/detail'));
                    }
                    else{ 
                            redirect(site_url('member/edit'));
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
                    /*
                    $data=$this->{$driver_core}->{$driver_name}->{$func_name}($param);
                    $result=array('status'=>true);
                    $this->param['user_detail']=$data['user_detail'];
                    */
                    //echo_r($this->param);exit;
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
                    redirect(site_url("member/editPassword"));
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
                            redirect(site_url('member/edit_master_password')."?p=".$userlogin['users']['u_mastercode']);
                    }
                    if($post0['code']!=$post0['code2']){
                            $this->session->set_flashdata('notif',array('status'=>false,'msg'=>'new code not valid'));
                            redirect(site_url('member/edit_master_password')."?p=444" );
                    }

            //	echo '<pre>'.print_r($userlogin,1);exit();
            //	echo_r($post0);echo_r($userlogin);exit;
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
                    redirect(site_url("member/edit_master_password?success=".date("Ymd") ));
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
                    redirect(site_url("member/edit_master_password?reload=".date("Ymd") ));
                    exit;
            }
    }

    public function editPassword_old(){
            $this->checkLogin();
            if($this->input->post('rand')){

                    $post=$this->input->post();
    //		echo '<pre>'.print_r($post,1);exit();
                    $data=array( 
                            'investor'=>$post['investor1'],
                            'trading'=>$post['trading1']
                    ); 

                    if( $post['expire'] > date("Y-m-d H:i:s") && $post['expire'] < date("Y-m-d H:i:s", strtotime("+2 hour"))){
                    $data['member']=$this->param['detail']; 
                    $data['now']=date("Y-m-d H:i:s", strtotime("+2 hour"));

                    $url=$this->forex->forexUrl('local'); 
                    $param=array(
                            'type'=>'updatePassword',
                            'raw'=>$data,
                            'recover'=>true,
                            'post'=> $this->input->post() 
                    );
                    $param['member']= $this->param['detail'] ; 
                    $result0=$this->load->view('depan/data/updatePassword_data',$param,true);
                    $result=is_array(json_decode($result0,1))?json_decode($result0,1):$result0;
                    //echo '<pre>'.print_r($result,1);exit();
//-----------EMAIL
                    $param2=array( 
                            'username'=>	$this->param['detail']['accountid'],//$this->param['detail']['username'],
                            'masterpassword'=>		$data['trading'],
                            'investorpassword'=>	$data['investor'],
                            'email'=>		$this->param['detail']['email']
                    );
                    saveTableLog('member','change pass',$param2);
                    //$param2['emailAdmin']=array();//$this->forex->emailAdmin;

                    //$this->load->view('depan/email/emailPasswordChange_view',$param2);

                    }
                    else{ 
                    echo 'not valid';redirect(site_url("depan/editPassword"));
                    }
                    redirect(site_url('member/logout'));
                    //echo '<pre>';print_r($data);die();
            }

            $this->param['title']='Secure Account | PASSWORD Edit'; 
            $this->param['content']=array(
                    'passwordEdit', 'modal'
            );
            $this->param['footerJS'][]='js/login.js';
            $this->showView(); 

    }	

    public function forgot(){
            redirect('guest/forgot');

            $this->param['title']='Recover your Secure Account'; 
            $this->param['content']=array(
                    'modal',
                    'forgot', 
            );
            $this->param['footerJS'][]='js/login.js';
            $this->showView(); 
    }

    public function recover($id=0){
            redirect('guest/recover/'.$id);
    }

//==================
    public function history($status='none'){	
        $this->checkLogin();
            $session=$this->param['session'];
            $detail=$uDetail=$userlogin=$this->param['userlogin'];
        //    $res= $this->localApi( 'users','detail',array($session['username']));
        //    $detail=isset($res['data'])?$res['data']:array();
            if($detail['typeMember']=='patners'){
                    redirect('partner/widtdrawal');
            }
            $res= _localApi('account','lists',array($detail['email']));
            //echo_r($res);
            $account = isset($res['data'])?$res['data']:array();
            //echo_r($account);
            $history=array();
            foreach($account as $row){
                    $data=$this->forex->flow_member($row['accountid']);
                    if($data!=false){
                            foreach($data['data'] as $row){
                                    $history[]=$row;
                            }
                    }
            }
            //echo_r($history);exit;
            $this->param['history']=$history;
            $this->param['content']=array();
            $this->param['title']='OPEN SECURE HISTORY'; 
            $this->param['content'][]='history' ;
            $this->param['content'][]='modal' ;
            $this->param['footerJS'][]='js/login.js';
            $this->param['footerJS'][]='js/jquery.dataTables.min.js';
            $this->param['footerJS'][]='js/api.js';
            $this->param['fileCss']['dataTable']='css/jquery.dataTables.min.css';
            $this->showView();  
    }

    public function deposit($status='none'){
        $this->checkLogin();
    //        $session=$this->param['session'];
    //        $res= $this->localApi( 'users','detail',array($session['username']));
    //        $detail=isset($res['data'])?$res['data']:array();
    //    echo_r($this->param['userlogin']);exit;
        $detail = $this->param['userlogin'];
            if($this->param['userlogin']['typeMember']=='patners'){
                    redirect('partner/deposit');
            }

            $this->param['content']=array();
            $uDetail=$userlogin=$this->param['userlogin'];

        if(!isset($uDetail['bank'])||$uDetail['bank']==''){
                $notAllow=1;
                $uDetail['bank']='';
        }

        if(!isset($uDetail['bank_norek'])||$uDetail['bank_norek']==''){
                $notAllow=1;
                $uDetail['bank_norek']='';
        }

        if(isset($notAllow)){
        //	echo_r($userlogin);
        //	$this->session->set_flashdata('notif', array('status' => false, 'msg' => 'Update nomor rekening!'));
                redirect(site_url("member/edit/warning"),1);
        }

        $email= $uDetail['email'];
        $res=$this->account->get_by_field($email,'email');
        $this->param['account_list']=$res;

        if($status=='done'){
                $info=$this->session->flashdata('info');
                if($info==1){
                        $this->param['done']=1;//$this->param['content'][]='done' ;
                }
        }

        if($this->input->post('orderDeposit')){
                $this->param['post0']=$post0=$this->input->post();

                $this->param['rate']=$rate=$this->forex->rateNow('deposit',$post0['currency']);
                $this->param['post0']['order1']=$post0['order1']=$rate['value'] * $post0['orderDeposit'];
                $this->param['post0']['rate']=$rate['value'];

                $dataDeposit=$post0;
                $dataDeposit['userlogin']=$this->param['userlogin'];
                $dataDeposit['rate']=$rate['value'];
                $dataDeposit['currency']=$rate['code'];
        //	echo_r($this->param['userlogin']);exit;
                $userlogin =$this->param['userlogin'];
        //	echo_r($this->param);
                $message=$this->load->view('email/email_deposit_member_view',$this->param,true);
                //die($message);
                _send_email($userlogin['email'],'[salmamarkets] Confirmation to Deposit',$message);

                $data=array( 'url'=>'Deposit',
                        'parameter'=>json_encode($post0),
                        'error'=>2,
                        'response'=>"rate:".json_encode($rate)."\n".json_encode($this->param['userlogin'])
                );
                //echo_r($data);echo_r($post0);echo_r($userlogin); exit;
                //$this->db->insert($this->forex->tableAPI,$data);
                dbInsert($this->forex->tableAPI, $data);

                //echo_r($data,1);exit;
                $this->forex->flowInsert('deposit', $dataDeposit);
                //dbInsert('deposit', $data);

                $this->session->set_flashdata('info', $post0);
                //kirim email 1
                //$this->load->view('depan/email/emailDepositAdmin_view',$this->param);			
                //kirim email 2
                //$this->load->view('depan/email/emailDepositMember_view',$this->param);
                $notif=array('status'=>true,'msg'=>'Your Deposit Order Success. Please Check your Email');
                $this->session->set_flashdata('notif',$notif);
                redirect(site_url('member/deposit/done/'.rand(2100,8999) ),true);
                redirect(site_url('member/deposit/done/'.rand(100,999) ),true);
                exit();
        }
        else{ 
                $this->param['title']='Secure Account | Deposit ';
                if($status=='done'){
                        $this->param['show_done']='deposit_done' ;
                }
                $this->param['content'][]='transaksi/deposit' ;
        }

        $this->param['footerJS'][]='js/login.js';
        $this->showView(); 

    }	

    public function widtdrawal($status44=null){
        $this->checkLogin();
            $session=$this->param['session'];
            $detail=$uDetail=$userlogin=$this->param['userlogin'];
        //    $res= $this->localApi( 'users','detail',array($session['username']));
        //    $detail=isset($res['data'])?$res['data']:array();
            if($detail['typeMember']=='patners'){
                    redirect('partner/widtdrawal');
            }
            
            $notAllow=true;
            $detail=$uDetail=$userlogin=$this->param['userlogin'];
            if($status44==null && isset($detail['document']['udoc_status'])){
                    if($detail['document']['udoc_status']==1){
                            unset($notAllow);
                    }
                    else{
    //			echo_r($detail);exit;
                            $this->session->set_flashdata('notif', array('status' => false, 
                            'msg' => 'Supporting Document still reviewed'));
                            redirect('member/widtdrawal/stop');
                    }
            }
            else{
                    if($detail['document']['udoc_status']==1){
                            //clear
                    }
                    elseif($detail['document']['udoc_status']<1){
                            $this->session->set_flashdata('notif', array('status' => false, 
                            'msg' => 'Reviewed in progress!'));
                            redirect('member/uploads/reload');
                    }
                    else{
                    //	echo_r($detail);die('----upload--');
                            $this->session->set_flashdata('notif', array('status' => false, 
                            'msg' => 'Please Upload Supporting Document !'));
                            redirect('member/uploads/reload');
                    }
            }

            $this->withdrawal_page($status44);
/*		
            if($notAllow){
                    $this->withdrawal_page($status44);
            }
            else{
                    //redirect(site_url('member/withdrawal/'.$status));
                    redirect('member/widtdrawal/reload');
            }
*/
    }

    function withdrawal($status=null){
            $this->widtdrawal($status);
    }
    private function withdrawal_page($status=null){
    logCreate('member |withdrawal_page |start');
            $this->checkLogin();
            $uDetail=$this->param['userlogin'];
            if(!isset($uDetail['bank'])||$uDetail['bank']==''){
                    $notAllow=1;
                    $uDetail['bank']='';
                    logCreate('member |withdrawal_page |detail bank kosong');
            }

            if(!isset($uDetail['bank_norek'])||$uDetail['bank_norek']==''){
                    $notAllow=1;
                    $uDetail['bank_norek']='';
                    logCreate('member |withdrawal_page |norek bank kosong');
            }

            if(isset($notAllow)){
            //	echo_r($uDetail);exit;
                    logCreate('member |withdrawal_page |failed');
                    redirect(site_url("member/edit/warning"),1);
            }

            $this->param['title']='Secure Account | Withdrawal ';
            $this->param['content']=array();
            $email= $uDetail['email'];
            logCreate('member |withdrawal_page |get akun:'.$email);

            $res=$this->account->get_by_field($email,'email');
            $this->param['account_list']=$res;
            logCreate('member |withdrawal_page |total akun:'.count($res));
            if($status=='done'){
                    $info=$this->session->flashdata('info');
                    logCreate('member |withdrawal_page |status=done?');
                    if($info==1){
                            logCreate('member |withdrawal_page |info=done?');
                            //$this->param['content'][]='done' ;
                            $this->param['done']=1;
                    }

            }

            if($this->input->post('orderWidtdrawal')){
                    $this->param['post0']=$post0=$this->input->post();
                    if($uDetail['users']['u_mastercode']!=$post0['mastercode']){
                            logCreate('member |withdrawal_page master code tidak valid','error');
                            $notif=array('status'=>false,'msg'=>'Master Code not Valid');
                            $this->session->set_flashdata('notif',$notif);
                    //	echo_r($notif);exit;
                            redirect('member/widtdrawal/failed');
                    }


                    $this->param['rate']=$rate=$this->forex->rateNow('widtdrawal',$post0['currency']);
                    $this->param['post0']['order1']=$post0['order1']=$rate['value'] * $post0['orderWidtdrawal'];
                    $this->param['post0']['rate']=$rate['value'];

                    $dataWD=$post0;
                    $dataWD['userlogin']=$this->param['userlogin'];
                    $dataWD['rate']=$rate['value'];
                    $dataWD['currency']=$rate['code'];
                    $dataWD['symbol']=$rate['symbol'];

            //	echo_r($this->param['userlogin']);exit;
                    $userlogin =$this->param['userlogin'];
            //	echo_r($this->param);
                    $message=$this->load->view('email/email_withdrawal_member_view',$this->param,true);
            //	die($message);
                    _send_email($userlogin['email'],'[salmamarkets] Confirmation to Withdrawal',$message);

                    $data=array( 'url'=>'widtdrawal',
                            'parameter'=>json_encode($post0),
                            'error'=>2,
                            'response'=>"rate:".json_encode($rate)."\n".json_encode($this->param['userlogin'])
                    );
                    //echo_r($data);echo_r($post0);echo_r($userlogin); exit;
                    //$this->db->insert($this->forex->tableAPI,$data);
                    dbInsert($this->forex->tableAPI, $data);

                    //echo_r($data,1);exit;
                    //$this->forex->flowInsert('deposit', $dataDeposit);
                    $this->forex->flowInsert('widtdrawal', $dataWD);//dbInsert('deposit', $data);

                    $this->session->set_flashdata('info', $dataWD);
                    //kirim email 1
                    //$this->load->view('depan/email/emailDepositAdmin_view',$this->param);			
                    //kirim email 2
                    //$this->load->view('depan/email/emailDepositMember_view',$this->param);
                    $notif=array('status'=>true,'msg'=>'Your Withdrawal Order Success. Please Check your Email');
                    $this->session->set_flashdata('notif',$notif);
                    redirect(site_url('member/widtdrawal/done/'.rand(2100,8999) ),true);
                    exit();

//============old==================================
                    $this->param['post0']=$post0=$this->input->post();
                    logCreate('member |withdrawal_page |post:'.json_encode($post0)); 
                    $post0['raw']=$this->input->post();
                    $this->param['rate']=$rate=$this->forex->rateNow('widtdrawal')['value'];//-100;
                    logCreate('member |withdrawal_page |rate= '.$rate);
                    $post0['order1']=$this->param['post0']['order1']=$rate * $post0['orderWidtdrawal'];
                     echo_r($this->param);
                    $pesan = $this->load->view('email/email_withdrawal_member_view',$this->param,true);
                    die($pesan);
                    _send_email($userlogin['email'],'[salmamarkets] Confirmation to Withdrawal',$message);

                    if($uDetail['users']['u_mastercode']!=$post0['mastercode']){
                            logCreate('member |withdrawal_page master code tidak valid','error');
                            $notif=array('status'=>false,'msg'=>'Master Code not Valid');
                            $this->session->set_flashdata('notif',$notif);
                    //	echo_r($notif);exit;
                            redirect('member/widtdrawal/failed');
                    }
                    //echo_r($post0);exit;//============
                    $dataApi=array( 'url'=>'widtdrawal',
                            'parameter'=>json_encode($post0),
                            'error'=>2,
                            'response'=>"rate:{$rate}\n".json_encode($this->param['userlogin'] )
                    );


            //	$this->param['rate']=$rate=$this->forex->rateNow('deposit')['value'];
            //	$this->param['post0']['order1']=$post0['order1']=$rate * $post0['orderDeposit'];
                    $this->param['post0']['rate']=$rate;

                    $data =$post0;
                    $userlogin = $data['userlogin']=$this->param['userlogin'];
                    $data['rate']=$rate;
                    unset($data['mastercode']);
                    //========== echo_r($data);exit;

                    $pesan = $this->load->view('email/email_withdrawal_member_view',$this->param,true);
                    //die($pesan);
                    logCreate('member |withdrawal_page |send email?');
                    _send_email($userlogin['email'],'[salmamarkets] Confirmation to Withdrawal', $pesan);
                    $this->forex->flowInsert('widtdrawal', $data);
                    //$this->db->insert($this->forex->tableAPI,$dataApi);
                    dbInsert($this->forex->tableAPI, $dataApi);
                    logCreate('member |withdrawal_page |flow insert => done?');

                    $this->session->set_flashdata('info', $data);
                    //kirim email 1
                    //$this->load->view('depan/email/emailWidtdrawalAdmin_view',$this->param);			
                    //kirim email 2
                    //$this->load->view('depan/email/emailWidtdrawalMember_view',$this->param);
                    $notif=array('status'=>true,'msg'=>'Your Withdrawal Order Success. Please Check your Email');
                    $this->session->set_flashdata('notif',$notif);
                    redirect(site_url('member/widtdrawal/done/'.rand(2100,8999) ),true);

            }
            else{ 	
                    $this->param['content'][]='transaksi/widthdrawal';
                    if($status=='done'){
                            $this->param['show_done']='widtdrawal_done' ;
                            logCreate('member |withdrawal_page |status=> done?');
                    }
                    logCreate('member |withdrawal_page |unknown');

            }

            $this->param['footerJS'][]='js/login.js';
            $this->showView(); 
            logCreate('member |withdrawal_page |end');


    }

    function updateDocument($status=null, $userid=null){
            $stat_id=null;
            if($status=='active') $stat_id=1;
            if($status=='review') $stat_id=2;
            if($status=='inactive') $stat_id=0;

            if($stat_id!=null){
                    $data=$this->account->detail($userid);
                    $username=$data['accountid'];//die(print_r($data,1));
                    $this->account->updateDocumentStatus($username, $stat_id);
                    echo 'status sudah berganti menjadi '.$status;
            }
            else{
                    echo 'status tidak diketahui';
                    exit();
            }
    }

    function show_upload($id=null){
    //	$users=$this->users_model->gets($userid,'u_id');
    //	$email=isset($users['u_email'])?$users['u_email']:false;
    //	$data=$this->users_model->document($email);
            $data=$this->users_model->document($id,'id');
    //	var_dump($users);echo_r($data);die('--'.$email);
            if(isset($data['filetype'])){

                    if(is_file($data['udoc_upload'])){
                            $email=isset($data['udoc_email'])?$data['udoc_email']:'uploads';
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
    //			echo_r($data);die('--no file--');
                    }
            }
            else{
    //		echo_r($data);die();
            }
    }
    function show_upload_0($id=null){
            $data=$this->users_model->document($userid,'id');
            //echo'<pre>';var_dump($data);die();
            header('content-type:'.$data['type']);
            header('Content-Disposition: attachment; filename="'.url_title($data['udoc_email']).'"');

            $txt=file_get_contents( $data['udoc_upload']);
            echo $txt;
    }

    function show_profile($userid=null){
            $data=$this->users_model->document($userid,'id');
            //echo'<pre>';var_dump($data);die();
            //header('content-type:'.$data['profile_type']);
            //header('Content-Disposition: attachment; filename="'.url_title($data['udoc_email']).'"');

            $txt=file_get_contents( $data['profile_pic']);
            echo $txt;
    }

    public function login(){
            redirect(site_url('forex'),1);
            $this->param['title']='LOGIN SECURE ACCOUNT'; 
            $this->param['content']=array(
                    'modal',
                    'login', 
            );
            $this->param['footerJS'][]='js/login.js';
            $this->showView(); 

    }

    public function logout(){
            foreach($_SESSION as $name=>$val){
                    $_SESSION[$name]='';
                    $_SESSION['password']='';
            }
            redirect(site_url("login"));
    }

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

    public function index(){
            logCreate('cek login');
            $this->checkLogin();
            logCreate('cek login OK');
            $this->param['title']='SECURE ACCOUNT | Dashboard';
            $this->param['content']=array( 'welcome');

            $this->param['footerJS'][]='js/login.js';
            $this->showView('newbase_view');
    }	

    public function listApi($type=null){
            redirect('admin/listApi/'.$type);
            $types=array('api','deposit','widtdrawal','user','agent','approval','partner','patner_revenue');	
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
                    redirect('member');
            }
//datatables		
            $this->param['footerJS'][]='js/jquery.dataTables.min.js';
            $this->param['footerJS'][]='js/api.js';
            $this->param['fileCss']['dataTable']='css/jquery.dataTables.min.css';
            //echo_r(array_keys($this->param));exit();
            $this->showView(); 
    }

    public function tarif(){		
            $this->checkLogin();
            if($this->input->post('rate')){
                    $post= $this->input->post();
                    $stat=$this->forex->rateUpdate($post);
                    if($stat===false)die('error');
                    redirect(site_url('member/tarif'));
                    exit();
            }else{}
            $this->param['title']='Salma forex | Tarif'; 
            $this->param['content']=array(
                    'modal',
                    'tarif', 
            );
            $this->param['rate']=array(
                    'mean'=>ceil( ($this->forex->rateNow('deposit')['value'] + $this->forex->rateNow('widtdrawal')['value']	)/2),
                    'deposit'=>$this->forex->rateNow('deposit')['value'],
                    'widtdrawal'=>$this->forex->rateNow('widtdrawal')['value']
            );
//datatables		
            $this->param['footerJS'][]='js/jquery.dataTables.min.js';
            $this->param['footerJS'][]='js/tarif.js';
            $this->param['fileCss']['dataTable']='css/jquery.dataTables.min.css';
            $this->showView();
    }

    private function checkLogin_3(){
    //	driver_run($driver_core, $driver_name, $func_name='executed', $params=array());
            $row = driver_run('mujur', 'user_login',  'execute' );
            if($row['error']=='NO_VALID_SESSION'||$row['error']=='NO_USERNAME'){
                    logCreate('member |checkLogin | error:'.$row['error'],'error');
                    js_goto($row['url']);
            }
            //echo_r($row);exit;
            $page = $this->uri->segment(2);
            $this->param['userlogin']=$detail = $row['users'];
            if(isset($detail['typeMember'])){
                    $typeMember=$detail['typeMember'];
                    if($typeMember=='admin'){
                            $url=site_url($typeMember.'/'.$page)."?from=member&t=".microtime();
                            js_goto($url);
                    }

                    if($typeMember=='admin_trans'){
                            $url=site_url('admin/'.$page)."?from=member&t=".microtime();
                            js_goto($url);
                    }

                    if($typeMember=='staff'){
                            $url=site_url($typeMember.'/'.$page)."?from=member&t=".microtime();
                            js_goto($url);
                    }
                    if($typeMember=='partner'||$typeMember=='patners'){
                            $url=site_url( 'partner/'.$page)."?from=member&t=".microtime();
                            js_goto($url);
                    }
            //	echo_r($detail);exit;
            }
            else{}

            if(isset($detail['name'])&&trim($detail['name'])!=''){
                    //OK
            }
            else{
                    if($page =='edit' || $page =='edit_master_password'){
                            //ok
                    }
                    else{
                            logCreate('member |checkLogin |no username');
                            $this->session->set_flashdata('notif',array('status'=>false,'msg'=>'Input Your Name'));
                            js_goto( site_url('member/edit').'?r=input_your_name');exit;
                    }
            }
            $session=$this->param['session'];
            $res0=  $this->account->get_by_field($session['username'],'email');
            //==========DETAIL ALL
            $res=array();$agent=false;
            if(defined('LOCAL')){ $agent=9999; }
            if(is_array($res0)){
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

            //$res0= $this->localApi( 'account','lists',array($session['username']));
            //echo_r($session);echo_r($res); die();
            //$detail=isset($res['data'])?$res['data']:array();
            $this->param['accounts']=$res;
            $this->param['agent_default']=$agent;
    }

    private function checkLogin_old(){
            $page = $this->uri->segment(2);	
            logCreate('member |checkLogin |start');
    //	echo_r($this->param);exit;
            $session=$this->param['session'];
            $res= $this->localApi( 'users','login_check',array($session['username'],$session['password']));
            $detail=isset($res['data']['valid'])?$res['data']['valid']:false;
            if($detail==false){
            //	echo_r($res);var_dump($session);exit();
                    logCreate('member |checkLogin |no username/accid:'.$session['username'],'error');
                    redirect("login");

            }
            //echo_r($res);var_dump($session);exit();
            //echo_r($session);exit;		
            $res= $this->localApi( 'users','nullDetail',array($session['username']));
            $session['expire']=strtotime("+20 minutes");

            $array=array( 
                    'username'=>$session['username'],
                    'password'=>($session['password']),
                    'expire'=>$session['expire']
            );

            $this->session->set_userdata($array);
            $res_user_detail= $this->localApi( 'users','detail',array($session['username']));
            $detail=isset($res_user_detail['data'])?$res_user_detail['data']:array();
            //echo_r($res_user_detail);exit;
//=======balance
            if(isset($session['accountid'])){
                    $accountid=$session['accountid'];
                    //$account_detail = $this->account->detail($accountid,'accountid') ;
                    $detail['balance']=isset($session['balance'])?$session['balance']:0;		
                    $detail['summary']=isset($session['summary'])?$session['summary']:0;		
                    //isset($account_detail['balance']['Balance'])?$account_detail['balance']['Balance'] :array();

                    $detail['accountid']=$accountid;

            }

            if(isset($detail['typeMember'])&&$detail['typeMember']=='patners'){
                    logCreate('member |checkLogin |partners detected');
                    redirect('partner/index/reload');

            }		
            if(isset($detail['typeMember'])&&$detail['typeMember']=='partner'){
                    logCreate('member |checkLogin |partners detected');
                    redirect('partner/index/reload');

            }
            //$detail['name']='';
            $this->param['userlogin']=$userlogin=$detail;
            $email = isset($userlogin['email'])?$userlogin['email']:'';
            if(isset($detail['users']['u_mastercode'])&&$detail['users']['u_mastercode']!=''){
                    //OK
            }
            else{
                    $this->users_model->random_mastercode( $email  );
                    if($page !='edit_master_password'){
                            logCreate('member |checkLogin |no master code');
                            $this->session->set_flashdata('notif',array('status'=>false,'msg'=>'Master Code not Valid'));
                    //	redirect('member/edit_master_password');
                    }
            }

            if(isset($detail['name'])&&trim($detail['name'])!=''){
                    //OK
            }
            else{
                    if($page =='edit' || $page =='edit_master_password'){
                            //ok
                    }
                    else{
                            logCreate('member |checkLogin |no username');
            //		echo_r($detail);exit;
                            $this->session->set_flashdata('notif',array('status'=>false,'msg'=>'Input Your Name'));
                            js_goto( site_url('member/edit').'?r=input_your_name');exit;
                    }
            }

            $res0=  $this->account->get_by_field($session['username'],'email');
            //==========DETAIL ALL
            $res=array();$agent=false;
            if(defined('LOCAL')){ $agent=9999; }
            if(is_array($res0)){
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

            //$res0= $this->localApi( 'account','lists',array($session['username']));
            //echo_r($session);echo_r($res); die();
            //$detail=isset($res['data'])?$res['data']:array();
            $this->param['accounts']=$res;
            $this->param['agent_default']=$agent;

    }

    function send_email($status='',$id=''){
            if($status!='send'){
                    $url0=site_url('member/send_email/send/'.$id);
                    echo "<h3 align='center'> Apakah email ini akan dikirim ulang? ".anchor($url0,'kirim email bila iya?')."</h3>";
            }
            $p=$this->forex->apiDetail($id);
            //print_r($p);
            $email=$p['url'];
            $detail=json_decode($p['parameter'],true);
            $subject=$detail[0];
            $message=$detail[2];
            $headers=$detail[1];
            //
            //$subject, $headers,$message,'send email'
            if($status!='send'){
                    echo $message;
            }
            else{ 
                    @mail($email, $subject, $message, $headers);
                    echo "email sudah dikirim";
                    $rawEmail=array(
                            $subject, $headers,$message,'email ulang'
                    );
                    $data=array( 'url'=>json_encode($email),
                            'parameter'=>json_encode($rawEmail),
                            'error'=>2
                    );
                    //$this->db->insert($this->forex->tableAPI,$data);
                    dbInsert($this->forex->tableAPI, $data );
            }

    }

//===============ACCOUNT=========
    public function account_list($accountid=0){
            if($accountid!='all'){
                    js_goto(site_url('member/account_list/all'));
                    exit;
            }
            $this->checkLogin();
//======BALANCE
            $this->param['accountid']=$accountid;
            $this->param['title']='SECURE ACCOUNT | Profile'; 
            $this->param['content']=array(
                    'account_list', 
            );
            $this->param['footerJS'][]='js/login.js';
            $this->showView(); 

    }

    public function account_id($accountid=0){
            $this->checkLogin();
//======BALANCE
            $array=array('accountid'=>$accountid);		
            $account_detail = $this->account->detail($accountid,'accountid') ;
            $array['balance']=  isset($account_detail['balance'])?$account_detail['balance']:array();
            $array['summary']=isset($account_detail['summary'])?$account_detail['summary']:array();
            $this->param['userlogin']['accountid']=$accountid;
            //$array['balance']
            $this->param['show_account_debug']=$this->input->get('show_account_debug')?true:false;

//		$session=$this->param['session'];echo_r($session);echo_r($array);exit;
            if( $this->param['userlogin']['balance'] != $array['balance'] ||$this->param['userlogin']['summary'] != $array['summary']  ){
            //	$this->param['userlogin']['balance']=$array['balance'];
                    $this->session->set_userdata($array);
                    redirect('member/account_id/'.$accountid.'?act=save_balance&d='.date("his"));
            }
            else{
                    $session=$this->param['session'];
                    //echo_r($session);echo_r($array);exit;
            }

            $this->param['accountid']=$accountid;
            $this->param['title']='SECURE ACCOUNT | Profile'; 
            $this->param['content']=array(
                    'account_detail', 
            );
            $this->param['footerJS'][]='js/login.js';
            $this->showView(); 

    }

    function account_password($accountid){
            $this->checkLogin();
            if($this->input->post('rand')){

                    $post=$this->input->post();
                    $account=$this->account->gets($post['accountid'],'accountid');
                    $email=$account['email'];
                    //echo_r($account);echo_r($post);exit;
                    $param2=array( 
                            'username'=>	$post['accountid'],
                            //$this->param['detail']['username'],
                            'masterpassword'=>		$post['trading1'],
                            'investorpassword'=>	$post['investor1'],
                            'email'=>		$email,
                            'userlogin' => $this->param['userlogin']
                    );
                    saveTableLog('member','change pass',$param2);
                    $param2['emailAdmin']=array();//$this->forex->emailAdmin;
            //email dahulu
                    $message = $this->load->view('email/account_password_change_view',$param2,true);
            //	die($message);
                    _send_email($to=$email,$subject='[Salmamarkets] Account Password Change ('.$post['accountid'].')',$message);
            //	$this->load->view('depan/email/account_password_change_view',$param2);
            //emailPasswordChange_view
            //========update detail forex
                    $this->account->update_password($post['accountid'], $post['trading1'], $post['investor1']);
                    $this->load->driver('advforex'); /*gunakan hanya bila diperlukan*/
                    $driver_core = 'advforex';
                    $driver_name='account_update_password';
                    $func_name='execute';
                    if( !method_exists($this->{$driver_core}->{$driver_name},$func_name) ){
                            $output=array('func.tion "'.$func_name.'" unable to declare');
                            die(json_encode($output));
                    }
                    else{
                            $row=$params=array($post['accountid'], $post['trading1'], $post['investor1']);
                            $params=$this->{$driver_core}->{$driver_name}->{$func_name}($row);

                            //echo_r($params);exit;
                    }
                    redirect(site_url('member'));
            }
            $this->param['accountid']=$accountid;
            $this->param['title']='SECURE ACCOUNT | Profile'; 
            $this->param['content']=array(
                    'account_password', 
            );
            $this->param['footerJS'][]='js/login.js';
            $this->showView();
    }

    function register($type=null){
            $this->param['title']='SECURE ACCOUNT | Profile';
            if($type=='account'){
                    $contents=array('register_account');
            }
            if($type=='agent'){
                    redirect('member/register/account');
                    $contents=array('register_agent');
            }
            $this->checkLogin();
            if(!isset($contents))
                    redirect('member/detail');
    //	$email = $this->param['userlogin']['email'];
    //	$this->param['accounts']=  $this->account->gets($email );
            $this->param['content']=$contents;
            $this->param['footerJS'][]='js/login.js';
            $this->showView(); 

    }	

    function register_post($type=null){
    $this->checkLogin();
            $this->load->driver('advforex'); /*gunakan hanya bila diperlukan*/
            $driver_core = 'advforex';
            $driver_name='register';
            $userlogin=$this->param['userlogin'];
            $post=$this->input->post();
            if(!isset($post['agree'])){
                    $forgot = array('status'=>false, 'message'=>'Please click Agree check');
                    if(defined('LOCAL')){
                            $forgot['message'].="<br/>code:".$userlogin['users']['u_mastercode'];
                    }
                    $this->session->set_flashdata('forgot', $forgot);
                    redirect('member/register/'.$type);
            }

            if($post['code']!=$userlogin['users']['u_mastercode']){
                    $forgot = array('status'=>false, 'message'=>'Your Master Code is not Valid');
                    if(defined('LOCAL')){
                            $forgot['message'].="<br/>code:".$userlogin['users']['u_mastercode'];
                    }
                    $this->session->set_flashdata('forgot', $forgot);
                    redirect('member/register/'.$type);
            }
            //echo_r($this->param['userlogin']);echo_r($post);exit;
            $row=array(
                    'email'=>$userlogin['email'],
                    'username'=>isset($userlogin['name'])?$userlogin['name']:'user forex',
                    'now'=>time()
            );
            if($type=='account'){
                    $func_name='simple_register';

            }
            if($type=='agent'){
                    $func_name='simple_register';
                    unset($row['agent']);
            }

            if( !method_exists($this->{$driver_core}->{$driver_name},$func_name) ){
                    $output=array('function "'.$func_name.'" unable to declare');
                    $forgot = array('status'=>false, 'message'=>'There is Problem in the system. Please Check a few moments ('.$func_name.')');
                    if(defined('LOCAL')){
                            $forgot['message'].="<br/>func:".$func_name;
                    }
                    $this->session->set_flashdata('forgot', $forgot);
                    redirect('member/register/'.$type);
            }
            else{
//==========LOCAL only
            //	logCreate(" {$driver_core}->{$driver_name}->{$func_name} ".print_r($row,1). print_r($result_register,1) );
                    $ar= $this->localApi( 'account','lists',array($userlogin['email'] ));
                    $accountlist = isset($ar['data'])?$ar['data']:array();
//			echo_r($accountlist);
                    $agent=defined('LOCAL')?211:'';
                    foreach($accountlist as $data){
                            if(isset($data['reg_agent'])&&$data['reg_agent']!=''){
                                    $agent=$data['reg_agent'];
                            }
                    }

                    if($agent!=''){
                            $row['agent']=$agent;
                    }
                    $saved_session = isset($_COOKIE['save_key'])?json_decode($_COOKIE['save_key'],true):array();
//			echo_r($saved_session);echo_r($row);exit;
                    $result_register=$this->{$driver_core}->{$driver_name}->{$func_name}($row);
                    //echo_r($result_register);die(" {$driver_core}->{$driver_name}->{$func_name} ");
                    $account_detail =$result_register['account'];
            //=========SEND EMAIL
                    $email_data=array(
                            'email'=>$userlogin['email'],
                            'username'=>isset($userlogin['name'])?$userlogin['name']:'user forex',
                            'password'=>'',
                            'mastercode'=>'',
                    );
                    $email_data['account']=$account_detail;
                    $email_data['name']=$userlogin['name'];

            //	echo_r($params);exit;
            //	echo '<pre>';print_r($params);
                    //return $params;
            }
            //
            if($result_register===false){
                    $forgot = array('status'=>false, 'message'=>'There is Problem in the system. Please Check a few moments');
                    if(defined('LOCAL')){
                            $forgot['message'].="<br/>{$driver_core}->{$driver_name}->{$func_name}param:".print_r($result_register);
                    }
                    $this->session->set_flashdata('forgot', $forgot);
                    redirect('member/register/'.$type);

            }
            else{
                    if(defined('LOCAL')){
                            logCreate('no Email');
                    }
                    else{
                            $this->load->view('email/emailRegister_account',$email_data);
                    }
            }

            //================update detail
            $driver_name='update_detail';
            $row=array( $userlogin['email'] );
            $func_name='execute';
            $result_register=$this->{$driver_core}->{$driver_name}->{$func_name}($row);
            //echo_r($params);
            //$output=array('func.tion "'.$func_name.'" unable to declare');
            $detail = array('status'=>true, 'account'=>$account_detail); //, 'update'=>$result_register);

            $this->session->set_flashdata('detail', $detail);
            redirect('member/register_detail/'.$type);
            //===================
            js_goto($_SERVER['HTTP_REFERER'],1);
            die('success');
    }

    function register_detail($type=null){
            $this->checkLogin();
            $userlogin=$this->param['userlogin'];
/*		Driver forex */
            $this->load->driver('advforex'); 
            $driver_core = 'advforex'; 
/*      gunakan hanya bila diperlukan        */
            $driver_name='update_detail';
            $row=array( $userlogin['email'] );
            $func_name='execute';
            $result_update=$this->{$driver_core}->{$driver_name}->{$func_name}($row);
//		echo_r($result_update);exit;

            $this->param['title']='SECURE ACCOUNT | Profile';
            $contents=array();

            $contents[]='register_detail';
            if(!isset($contents)) redirect('member/detail');
            $this->param['content']=$contents;
            $this->param['footerJS'][]='js/login.js';
            $this->showView(); 

    }
}