<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (   function_exists('logFile')){ logFile('model','account_model.php','model'); };

class Account_model extends CI_Model {
public $tableRegis='mujur_register'; 
public $tableWorld='mujur_country'; 
public $tableAccount='mujur_account';
public $table='mujur_account';
public $tableAccountRecover='mujur_accountrecover';

public $tableAccountDocument='mujur_accountdocument';
public $tableAccountBalance='mujur_accountbalance';
public $tableAccountDetail='mujur_accountdetail';
public $tableActivation='mujur_activation';
public $tablePassword='mujur_password';
public $tableAdmin='mujur_admin';
public $tablePrice='mujur_price';
public $tableFlowlog='mujur_flowlog';
public $tableAPI='mujur_api';
public $url="http://localhost/forex/fake";
public $demo=1; 
/***
Daftar Fungsi Yang Tersedia :
*	newAccountWithoutPassword()
*	recoverId($id=0)
*	noPass($id)
*	recover($detail=false)
*	create($id,$raw='') //tidak di jalankan
*	updateAccountDetail($username, $detail=false,$document=false)
*	updateDocument($username,$document=false)
*	updateDocumentStatus($username,$status=false)
*	document($id,$field='id')
*	detail($id,$field='id')
*	balance($username,&$time)
*	detailRepair($data=array())
*	__construct()
***/
	function newAccountWithoutPassword(){
		$sql="select username from `{$this->tableAccount}` 
		where masterpassword='' limit 4;";
		return dbFetch($sql);
	}

	function recoverId($id=0){		 
		$now=date("Y-m-d H:i:s");
		$sql="select count(id) c from {$this->tableAccountRecover} 
		where id='{$id}' 
		and expired > '$now'";
		$row0=$this->db->query($sql)->row_array();
		 
		if($row0['c']==0) return false;
		$sql="select params from `{$this->tableAccountRecover}` 
		where id='{$id}'";
		$row1=$this->db->query($sql)->row_array();
		$raw=base64_decode($row1['params']);
		
		$source=isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'-';
		$detail='click from :'.$source; 
		$sql="update `{$this->tableAccountRecover}` 
		set  detail='$detail' , expired =now()
		where id='$id'";
		dbQuery($sql,1);
		//$raw->click =$detail;
		$return  = json_decode($raw,true);
		$return['row']=array($row0,$row1);
		return $return;
	}
	
	function noPass($id){/* */
		$id=addslashes($id);
		$sql="update `{$this->tableAccount}` 
		set 
		investorpassword='',masterpassword='' 
		where id='$id'";
		dbQuery($sql,1);
		logCreate("reset password |id:".$id);		
		return true;
	}
	function cleanRecover(){
		$date=date("Y-m-d",strtotime("-20 days"));
		$sql="delete from `{$this->tableAccountRecover}`
		where expired < '$date'";
		dbQuery($sql,1);
	}

