<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (   function_exists('logFile')){ logFile('model','account_model.php','model'); };

class Account_model extends CI_Model {
public $tableRegis='mujur_register'; 
public $tableWorld='mujur_country'; 
public $tableAccount='mujur_account';
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
		$row=$this->db->query($sql)->row_array();
		 
		if($row['c']==0) return false;
		$sql="select params from `{$this->tableAccountRecover}` 
		where id='{$id}'";
		$row=$this->db->query($sql)->row_array();
		$raw=base64_decode($row['params']);
		
		$source=isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'-';
		$detail='click from :'.$source; 
		$sql="update `{$this->tableAccountRecover}` 
		set  detail='$detail' 
		where id='$id'";
		dbQuery($sql,1);
		return json_decode($raw,true);
	}
	
	function noPass($id){
		$id=addslashes($id);
		$sql="update `{$this->tableAccount}` 
		set 
		investorpassword='',masterpassword='' 
		where id='$id'";
		dbQuery($sql,1);
		logCreate("reset password |id:".$id);		
		return true;
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
	
	function create($id,$raw='') //tidak di jalankan
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
		$url.="?".http_build_query($param);
		$arr['param']=$param;
		$arr['url']=$url;
//---------UNTUK PEMBUATAN		
		$result0= _runApi($url );
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

	function updateDocument($username,$document=false,$type=null){
		$data=$this->detail($username,'username');
		$email=trim($data['email']);
		$sql="select count(id) c from {$this->tableAccountDocument} where email like '$email'";
		$res=dbFetchOne($sql);
		if($res['c']==0){
			$ar=array('email'=>$email);
			$this->db->insert($this->tableAccountDocument, $ar);
		}else{}
		if($document!=false){
			$upload=addslashes($document);
			$sql="UPDATE {$this->tableAccountDocument} SET `upload` = '{$document}' WHERE `email` = '{$email}';";
			dbQuery($sql);
			$type=addslashes($type);
			$sql="UPDATE {$this->tableAccountDocument} SET `filetype` = '{$type}' WHERE `email` = '{$email}';";
			dbQuery($sql);
			$sql="UPDATE {$this->tableAccountDocument} SET `status` = '2' WHERE `email` = '{$email}';";
			dbQuery($sql);
			echo $sql;
		}
		return true;
	}
	function updateDocumentStatus($username,$status=false){
		$data=$this->detail($username,'username');
		$email=trim($data['email']);
		$sql="select count(id) c from {$this->tableAccountDocument} where email like '$email'";
		$res=dbFetchOne($sql);
		if($res['c']==0){
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
		$sql="select count(id) c 
		from `{$this->tableAccount}`  
		where `{$field}` like '{$id}';"; 
		$res=dbFetchOne($sql);
		if($res['c']==0){
			logCreate("account detail id:$id|field:$field|NOT FOUND","error");
			return false; 
		}
		
		$sql="select a.* from `{$this->tableAccount}` a
		where `{$field}` like '$id'";
		$res=dbFetchOne($sql);
		$email=$res['email'];
		$sql="select id,email,upload,filetype type,status 
		from {$this->tableAccountDocument} 
		where email like '$email'";
		$resDoc= dbFetchOne($sql);
		$resDoc['account']=$res;
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
	
	function detail($id,$field='id'){
	logCreate("account detail id:$id|field:$field");	
		$id=addslashes(trim($id));
		if($field=='email')$id.="%";
		$sql="select count(id) c 
		from `{$this->tableAccount}`  
		where `{$field}` like '{$id}';"; 
		$res=dbFetchOne($sql);
		if($res['c']==0){
			logCreate("account detail id:$id|field:$field|NOT FOUND","error");
			return false; 
		}
		
		$sql="select a.* from `{$this->tableAccount}` a  		
		where `{$field}` like '$id'";
		$res=dbFetchOne($sql); 
		if($res['username']!=$res['accountid']&&$res['reg_id']!=0){
			logCreate("account detail id:$id|field:$field|update username |".json_encode($res),"info");
			$sql="select count(id) c from `{$this->tableAccount}` where `username` = '{$res['accountid']}'";
			$res0=dbFetchOne($sql,1);
			$okay2Rename=$res0['c']==0?true:false;
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
			$sql="select a.* from `{$this->tableAccount}` a  		
			where `{$field}` like '$id'";
			$res=dbFetchOne($sql); 
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
		//	logCreate("account detail id:$id|field:$field|agent","info");
			$agent=true;
		}
		else{ 
			$agent=false;
		}
		
		if($data['type']==7){
			$data['type']='admin';
		}
		elseif(strtolower($data['accounttype'])=='agent'){
			$data['type']='agent';
		}
		else{
			$data['type']=false;
		}
		
		if(isset($data['raw'])){
		//	logCreate("account detail id:$id|field:$field|raw detail","info");
			$data['detail']=json_decode($data['raw'],true); 
			unset($data['raw']);
		}
//----document
		$data['document']=$this->document($id, $field);
		$sql="select count(a.id) c from `{$this->tableAccount}` a  		
		where `agent` like '$data[username]'";
		$res0=dbFetchOne($sql);
		$data['patner']=$res0['c'];
		//logCreate("cek balance:");
		$time=date('Y-m-d H:i:s');
		$data['balance']=$this->account->balance($res['username'],$time);
		$data['balanceDate']=$time;
		return $data;

	}
	
	private function balance($username,&$time){
//======Remove Expire
/*
		if(!isset($this->session)){
			return 0;
		}
*/	
		$session=$this->session-> all_userdata();
		$now = date("Y-m-d H:i:s");
		$now_12 = date("Y-m-d H:i:s", strtotime("+3 hours"));
		$sql="delete from {$this->tableAccountBalance} where expired < '$now'";
		dbQuery($sql);
		$time=date("Y-m-d H:i:s");
		$sql="select username, balance,modified from {$this->tableAccountBalance} where username like '$username'";
		$row=dbFetchOne($sql);
		
		if(isset($row['balance'])){
			$time=$row['modified'];
			return $row['balance'];
		}

		if($session['username']!=$username){
		//	logCreate('username different:'.$username);
			return 0;
		}

		$param['accountid']		=	$username;
		$param['volume']		=	"-0";  			 
		$param['privatekey']	=	$this->forex->forexKey();
		$param['description']	= 	'check balance '.date("H:i:s");
		$url=$this->forex->forexUrl('updateBalance');
		$url.="?".http_build_query($param);

		$tmp= _runApi($url );
		logCreate('account balance:'.json_encode($tmp));
		if(!is_array($tmp))$tmp=(array)$tmp;
		if(isset($tmp['balance'])){
			logCreate('url:'.$url.'| respon:'.json_encode($tmp));
			$data = array(
				'username' => $username,
				'detail'  => json_encode($tmp),
				'balance'  =>  $tmp['balance'],
				'expired'	=> $now_12
			);

			$sql = $this->db->set($data)->get_compiled_insert($this->tableAccountBalance);
			dbQuery($sql);
			return $tmp['balance'];
		}
		else{
			logCreate('url:'.$url.'| Failed| respon:'.json_encode($tmp));
		}
		return 0;
		
	}

	function detailRepair($data=array()){
		$username=$data['username'];
		$sql="select count(id) c  from `{$this->tableAccountDetail}` 
		where `username`='$username'";
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
//=====================================
		public function __construct()
        {
            $this->load->database();
			$this->load->dbforge();
			$this->recover();
		}

}