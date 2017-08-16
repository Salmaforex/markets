<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
//require_once(APPPATH.'/libraries/SmtpApi.php');
class Runonce extends CI_Controller {
/***
Daftar Fungsi Yang Tersedia :
*	go()
*	send_email1()
*	close()
*	sendEmail($params)
*	pings()
*	__CONSTRUCT()
***/
private $db_main;
    function index(){
        $db['default']['hostname'] = 'localhost';
        $db['default']['username'] = 'root2';
        $db['default']['password'] = '';
        $db['default']['database'] = 'mujur_forex1708';
        $db['default']['dbdriver'] = 'mysqli';
        $db['default']['dbprefix'] = ''; //NO PREFIX
        $db['default']['pconnect'] = FALSE;
        $db['default']['db_debug'] = TRUE;
        $db['default']['cache_on'] = FALSE;
        $db['default']['cachedir'] = '';
        $db['default']['char_set'] = 'utf8';
        $db['default']['dbcollat'] = 'utf8_general_ci';
        $db['default']['swap_pre'] = '';
        $db['default']['autoinit'] = TRUE;
        $db['default']['stricton'] = FALSE;
        $this->db_main = $this->load->database('default', true);//
        send_api();
        //$this->cloneAccount();
        //$this->updateDetail();
    }
    
    function send_api(){
         $urls=$this->config->item('apiForex_url' );
        $url=$urls['update'];
        $start=0;$n=1;
        while($n>0){
            $data= $this->db_main->limit(5,$start)->get('tmp_account')->result_array();
            $result=array();
            foreach($data as $row){
                $params=array(
                    'privatekey'=>ciConfig('privatekey'),
                    'accountid'=>$row['accountid'],
                    'username'=>trim($row['username']),
                    'country'=>isset($users['country'])?$users['country']:'Indonesia?',
                //    'allowlogin'=>1,
                //    'allowtrading '=>1
                );
                
                if($params['username']==''){
                    unset($params['username']);
                }

                if(defined('LOCAL')){
                    $res=array('url'=>$url,'param'=>$params );
                    logCreate('update account(local):'.json_encode($res ));
                    $result['time']['no api '.$accountid]=microtime(true);
                    $result['run'][]=$res;
                }
                else{
                    $res=_runApi($url,$params);
                    $result['time']['run api '.$accountid]=microtime(true);
                    logCreate('update account:'.json_encode($res));
                    $result['run'][]=  json_encode($res) ;
                }
                
            }
            echo date("Y-m-d H:i:s");
            echo "pos:$n $start\n".  microtime();
            print_r($result);
             $n=count($data);$start+=5;
sleep(1);
        }
    }
    
    function updateDetail(){
        $start=0;$n=1;
        while($n>0){
            $data= $this->db_main->limit(5,$start)->get('tmp_account')->result_array();
            foreach($data as $row){
                $id=$row['id'];
                $input=array();
                $sql="select detail from mujur_accountdetail where username like '{$row['accountid']}'";
                $row2 =$this->db_main->query($sql)->row_array();
                if($row2){
                    $res=  json_decode($row2['detail'],true);
                     if(isset($res['name'])){
                        $input['username']=$res['name'];
                    }
                    if(isset($res['firstname'])){
                        $input['username']=$res['firstname'];
                    }

                    if(isset($res['country']['name'])){
                        $input['country']=  ucfirst( strtolower( $res['country']['name'] ) );
                    }
                    if(isset($res['country'] )&&!is_array($res['country'])){
                        $input['country']=  ucfirst( strtolower( $res['country']  ) );
                    }
                    print_r($res); 
                }
                
                $sql="select ud_detail detail from mujur_usersdetail where ud_email like '{$row['email']}'";
                $row2 =$this->db_main->query($sql)->row_array();
                if($row2){
                    $res=  json_decode($row2['detail'],true);
                    if(isset($res['firstname'])){
                        $input['username']=$res['firstname'];
                    }
                    
                    
                     if(isset($res['name'])){
                        $input['username']=$res['name'];
                    }

                    if(isset($res['country'])){
                        $input['country']=  ucfirst( strtolower( $res['country'] ) );
                    }
                    print_r($res); 
                }
                
            //============n===============================
                $this->db_main->select("name,country" )->where('accountid',$row['accountid']);
                $res=$this->db_main->get('account_xls')->row_array();
                print_r($res);
                if(isset($res['name'])){
                    $input['username']=$res['name'];
                }
                
                if(isset($res['country'])){
                    $input['country']=  ucfirst( strtolower( $res['country'] ) );
                }
                
                if(count($input)!= 0){
                    $input['username']=substr($input['username'],0,30);
                    
                    if($input['country']=='')$input['country']="Indonesia";
                    if((int)$input['country']==0)$input['country']="Indonesia";
                    print_r($input);
                    $this->db_main->where('id',$id)->update('tmp_account',$input );
                }
            }
            echo "pos:$n $start\n".  microtime(); 
             $n=count($data);$start+=5;
            //echo "\n$start";
        }
    }
    
