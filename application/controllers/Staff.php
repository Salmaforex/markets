<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Staff extends MY_Controller {
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

    public function loginProcess(){
        
            $login=$post=$this->session->userdata('login');
            $res= _localApi('users','exist',array($login['username']));
            $respon_data=isset($res['data'])?$res['data']:array();
            echo_r($respon_data);exit();
            if(!$respon_data){
                    $login['message']="Username Not Valid";
                    $this->session->set_flashdata('login', $login);
                    redirect(base_url('login/member'),1);
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
                    js_goto(base_url('member')."?r=reload_login");
                    //redirect(base_url('member'),1);

            }
    //	echo_r($login);
    }

    public function listApi($type=null){
    $types=array('account', 
    'user','agent','approval','partner',);
//'patner_revenue'	
//		if(!defined('LOCAL')){
    $this->checkLogin();
//		}

            $this->param['title']='Secure staff | '.$type; 
            $this->param['content']=array(
                    'modal',			
            ) ;

            if(in_array($type, $types)){
                    $this->param['content']='api/'.$type;
            }
            else{ 
                    $this->param['content']='api/api';
                    log_message('error', 'listApi not define');
                    redirect(site_url('staff')."?not define");
            }
//datatables		
            $this->param['footerJS'][]='js/jquery.dataTables.min.js';
            $this->param['footerJS'][]='js/api.js';
            $this->param['fileCss']['dataTable']='css/jquery.dataTables.min.css';
            //echo_r(array_keys($this->param));exit();
            $this->showView(); 
    }
            private function checkLogin_old2(){
    //	driver_run($driver_core, $driver_name, $func_name='executed', $params=array());
            $row = driver_run('mujur', 'user_login',  'execute' );
            if($row['error']=='NO_VALID_SESSION'||$row['error']=='NO_USERNAME'){
                    logCreate('staff |checkLogin | error:'.$row['error'],'error');
                    js_goto($row['url']);
            }
            //echo_r($row);exit;
            $page = $this->uri->segment(2);
            $page2 = $this->uri->segment(3);
            $this->param['userlogin']=$detail = $row['users'];
            if(isset($detail['typeMember'])){
                    $typeMember=$detail['typeMember'];
                    if($typeMember=='admin'){
                            $url=site_url($typeMember.'/'.$page.'/'.$page2)."?from=staff";
                            js_goto($url);
                    }
                    if($typeMember=='member'){
                            $url=site_url($typeMember.'/'.$page.'/'.$page2)."?from=staff";
                            js_goto($url);
                    }
                    if($typeMember=='partner'||$typeMember=='patners'){
                            $url=site_url( 'partner/'.$page.'/'.$page2)."?from=staff";
                            js_goto($url);
                    }
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
                            logCreate('staff |checkLogin |no username');
                            $this->session->set_flashdata('notif',array('status'=>false,'msg'=>'Input Your Name'));
                            js_goto( base_url('staff/edit').'?r=input_your_name');exit;
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
    private function checkstaff(){
    //	echo_r($this->param['userlogin']);
            if( $this->param['userlogin']['typeMember']!='staff'){
                    //echo_r($this->param['userlogin']);exit;
                    log_message('error', 'check staff not define');
                    $url=site_url('member')."?type_member=".$this->param['userlogin']['typeMember'];
                    //redirect('member');
                    js_goto($url);
                    exit;
            }
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
                    redirect(base_url('staff/edit')."?rand=$rand");
                    /*
                    $param['username']= $this->param['detail']['username'];
                    $param['post']['detail']= $this->param['detail']['detail'];

                    $url=$this->forex->forexUrl('local');

                    $param['data']=$this->convertData();

                    $ar=$this->load->view('depan/data/updateDetail_data',$param,true);
                    $result=json_decode($ar,1); 
                    */
                    if(isset($result['status'])&&(int)$result['status']==1){
                            redirect(base_url('staff/detail'));
                    }
                    else{ 
                            redirect(base_url('staff/edit'));
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


    function __CONSTRUCT(){
    parent::__construct();
    
            date_default_timezone_set('Asia/Jakarta');
            $this->param['today']=date('Y-m-d');
            $this->param['folder']='depan/';
            $this->param['active_controller']='staff';
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

            $this->param['shortlink']=base_url();
            $this->param['footerJS']=array(	 
                    'js/bootstrap.min.js',
                    'js/formValidation.min.js',
                    'js/scripts.js'
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

            $this->param['shortlink']=base_url();
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
    $this->checkstaff();
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
            $post=$this->input->post();
            //echo_r($post);
            $params=array('ud_detail'=>json_encode($post['detail']));
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
            //base_url("staff/data")
            //$res=_runApi(base_url("staff/data"),$params);
            $type=$driver_name="user_edit";
            $driver_core = 'advforex';
            $ar = $this->{$driver_core}->user_edit->execute( $param );
            //echo_r($ar);
            $html = $ar['html'];
            $this->param['html']=$html;
            $this->param['title']='Secure staff | edit '.$email; 
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
            //base_url("staff/data")
            //$res=_runApi(base_url("staff/data"),$params);
            $type=$driver_name="user_detail";
            $driver_core = 'advforex';
            $ar = $this->{$driver_core}->user->detail( $param );
            //echo_r($ar);die();
            $html = $ar['html'];
            $this->param['html']=$html;
            $this->param['title']='Secure staff | '.$ar['title']; 
            $this->param['content']=array(
                    'detail_user'
            ) ;
            $this->showView(); 
    }

    function send_email($id='',$status=''){
            if($status!='send'){
                    $url0=base_url('staff/send_email/'.$id.'/send');
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
            $this->checkstaff();
            $this->load->model('users_model');
            if($i!=null){
                    $row = $this->users_model->gets($i,'u_id');
                    $email = $row['u_email'];
//			echo_r($row);
                    $this->users_model->update_type($email, 10);
                    $this->account_model->update_to_agent($email );
//			exit;
            }
//		exit;
            $res=$this->account_model->all_agent('email','email');
            foreach($res as $row){
                    if($row['email']!=''){
                            $this->users_model->update_type($row['email'], 10); //10 adalah agent
                            echo "<br/> update $row[email]";
                    }
            }
            $url=site_url('staff')."?update_agent=okk";
            //redirect('staff');
            js_goto($url);
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
                    redirect(base_url('staff/tarif'));
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
            redirect('staff/index');
            //belum jadi
    }
    public function complaint(){
            redirect('staff/index');
            //belum jadi
    }
    function new_account(){
            redirect('staff/index');
            //belum jadi
    }
}