	function recover($detail=false){
		if($detail==false){
//=========Menambah Account Recover
			if(!$this->db->table_exists($this->tableAccountRecover)){
				$fields = array(
				  'id'=>array( 
					'type' => 'BIGINT','auto_increment' => TRUE), 		   
				  'params'=>array( 
					'type' => 'text'),
				  'detail'=>array( 'type' => 'text'),
				  'modified'=>array( 'type' => 'timestamp'),
				  'expired'=>array('type'=>'datetime')
				);
				$this->dbforge->add_field($fields);
				$this->dbforge->add_key('id', TRUE);
				$this->dbforge->create_table($this->tableAccountRecover,TRUE);
				$str = $this->db->last_query();			 
				logConfig("create table:$str");
				$this->db->reset_query();	
			}else{}
//==========Account Document
			if(!$this->db->table_exists($this->tableAccountDocument)){
				$fields = array(
				  'id'=>array( 
					'type' => 'BIGINT','auto_increment' => TRUE),
				  'upload'=>array( 'type' => 'text'),
				  'modified'=>array( 'type' => 'timestamp')
				);
				$this->dbforge->add_field($fields);
				$this->dbforge->add_key('id', TRUE);
				$this->dbforge->create_table($this->tableAccountDocument,TRUE);
				$str = $this->db->last_query();			 
				logConfig("create table:$str");
				$this->db->reset_query();	
				$sql="ALTER TABLE {$this->tableAccountDocument} ENGINE=MYISAM";
				dbQuery($sql);
			}else{}

			if (!$this->db->field_exists('email', $this->tableAccountDocument)){
				$sql="ALTER TABLE `{$this->tableAccountDocument}` ADD `email` varchar(255) AFTER `id`";
				dbQuery($sql);
			}
			if (!$this->db->field_exists('status', $this->tableAccountDocument)){
				$sql="ALTER TABLE `{$this->tableAccountDocument}` ADD `status` tinyint AFTER `email`";
				dbQuery($sql);
			}
			if (!$this->db->field_exists('filetype', $this->tableAccountDocument)){
				$sql="ALTER TABLE `{$this->tableAccountDocument}` ADD `filetype` varchar(100) AFTER `upload`";
				dbQuery($sql);
			}
//=========Menambah Account Balance
			if(!$this->db->table_exists($this->tableAccountBalance)){
				$fields = array(
				  'id'=>array( 
					'type' => 'BIGINT','auto_increment' => TRUE),
				  'detail'=>array( 'type' => 'text'),
				  'balance'=>array('type'=>'float'),
				  'modified'=>array( 'type' => 'timestamp'),
				  'expired'=>array('type'=>'datetime')
				);
				$this->dbforge->add_field($fields);
				$this->dbforge->add_key('id', TRUE);
				$this->dbforge->create_table($this->tableAccountBalance,TRUE);
				$str = $this->db->last_query();			 
				logConfig("create table:$str");
				$this->db->reset_query();
				$sql="ALTER TABLE `{$this->tableAccountBalance}` CHANGE `balance` `balance` DECIMAL(19,7) NOT NULL;";
				dbQuery($sql);
				$sql="ALTER TABLE `{$this->tableAccountBalance}` ENGINE=MYISAM;";
				dbQuery($sql);
			}else{}
			if (!$this->db->field_exists('username', $this->tableAccountBalance)){
				$sql="ALTER TABLE `{$this->tableAccountBalance}` ADD `username` varchar(255) AFTER `id`";
				dbQuery($sql);
			}
//===================
				$sql="select * from {$this->tableAccount} limit 1";
				$row=dbFetchOne($sql);
				if (!$this->db->field_exists('agent', $this->tableAccount)){ 
					$sql="ALTER TABLE `{$this->tableAccount}` ADD `agent` varchar(31) AFTER `accountid`";
					dbQuery($sql);
				}
				if (!$this->db->field_exists('status', $this->tableAccount)){ 
					$sql="ALTER TABLE `{$this->tableAccount}` ADD `status` tinyint AFTER `accountid`";
					dbQuery($sql);
					/*
					1 : valid
					2 : waiting
					0 : not valid
					*/
				}
				if (!$this->db->field_exists('document', $this->tableAccountDetail)){ 
					$sql="ALTER TABLE `{$this->tableAccountDetail}` ADD `document` tinyint AFTER `detail`";
					dbQuery($sql);
				}
			return true;
		}
		
		$data=array('id'=>dbId('recover',date('Ym000'),rand(13,29)));
		$data['params']=base64_encode(json_encode($detail));
		$data['detail']=defined("_DEV_")||defined("LOCAL")?json_encode($detail):'';
		date_default_timezone_set('Asia/Jakarta');
		$data['expired']=date("Y-m-d H:i",strtotime("+25hours"));
		$this->db->insert($this->tableAccountRecover,$data);
		return $data['id'];
		
	}
	