    private function cloneAccount(){
        $start=0;$n=1;
        while($n>0){
            $data= $this->db_main->limit(5,$start)->get('mujur_account')->result_array();
            //print_r($data);die;
            foreach($data as $row){
                $input=array('accountid'=>$row['accountid'],'username'=>$row['username'],'country'=>'Indonesia'
                    ,'email'=>$row['email']);
                //$this->db_main->insert('tmp_account',$input);
                $insert_query = $this->db_main->insert_string('tmp_account', $input );
                $insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
                $this->db_main->query($insert_query);

            }
            $n=count($data);$start+=5;
            echo "\n$start ".  microtime();
        }
    }
    
    private function createAccountList(){
        $row = 1;
        if (($handle = fopen("tmp/tmp.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $num = count($data);
            //    echo "<p> $num fields in line $row: <br /></p>\n";
                $row++;
                $arr=array();
                for ($c=0; $c < $num; $c++) {
                    $n=$c+1;
                    $arr['tmp'.$n]=$data[$c];
                }
                //print_r($arr);
                //die;
                $this->db_main->insert('z_reports',$arr);
                echo "\npos:$row";
            //    die;
            }
            fclose($handle);
        }
    }

    private function updateAccount(){
        $total=$this->account->all(4,0,'count(*) total');
        print_r($total);
        $n=1;
        $limit=5;
        $start=0;//$total[0]['total']-$limit;
        while($n>0){
			sleep(1);
            echo "\nSTART:$start / $limit";
            $n=$this->run_update_account($limit, $start);
            $start+=$limit;
			
        }
        
    }
    
    private function run_update_account($limit,$start ){
        $urls=$this->config->item('apiForex_url' );
        $url=$urls['update'];
        $account=$this->account->all($limit, $start);//$total[0]['total']);
        //echo_r($account);
		echo json_encode($account);echo "\n".data("Y-m-d H:i:s")."\n";
        $result=array();
        foreach($account as $row){
            $accountid=$row['accountid'];
            $users = $this->users_model->getDetail($row['email']);
            echo_r($users);
            $name='salmamarkets users';
            if(isset($users['name'])){
                $name=$users['name'];
            }
            elseif(isset($user['firstname'])){
                $name=$user['firstname'];
            }
            
            $params=array(
                'privatekey'=>ciConfig('privatekey'),
                'accountid'=>$row['accountid'],
                'username'=>$name,
                'address'=>isset($users['address'])?$users['address']:'.',
                'zipcode'=>isset($users['zipcode'])?$users['zipcode']:'.',
                'country'=>isset($users['country'])?$users['country']:'Indonesia?',
                'phone'=>isset($users['phone'])?$users['phone']:'.',
                'allowlogin'=>1,
                'allowtrading '=>1
            );
            
            if(defined('LOCAL')){
                $res=array('url'=>$url,'param'=>$params );
                logCreate('update account(local):'.json_encode($res ));
                $result['time']['no api '.$accountid]=microtime(true);
                $result['run'][]=$res;
            }
            else{
                $res=_runApi($url,$params);
                $result['time']['run api '.$accountid]=microtime(true);
                logCreate('update account:'.json_encode($res));
                $result['run'][]=$res;
            }
        }
        //echo_r($result);
		echo json_encode($result);echo "\n".data("Y-m-d H:i:s")."\n";
        return isset($result['run'])?count($result['run']):0;
    }
	
	function go(){
		$sPubKey= $this->config->item('sendpulse_pubkey');
		//var_dump($sPubKey);
		$oApi = new SmtpApi($sPubKey);
		$res = $oApi->ips();
/*		$res = $oApi->add_domain('admin@dev.salmaforex.com');
		if ($res['error']){ // check if operation succeeds
			die('Error: <pre>' . print_r($res,1));
		}
		else{
			echo_r($res);
		}
		$res = $oApi->verify_domain('admin@dev.salmaforex.com');
		if ($res['error']){ // check if operation succeeds
			die('Error: <pre>' . print_r($res,1));
		}
		else{
			echo_r($res);
		}
		exit();
*/
		/*$res = $oApi->ips();
		5.104.224.149
		*/
		/*$res = $oApi->domains();
		["@gmail.com","@salmaforex.com"]*/
		/*$res = $oApi->add_domain('noreply@salmamarkets.com');
		"Confirmation e-mail has been sent"
		*/
	/*
		$aEmail = array(
    'html' => '<p>HTML text of email message</p>',
    'text' => 'Email message text',
    'encoding' => 'UTF-8',
    'subject' => 'Email message subject',
    'from' => array(
        'name' => 'No reply',
        'email' => 'admin@dev.salmaforex.com',
    ),
    'to' => array(
        array(
            'email' => 'gundambison@gmail.com'
        ),
    ),
    'bcc' => array(
        array(
            'name' => 'Admin Devel',
            'email' => 'admin@dev.salmaforex.com '
        ),
    ),
);
		$res = $oApi->send_email($aEmail);"Sent OK"
		*/
		echo'<pre>'; var_dump($res);echo '</pre>';
		if ($res['error']){ // check if operation succeeds
			die('Error: <pre>' . print_r($res,1));
		}
		else {
			if (empty($res['data'])){
				// empty array – email address is not specified
				echo 'empty'.print_r($res,1);
			} else {
				echo json_encode($res['data']);
			}
		}

		exit();
	}
	
	function send_email1(){
		$sPubKey= $this->config->item('sendpulse_pubkey');
		echo_r($sPubKey);
	//	$this->oApi = new SmtpApi($sPubKey);
		$oApi = new SmtpApi($sPubKey);
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
	function close(){
		if($this->input->post('message')){
			$data=array('message'=>$this->input->post('message'));
			$params['message']=$this->load->view('guest/email/emailclose_view',$data,true);
			$params['subject']=$this->input->post('subject');
			
			if($this->input->post('to')!='all'){
				$params['to']=$this->input->post('to_email');
				$this->sendEmail($params);
				echo $params['message'];
			}
			else{
				echo 'start';
				$to=$this->account->all_by_email('email');
				//print_r($to[33]); die( 'total:'.count($to) );
				echo ' GO ';
				foreach($to as $row){
					if(trim($row['email'])!=''){
						$emails =trim($row['email']);
						$params['to']=$emails;
						$this->sendEmail($params);
					}else{}
					
				}
				
				die('send to '.count($to).' email');
			}
		}
		else{
			$this->load->view('guest/close_view');
		}
	}

	function sendEmail($params){
		$this->forex->userDocumentRefill();
	$subject = $params['subject'];
 
	$headers = "From: noreply@salmaforex.com\r\n";
	$headers .= "Reply-To: noreply@salmaforex.com\r\n"; 
	$headers .= "MIME-Version: 1.0\r\n";

	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	
	$to=$params['to'];
	$message=$params['message'];

	if(defined('LOCAL')){	
		$rawEmail=array(
			$subject, $headers,$message,'send email'
		);
		$data=array( 
			'url'=>json_encode($to),
			'parameter'=>json_encode($rawEmail),
			'error'=>2
		);
		$this->db->insert($this->forex->tableAPI,$data);
		//die($message ); 
	}
	else{
		if(!is_array($to))$to=array($to);
		foreach($to as $email){
			batchEmail($email, $subject, $message, $headers);
		}
		$rawEmail=array(
			$subject, $headers,$message,'send email'
		);
		$data=array( 'url'=>json_encode($to),
			'parameter'=>json_encode($rawEmail),
			'error'=>2
		);
	//	$this->db->insert($this->forex->tableAPI,$data);

	}
	}

	function pings(){
		$this->forex->userDocumentRefill();

//=====database
		
		$param=array(
			array(
			'hostname' => 'mysql.idhostinger.com',
			'username' => 'u429780871_forex',
			'password' => '|V4CMBo6mj',
			'database' => 'u429780871_forex',
			),
			array(
			'hostname' => '31.220.56.227',
			'username' => 'mujur_salma',
			'password' => 'mujur!salma227',
			'database' => 'mujur_salma',
			)
		);
		foreach($param as $db){
			$result = array('start'=>date('Y-m-d H:i:s') );
			$mysqli = @new mysqli($db['hostname'], $db['username'], $db['password'], $db['database']);
			$result['end']= date('Y-m-d H:i:s');
			$url='mysqli: '.$db['hostname'];
/*
 * This is the "official" OO way to do it,
 * BUT $connect_error was broken until PHP 5.2.9 and 5.3.0.
 */
			if ($mysqli->connect_error) {
				$result['num_err']= $mysqli->connect_errno;
				$result['message']= $mysqli->connect_error;
			//	die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
				$this->forex->pingFailed($url, $result);
				echo "\n{$url} |". $mysqli->connect_error;
			}
			else{
			//	$result['message']='success';
				$this->forex->pingSuccess($url,$result);
				echo "\n{$url} | success";
			}

		}
				$param=array(
		//'http://lms.lkpp.sitex/',
		'http://salmaforex.com/',
		'http://sukses.salmaforex.com/',
		'http://serversaga.salmaforex.com/'
		);
		global $maxTime;
		$maxTime = 2;
		echo '<pre>';
		$post=array('from'=>current_url(),'maxTime'=>9);
		foreach($param as $url){
			$info=array( 'start'=>date('Y-m-d H:i:s') );
			$result = _runApi($url,$post);

			//echo '<pre>xxx'.print_r($result,1).'</pre>';die();
			if(isset($result->code)){
				$this->forex->pingFailed($url, $result);
				echo "\n fail:$url";
			}
			else{
				$info['end']=date('Y-m-d H:i:s');
				$info['size']=strlen($result);
				$this->forex->pingSuccess($url, $info);
				echo "\n success:$url";
			}
		}
	}

    function __CONSTRUCT(){
	parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
    /*
        $this->param['today']=date('Y-m-d');
        $this->param['folder']='depan/';
        $this->load->helper('form');
        $this->load->helper('formtable');
        $this->load->helper('language');
        $this->load->helper('api');
        $this->load->helper('db');
        $this->load->model('forex_model','forex');
        $this->load->model('country_model','country');
        $this->load->model('users_model');
        $this->load->model('account_model','account');
     * 
     */
    }

	function user_exist(){
		$res= _localApi('users','exist',array('admin'));
		$respon_data=isset($res['data'])?$res['data']:array();
		echo_r($respon_data);exit();
	}

	function index_old(){
		$subject = "testing email";
 
	$headers = "From: noreply@salmaforex.com\r\n";
	$headers .= "Reply-To: noreply@salmaforex.com\r\n"; 
	$headers .= "MIME-Version: 1.0\r\n";

	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	
	$email="gundambison@gmail.com";
	$message="ini adalah test email dari salmamarkets";

	batchEmail($email, $subject, $message, $headers);
	echo "done kirim email $email";
	}
	
	function blast_mail($type=1,$limit=4,$start=0){
		$this->load->model('password_model');
		$this->users_model->add_no_user_account();
		echo "\nno account done";
		$data=$this->users_model->gets_all($type,'u_type',$limit,$start);
		$n=$start;
		echo "\nType:$type\t limit: $start , $limit";
		$headers = "From: noreply@salmamarkets.com\r\n";
		$headers .= "Reply-To: noreply@salmamarkets.com\r\n"; 
		$headers .= "MIME-Version: 1.0\r\n";

		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		$subject = '[salmamarkets] Welcome to Salmamarkets ';
		foreach($data as $row){
			$email= $row['u_email'];
			logCreate("blast_mail |$n\temail:$email");
			$this->users_model->addNullDetail($email);
			$this->users_model->addNullDocument($email);
			logCreate("blast_mail| email:$email| add null done");
	//============password
			$resPass= $this->password_model->random(3);
			$pass1=$resPass[0]['password'];
			$pass2=$resPass[1]['password'];
			$pass3=rand(1234,9876);
			$password="{$pass1}{$pass3}";
			$password_sha1=sha1("{$pass1}{$pass3}|{$pass2}")."|".$pass2 ;
			$this->users_model->updatePass($email, $password_sha1);
		//============mastercode
			$pass1=rand(1234,9876);
			$pass2=rand(1234,9876);
			$mastercode=$pass1.$pass2;
			$this->users_model->updateMasterPass($email, $mastercode);
			logCreate("blast_mail| email:$email password:$password| $mastercode");
				$param=array(
					'name'=>'Salmamarkets Member',
					'username'=>$row['u_email'],
					'password'=>$password,
					'mastercode'=>$mastercode
				
				);
			$valid_email= filter_var($email, FILTER_VALIDATE_EMAIL);
			if ($valid_email) {
				logCreate("blast_mail |email:$email valid");
				echo("\n$email is a valid email address");
				$message=$this->load->view('email/email_register_email',$param,true);
				batchEmail($email, $subject, $message, $headers);
			} else {
				logCreate("blast_mail |email:$email NOT VALID");
			  echo("\n$email is not a valid email address");
			}
			
			$n++;
		}
	}
	
	function execute_blast($total=12000){
		$counter=100;
		for($i=0;$i<=$total;$i+=$counter){
			echo "\nstart:$i / $counter";
			$this->blast_mail(1,$counter,$i);
		}
		echo "done \n\n";
		
		
	}
	function index0(){
		echo "test ".date("H:i:s");
		$url=site_url('runonce/all_email')."?d=".date("H:i:s");
		$res=_runApi($url);
		echo_r($res);
	//	$this->blast_mail();
	}
	
	function all_email(){
		$data = $this->forex_model->emailData(10);
		$result=array();
		foreach($data as $row){
			$id=$row['id'];
			$to=$row['to'];
			$subject=$row['subject'];
			$message= $row['messages'];
			$headers= $row['headers'];
		//	echo "\n: $id $to";
		//	batchEmail( $to , $subject , $message , $headers, false);
			$this->forex_model->emailHide($row['id']);
			$result[]= array($to , $subject , $message , $headers);
			log_info_table('email',array($to,$subject));
		//	$result[]=array(array_keys($row),$row['status'] );
		}
		echo json_encode($result);
	}
	
}