	function create_old($id,$raw='') //tidak di jalankan
	{
		$detail=$this->regisDetail($id);
		if(defined('LOCAL')){
			$sql="select count(id) c from `{$this->tableAccount}` 
		where username like '{$detail['username']}'";
			$row=dbFetchOne($sql);
			if($row['c']!=0){
				$sql="delete from `{$this->tableAccount}` 
				where username like '{$detail['username']}'";
				dbQuery($sql,1);
				$sql="delete from `{$this->tableAccountDetail}` 
				where username like '{$detail['username']}'";
				dbQuery($sql,1);
			}
		}

		$dt=array(
			'reg_id'=>$id,
			'username'=>$detail['username'],
			'investorpassword'=>trim($raw['investorpassword']),
			'masterpassword'=>trim($raw['masterpassword']),
			'accountid'=>$raw['accountid'],
			'email'=>$detail['email'], 
			'created'=>date("Y-m-d")
		);

		$accid=date("ym000");
		$sql="select max(id) max from `{$this->tableAccount}`";
		$dt2=dbFetchOne($sql);
		if($dt2['max'] > (int)$accid){
			$accid=$dt2['max'];
		}
		$dt['id']=$accid+1;
		
		$sql=$this->db->insert_string($this->tableAccount,$dt);
		dbQuery($sql,1);
//===========Account Detail  
		$dt=array(
			'id'=>$accid,
			'username'=>$detail['username'],
			'detail'=>json_encode($detail['detail']),
			
		);
		$sql=$this->db->insert_string($this->tableAccountDetail,$dt);
		
		$sql="select id from `{$this->tableActivation}` 
		where userid=$id";
		$data=dbFetch($sql);
		foreach($data as $row){
			$idActive=$row['id'];
			$this->activationUpdate($idActive, 1); //close activation
		}
		
		$data = array('reg_status' => 0);
		$where = "reg_id=$id";
		$sql = $this->db->update_string($this->tableRegis, $data, $where);
		dbQuery($sql,1);
//===============Change Password===============
		$sql="select password from {$this->tablePassword} order by rand() limit 2";
		$data=dbFetch($sql);
		$invPass=$data[0]['password'];
		$masterPass=$data[1]['password'];
		
		$param=array( );
		$param['privatekey']	=$this->forex->forexKey();
		$param['accountid']=$raw['accountid'];
		$param['masterpassword']=$masterPass.($raw['accountid']%100000 +19939);
		$param['investorpassword']=$invPass.($raw['accountid'] %100000 +19919) ; 
		
		$url=$this->forex->forexUrl('update');
		//$url.="?".http_build_query($param);
		$arr['param']=$param;
		$arr['url']=$url;
//---------UNTUK PEMBUATAN		
		$result0= _runApi($url,$param );/*update new logic*/

		logCreate("update password result:".print_r($result0,1));
		$data = array(
			'investorpassword' => md5( $param['investorpassword'] ),
			'masterpassword'=>md5( $param['masterpassword'] )
		);
		$where = "reg_id=$id";
		
		$sql = $this->db->update_string($this->tableAccount, $data, $where);
		dbQuery($sql,1);
		
		$param2=array( 
			'username'=>$detail['username'],
			'masterpassword'=>$param['masterpassword'],
			'investorpassword'=>$param['investorpassword'],
			'email'=>$detail['email']
		);
		$param2['emailAdmin']=$this->emailAdmin;
		$param2['accountType']=$detail['accounttype'];

		$this->load->view('depan/email/emailRegister_view',$param2);
		
	}

	function updateAccountDetail($username, $detail=false,$document=false){
		$sql="select count(id) c from {$this->tableAccountDetail} where username='$username'";
		$res=dbFetchOne($sql);
		if($res['c']==0){
			$ar=array('username'=>$username);
			$this->db->insert($this->tableAccountDetail, $ar);
		}
		$json=json_encode($detail);
		$sql="UPDATE {$this->tableAccountDetail} SET `detail` = '{$json}' WHERE `username` = '{$username}';";
		dbQuery($sql);
		return true;
	}	

	function update_password($accountid, $pass1,$pass2){
		$sql="select count(id) c from {$this->tableAccount} where accountid='$accountid'";
		$res=dbFetchOne($sql);
		$pass1a=md5($pass1);
		$pass2a=md5($pass2);
		$sql="UPDATE {$this->tableAccount} SET `masterpassword`='{$pass1a}', `investorpassword` = '{$pass2a}'  WHERE accountid='$accountid' limit 1;";
		dbQuery($sql);
		return true;
	}	

	function updateDocument($username,$document=false,$type=null){
		$data=false;//$this->detail($username,'username');
		if($data==false){
			$data=$this->detail($username,'accountid');
		}

		$email=trim($data['email']);
		$sql="select count(id) c from {$this->tableAccountDocument} where email like '$email'";
		$res=dbFetchOne($sql);
		if($res['c']==0){
			$ar=array('email'=>$email);
			$this->db->insert($this->tableAccountDocument, $ar);
			logCreate('not found email:'.$email );
		}
		else{
			logCreate('found email:'.$email.'| total:'.$res['c']);
		}

		if($document!=false){
			logCreate('found email:'.$email.'| document:'.$document);
			$upload=addslashes($document);
			$sql="UPDATE {$this->tableAccountDocument} SET `upload` = '{$document}' WHERE `email` = '{$email}';";
			dbQuery($sql);
			$type=addslashes($type);
			$sql="UPDATE {$this->tableAccountDocument} SET `filetype` = '{$type}' WHERE `email` = '{$email}';";
			dbQuery($sql);
			$sql="UPDATE {$this->tableAccountDocument} SET `status` = '2' WHERE `email` = '{$email}';";
			dbQuery($sql);
			//echo $sql;
			logCreate('update document email:'.$email.'| document:'.$document);
		}
		else{
			logCreate('no  document update:');
		}

		return true;
	}

	function updateDocumentStatus($username,$status=false){
		$data=$this->detail($username,'username');
		if(!$data)$data=$this->detail($username,'accountid');
		$email=trim($data['email']);
		$sql="select count(id) cUpdateDoc from {$this->tableAccountDocument} where email like '$email'";
		$res=dbFetchOne($sql);
		if($res['cUpdateDoc']==0){
			$ar=array('email'=>$email);
			$this->db->insert($this->tableAccountDocument, $ar);
		}else{}
			$sql="UPDATE {$this->tableAccountDocument} SET `status` = '$status' WHERE `email` = '{$email}';";
			dbQuery($sql);
			//echo $sql;
		return true;
	}
	
	function document($id,$field='id'){
		$id=addslashes(trim($id));
		if($field=='email')$id.="%";
		$sql="select count(id) cDoc from `{$this->tableAccount}` where `{$field}` like '{$id}';"; 
		$res=dbFetchOne($sql);
		if($res['cDoc']==0){
			logCreate("account document id:$id|field:$field|NOT FOUND","error");
			return false; 
		}
		logCreate("account document id:$id |field:$field |FOUND:".$res['cDoc']);
		
		$sql="select a.* from `{$this->tableAccount}` a
		where `{$field}` like '$id'";
		$res=dbFetchOne($sql);
		$email=$res['email'];
		
		$sql="select id,email,upload,filetype type,status 
		from {$this->tableAccountDocument} 
		where email like '$email'";
		$resDoc= dbFetchOne($sql);
		$resDoc['account']=$res;
		if(isset($res['upload']))
			logCreate("account document id:$id |field:$field |FOUND:".$res['upload']);
		return $resDoc;
	}

	function lists($id,$field='id'){
		$sql="select count(id) c 
		from `{$this->tableAccount}`  
		where `{$field}` like '{$id}';"; 
		$res=dbFetchOne($sql);
		$respon=array('total'=>$res['c']);
		$sql="select a.* from `{$this->tableAccount}` a  		
		where `{$field}` like '$id'";
		$respon['data']=dbFetch($sql);
		return $respon;
		
	}
	
	function exist($id,$field='id'){
	logCreate("account exist id:$id|field:$field");	
		$id=addslashes(trim($id));
		if($field=='email')$id.="%";
		$sql="select count(id) c 
		from `{$this->tableAccount}`  
		where `{$field}` like '{$id}';"; 
		$res=dbFetchOne($sql);
		if($res['c']==0){
			logCreate("account exist id:$id|field:$field|NOT FOUND","error");
			return false; 
		}
		return true;
	}
	
	function get_by_field($id,$field='id',$sort_field='id',$sort_asc='asc'){
	logCreate("account detail by {$field} id:$id|field:$field");	
		$id=addslashes(trim($id));
	//	if($field!='id')$id.="%";
		$sql="select count(a.id) c 
		from `{$this->tableAccount}` a
		left join `{$this->tableRegis}` r on a.reg_id= r.reg_id
		where {$field} like '{$id}';"; 
		$res=dbFetchOne($sql);
		if($res['c']==0){
			logCreate("account detail id:$id|field:$field|NOT FOUND","error");
			return array( $sql); 
		}
		$users=array();
		$sql="select a.id from `{$this->tableAccount}` a
		left join `{$this->tableRegis}` r on a.reg_id= r.reg_id
		where {$field}  like '{$id}' order by {$sort_field} {$sort_asc};";
		$users=dbFetch($sql);
		$users[]=$sql;
		return $users;
	}

	function gets($id,$field='id',$basic=false){
	$id=addslashes(trim($id));
		if($field=='email')$id.="%";
		$fields="a.*,r.*,if(a.`agent` is null, r.reg_agent,agent) agent_num, if( a.`agent` is null,'register','account') as agent_status";
		$sql0="select %s
		from `{$this->tableAccount}` a
		left join `{$this->tableRegis}` r on a.reg_id= r.reg_id
		where `%s` like '%s'";
	//`{$field}` like '$id'";
	//	$res=dbFetchOne($sql);
		if($basic){
			$sql=sprintf($sql0, 'count(*) c',$field,$id);
			$row=dbFetchOne($sql);
			$sql=sprintf($sql0, $fields,$field,$id);
			if($row['c']>1){
				$res = dbFetch($sql);
			}
			else{
				$res=dbFetchOne($sql);
			}
			return $res; 
		}
		else{//tidak perlu user
			$sql=sprintf($sql0, $fields,$field,$id);
			$res=dbFetchOne($sql);		
		}
/****
SELECT `id`, `username`, `email`, `accountid`, if(`agent` is null,mujur_register.reg_agent,agent) agent,if(`agent` is null,'register','account') agent_status FROM `mujur_account` left join mujur_register on mujur_account.reg_id=mujur_register.reg_id
            [id] => 16005878
            [username] => 7003943
            [email] => Awanwawan36@yahoo.com
            [created] => 2016-10-10
            [modified] => 2017-04-21 01:19:57
            [investorpassword] => 8d4646eb2d7067126eb08adb0672f7bb
            [masterpassword] => 4297f44b13955235245b2497399d7a93
            [reg_id] => 5117
            [accountid] => 7003943
            [status] => 
            [agent] => 
            [type] => MEMBER
****/
		$user=$this->users_model->getDetail($res['email']);
		$res['name']=isset($user['name'])?$user['name']:'user forex';
		$res['users']=$user;
		return $res;
	}

	function detail_basic($id,$field='id',$no_balance=false){
	logCreate("account detail id:$id|field:$field");
	return $this->gets($id,$field,true);
/*	
	$id=addslashes(trim($id));
		if($field=='email')$id.="%";
		$sql="select *
		from `{$this->tableAccount}`
		where `{$field}` like '{$id}';"; 
		$res=dbFetchOne($sql);

		return $res;
*/
	}
	
	function detail($id,$field='id',$no_balance=false){
	logCreate("account detail id:$id|field:$field");	
		$id=addslashes(trim($id));
		if($field=='email')$id.="%";
		$sql="select count(id) c 
		from `{$this->tableAccount}`
		where `{$field}` like '{$id}';"; 
		$res=dbFetchOne($sql);
		if($res['c']==0){
			logCreate("account detail id:$id| field:$field| NOT FOUND","error");
			return false; 
		}
/*		
		$sql="select a.* from `{$this->tableAccount}` a
		where `{$field}` like '$id'";
*/
		$res=$this->gets($id,$field,true);//dbFetchOne($sql);
		
		logCreate("account detail FOUND","lokal");
		if($res['username']!=$res['accountid']&&$res['reg_id']!=0){
			logCreate("account detail id:$id|field:$field|update username |".json_encode($res),"info");
			$sql="select count(id) c from `{$this->tableAccount}` where `username` = '{$res['accountid']}'";
			//$res0=dbFetchOne($sql,1);
			//$okay2Rename=$res0['c']==0?true:false;
			//logCreate("account detail FOUND","lokal");
/*
			if($okay2Rename){
				logCreate("update rename:".json_encode($res));
				$sql="UPDATE `{$this->tableAccount}` SET `username` = '{$res['accountid']}' WHERE `mujur_account`.`id` = {$res['id']};";
				dbQuery($sql);
				$sql="UPDATE {$this->tableAccountDetail} SET `username` = '{$res['accountid']}' WHERE `username` = '{$res['username']}';";
				dbQuery($sql);
				logCreate("acc_model detail update:rename [DONE]");
			}
			else{
				logCreate("fail rename:".json_encode($res));
				$sql="UPDATE {$this->tableAccount} SET `accountid` = '{$res['username']}' WHERE `username` = '{$res['username']}';";
				dbQuery($sql);
			}
*/
			$sql="select a.* from `{$this->tableAccount}` a  		
			where `{$field}` like '$id'";
			$res=$this->gets($id,$field,true);//$res=dbFetchOne($sql); 
		}

		$sql="select 
		a.id, a.username, a.email, a.investorpassword, a.masterpassword, a.reg_id,a.accountid, a.status,
		a.type accounttype, ad.detail raw,adm.adm_type type from `{$this->tableAccount}` a 
		left join `{$this->tableAccountDetail}` ad 
			on a.username like ad.username
		left join `{$this->tableAdmin}` adm 
			on adm_username like a.username
		where a.`{$field}` like '$id'";
		$data= dbFetchOne($sql);
		if($data['accounttype']!='MEMBER'){
			logCreate("account detail |agent","info");
			$agent=true;
		}
		else{
			logCreate("account detail |member","info");
			$agent=false;
		}
		
		if($data['type']==7){
			logCreate("account detail |admin","info");
			$data['type']='admin';
		}
		elseif(strtolower($data['accounttype'])=='agent'){
			logCreate("account detail |agent","info");
			$data['type']='agent';
		}
		else{
			logCreate("account detail |other?","info");
			$data['type']=false;
		}
		
		if(isset($data['raw'])){
			logCreate("account detail id:$id|field:$field|raw detail","info");
			$data['detail']=json_decode($data['raw'],true); 
			unset($data['raw']);
		}
//----document
		logCreate("account document |start","info");
		$documents=$this->document($id, $field);
		if(!$documents)
			$documents=$this->document($id, 'accountid');
		$data['document']=$documents;
		logCreate("account document |end","info");
//----Partner
		$sql="select count(a.id) cPatner from `{$this->tableAccount}` a 
		left join `{$this->tableRegis}` r on a.reg_id=r.reg_id
		where `reg_agent` like '$data[accountid]'";
		$res0=dbFetchOne($sql);

		$data['patner']= (int)$res0['cPatner'];
		//logCreate("cek balance:");
		$time=date('Y-m-d H:i:s');
		logCreate("account balance |start","info");
		if($no_balance==false){
			$data['balance']=$this->balance($res['accountid'],$time);
			$data['balanceDate']=$time;
			$data['summary']=$this->summary($res['accountid'],$time);
			//$data['balanceDate']=$time;

			//logCreate("account balace |end tot:".$data['balance'],"info");
		}
		return $data;

	}
	
	private function summary($username,&$time){
		//========update detail forex
			$this->load->driver('advforex'); /*gunakan hanya bila diperlukan*/
			$driver_core = 'advforex';
			$driver_name='forex_summary';
			$func_name='execute';
			if( !method_exists($this->{$driver_core}->{$driver_name},$func_name) ){
				$output=array('function "'.$func_name.'" unable to declare');
				die(json_encode($output));
			}
			else{
				$row=$params=array($username);
				$params=$this->{$driver_core}->{$driver_name}->{$func_name}($row);
				
				//echo_r($params);exit;
			}
		return isset($params['summary'])?$params['summary']:$params;
	}
	
	private function balance($username,&$time){
            //========update detail forex
                    $this->load->driver('advforex'); /*gunakan hanya bila diperlukan*/
                    $driver_core = 'advforex';
                    $driver_name='forex_balance';
                    $func_name='execute';
                    if( !method_exists($this->{$driver_core}->{$driver_name},$func_name) ){
                            $output=array('function "'.$func_name.'" unable to declare');
                            die(json_encode($output));
                    }
                    else{
                            $row=$params=array($username);
                            $params=$this->{$driver_core}->{$driver_name}->{$func_name}($row);

                            //echo_r($params);exit;
                    }
            return isset($params['margin'])?$params['margin']:$params;
	}
	
	private function balance_old($username,&$time){
//======Remove Expire
/*
		if(!isset($this->session)){
			return 0;
		}

		return 0; 
		/*sementara*/
		$detail=$userlogin=$this->exist($username,'accountid');
		if($detail!==false){
		//OK
			$field='accountid';
			logCreate("account->balance |found accountid","info");
			$sql="select 
		a.id, a.username, a.email, a.investorpassword, a.masterpassword, a.reg_id,a.accountid, a.status,
		a.type accounttype, ad.detail raw,adm.adm_type type from `{$this->tableAccount}` a 
		left join `{$this->tableAccountDetail}` ad 
			on a.username like ad.username
		left join `{$this->tableAdmin}` adm 
			on adm_username like a.username
		where a.`{$field}` like '$username'";
			$detail= dbFetchOne($sql);
		
		}
		else{
			$detail=$userlogin=$this->exist($username,'username');
			if($detail!==false){
				//OK
				$field='accountid';
				logCreate("account->balance |found accountid","info");
				$sql="select 
			a.id, a.username, a.email, a.investorpassword, a.masterpassword, a.reg_id,a.accountid, a.status,
			a.type accounttype, ad.detail raw,adm.adm_type type from `{$this->tableAccount}` a 
			left join `{$this->tableAccountDetail}` ad 
				on a.username like ad.username
			left join `{$this->tableAdmin}` adm 
				on adm_username like a.username
			where a.`{$field}` like '$username'";
				$detail= dbFetchOne($sql);
				logCreate("account->balance |found username","info");
			}
			else{
				logCreate("account->balance |not found","info");
				return 0;
			}
		}

		$accountid=$detail['accountid'];
		/*
		if(isset($this->session))
			$session=$this->session-> all_userdata();
		*/
		$now = date("Y-m-d H:i:s");
		$now_12 = date("Y-m-d H:i:s", strtotime("+1 hours"));
		$sql="delete from {$this->tableAccountBalance} where expired < '$now'";
		logCreate("account->balance |erase expired","info");
		dbQuery($sql);
		$time=date("Y-m-d H:i:s");
		$sql="select username, balance,modified from {$this->tableAccountBalance} where username like '$accountid'";
		$row=dbFetchOne($sql);

		if(isset($row['balance'])){
			$time=$row['modified'];
			logCreate("account->balance |still cache","info");
			return $row['balance'];
		}
/*
		if($session['username']!=$username){
		//	logCreate('username different:'.$username);
			return 0;
		}
*/
		
		$param['accountid']		=	$accountid;
		//$param['volume']		=	"-0";  			 
		$param['privatekey']	=	$this->forex->forexKey();
		//$param['description']	= 	'check balance '.date("H:i:s");
		$url=$this->forex->forexUrl('get_marsgin');
		//$url.="?".http_build_query($param);
		
		$tmp=  _runApi($url, $param ); /*new logic*/
		//die("----".print_r($url,1));
		logCreate('account balance:'.json_encode($tmp));
		if(!is_array($tmp))$tmp=(array)$tmp;
		if(isset($tmp['Balance'])){
			logCreate('url:'.$url.'| respon:'.json_encode($tmp));
			$data = array(
				'username' => $accountid,
				'detail'  => json_encode($tmp),
				'balance'  =>  $tmp['Balance'],
				'expired'	=> $now_12
			);

			$sql = $this->db->set($data)->get_compiled_insert($this->tableAccountBalance);
			dbQuery($sql);
			logCreate("account->balance |update","info");
			return isset($tmp['Balance'])?$tmp['balance']:0;
		}
		else{
			logCreate('url:'.$url.'| Failed| respon:'.json_encode($tmp));
		}
		return 0;
		
	}

	function detailRepair($data=array()){
		$username=$data['username'];
		$sql="select count(id) c  from `{$this->tableAccountDetail}` where `username`='$username'";
		$res=dbFetchOne($sql);
		if($res['c']==1){
			return true;
		}
		
		if($data['reg_id']!=0){
			$reg=$this->regisDetail($data['reg_id']);
			$detail=addslashes(json_encode($reg['detail']));
			$sql="insert into `{$this->tableAccountDetail}`(username,detail) 
			values('$username','$detail')";
			dbQuery($sql);
		}else{}
		return true;
	}
	
	function all_by_email($field='*'){
		$sql="select {$field} from `{$this->tableAccount}` 
		group by email
		order by modified";
		return dbFetch($sql);
	}
	

//=====================================
        public function __construct()
        {
            $this->load->database();
			$this->load->dbforge();
			$this->recover();
			$this->load->model('users_model');
        }
        
//============AGENT
	function all_agent($field='*',$group=false){
		$sql="select {$field} from {$this->tableAccount} where type like 'agent'";
		if($group){
			$sql.=" group by `$group`";
		}

		return dbFetch($sql);
	}
	function update_to_agent($email){
		$sql="update `{$this->tableAccount}` set type='AGENT' where `email` like '$email'";
		dbQuery($sql);
		$sql="update `{$this->tableAccount}` set type='AGENT' where `type` like 'agent'";
		dbQuery($sql);
	}
        
    function all($limit=10,$offset=0,$field='*'){
        $sql="select {$field} from {$this->tableAccount} order by id asc limit $offset,$limit";
        return dbFetch($sql);
    }
}