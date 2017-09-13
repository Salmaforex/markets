<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (   function_exists('logFile')){ logFile('model','users_model.php','model'); };

class Users_model extends CI_Model {
public $table='mujur_users';
public $table_erase='mujur_users_erase';
public $tableDocument='mujur_usersdocument';
public $tableDetail='mujur_usersdetail';
public $tabletype='mujur_userstype';
public $table_erase_user='';

function exist($search,$limit=10, $field='u_email'){
	$sql='select u_id,u_password from `'.$this->table.'` where `'.$field.'` like \''.$search.'\'
	limit '.$limit;//preventif
	logCreate("users_model exist:".$sql);
	$res=dbFetch($sql);
	if($res==false||count($res)==0){
		logCreate('kosong');
		return false;
	}
	
	$res= array(
	'count'=>count($res),
	'data'=>$res
	);
	return $res;
}

function erase($email){
    logCreate('model|users_model| erase:'.$email);
    $data= $this->gets($email);
    $input=array(
        'email'=>$email,
        'parameter'=>  json_encode($data)
    );
    
    $this->db->insert($this->table_erase_user,$input);
    
    $sql="delete from `{$this->table}` where `u_email` like '".$email."'";
    dbQuery($sql);
    $sql="delete from `{$this->tableDetail}` where `ud_email` like '".$email."'";
    dbQuery($sql);
    logCreate('model|users_model| delete:'.$email);
    return true;
}

function erase_old($email){
	logCreate('model|users_model| erase:'.$email);
	$sql="insert into `{$this->table_erase}` select * from {$this->table} where `u_email` like '".$email."'";
	dbQuery($sql);	
	logCreate('model|users_model| backup:'.$email);
	$sql="delete from `{$this->table}` where `u_email` like '".$email."'";
	dbQuery($sql);
	$sql="delete from `{$this->tableDetail}` where `ud_email` like '".$email."'";
	dbQuery($sql);
	logCreate('model|users_model| delete:'.$email);
	return true;
}

function register($param,$debug=false){
	$res=array('data'=>$param);
        $password="SalmaMarket";
	$params=$param;
	unset($params['accept'],$params['submit']);
	$email=isset($param['email'])?trim($param['email']):null;
        $resPass= $this->password_model->random(2);
        
        $pass1=$resPass[0]['password'];
        $pass2=$resPass[1]['password'];
        $pass3=rand(1234,9876);//$resPass[2]['password'];
        $input=array('u_type'=>1,'u_status'=>-1); //status -1 karena baru. di aktifkan setelah kirim email
        $masterpassword = $pass1.$pass3;
        $input['u_password']=sha1("{$pass1}{$pass3}|{$pass2}")."|".$pass2 ;
        $input['u_email']=$email;

        $res['password'][]=$resPass;
        $res['password'][]=$pass3;

        //$resPass= $this->password_model->random(2);
        //$res['password'][]=$resPass;
        //$pass1=$resPass[0]['password'];
        //$pass2=$resPass[1]['password'];
        $input['u_mastercode']=$mastercode=rand(142410,987658);
		
	//$sql= $this->db->insert_string($this->table, $input);
	//	dbQuery($sql);
	//$res['sql'][]=$sql;
	dbInsert($this->table, $input);

	$input=array('ud_email'=>$email);
	$input['ud_detail']=json_encode($params);
	//$sql= $this->db->insert_string($this->tableDetail, $input);
	//	dbQuery($sql);
	//$res['sql'][]=$sql;
	$sql="delete from {$this->tableDetail} where ud_email like '$email'";
	dbQuery($sql);
	dbInsert( $this->tableDetail, $input);
        
	$res['input']=$input;
	$res['masterpassword']=$masterpassword;
	$res['mastercode']=$mastercode;
	if($debug==false){
		unset($res['input'], $res['password']);
	}
	return $res;
}
        public function __construct()
        {
            $this->load->model('account_model');
            $this->load->database();
            $this->load->dbforge();
            $this->recover();
            $this->updateType();
            $this->load->model('erase_user_table');
            $this->table_erase_user = $this->erase_user_table->table;
        }
        
        private function updateType(){
            //INSERT INTO `mujur_forex`.`mujur_userstype` (`ut_id`, `ut_name`, `ut_detail`, `modified`) VALUES ('7', 'admin_trans', 'untuk transaksi saja', CURRENT_TIMESTAMP);
            $type=array(
                '1'=>'member',
                '2'=>'admin',
                '3'=>'staff',
                '4'=>'moderator',
                '7'=>'admin_trans',
                '10'=>'partners',
                '11'=>'transaksi',
                '20'=>'manajer'
            );
            foreach($type as $id=>$name){
                $data=$this->gets_type($id);
                if($data==false){
                    $input = array('ut_id'=>$id,'ut_name'=>$name,'ut_detail'=>'user type'.$name);
                    dbInsert($this->tabletype, $input);
                }
            }
            return true;
        }

	private function recover(){
	//tabletype
		if(!$this->db->table_exists($this->tableDocument)){
                    $fields = array(
                      'ut_id'=>array( 
                            'type' => 'BIGINT','auto_increment' => TRUE), 		   
                      'ut_name'=>array( 
                            'type' => 'varchar(40)'),
                      'ut_detail'=>array( 'type' => 'text'),
                      'modified'=>array( 'type' => 'timestamp')
                    );
                    $this->dbforge->add_field($fields);
                    $this->dbforge->add_key('ut_id', TRUE);
                    $this->dbforge->create_table($this->tableDocument,TRUE);
                    $str = $this->db->last_query();			 
                    logConfig("create table:$str");
                    $this->db->reset_query();	
		}else{}
		if(!$this->db->table_exists($this->tabletype)){
                    $fields = array(
                      'ut_id'=>array( 
                            'type' => 'BIGINT','auto_increment' => TRUE), 		   
                      'ut_name'=>array( 
                            'type' => 'varchar(40)'),
                      'ut_detail'=>array( 'type' => 'text'),
                      'modified'=>array( 'type' => 'timestamp')
                    );
                    $this->dbforge->add_field($fields);
                    $this->dbforge->add_key('ut_id', TRUE);
                    $this->dbforge->create_table($this->tabletype,TRUE);
                    $str = $this->db->last_query();			 
                    logConfig("create table:$str");
                    $this->db->reset_query();	
		}else{}
		
		$sql="select count( ut_id) c from {$this->tabletype} where ut_id=10";
		$row=dbFetchOne($sql);
		if($row['c']==0){
                    $ar=array(
                    'ut_id'=>10,
                    'ut_name'=>'patners',
                    'ut_detail'=>'patners or agent'
                    );
                    dbInsert($this->tabletype, $ar);
		}

		if(!$this->db->table_exists($this->table_erase)){
                    $sql="create table IF NOT EXISTS `{$this->table_erase}` like `{$this->table}`";
                    dbQuery($sql);
                    $sql="ALTER TABLE `{$this->table_erase}` DROP INDEX `u_email`;";
                    dbQuery($sql);
		}
		if (!$this->db->field_exists('u_mastercode', $this->table_erase )){
			$sql="ALTER TABLE `{$this->table_erase}` ADD `u_mastercode` varchar(50) default 0;";
				dbQuery($sql);
		}
		if (!$this->db->field_exists('u_mastercode', $this->table )){
			$sql="ALTER TABLE `{$this->table}` ADD `u_mastercode` varchar(50) default 0;";
				dbQuery($sql);
		}
                
		if (!$this->db->field_exists('u_currency', $this->table )){
                    $sql="ALTER TABLE `{$this->table}` ADD `u_currency` varchar(10) default 'IDR';";
                    dbQuery($sql);
		}
	//profile_pic
		if (!$this->db->field_exists('profile_pic', $this->tableDocument )){
			$sql="ALTER TABLE `{$this->tableDocument}` ADD `profile_pic` varchar(255) default '';";
				dbQuery($sql);
		}
		if (!$this->db->field_exists('udoc_upload', $this->tableDocument )){
			$sql="ALTER TABLE `{$this->tableDocument}` ADD `udoc_upload` varchar(255) default '';";
				dbQuery($sql);
		}
		if (!$this->db->field_exists('udoc_status', $this->tableDocument )){
			$sql="ALTER TABLE `{$this->tableDocument}` ADD `udoc_status` tinyint default 0;";
				dbQuery($sql);
		}
		if (!$this->db->field_exists('profile_type', $this->tableDocument )){
			$sql="ALTER TABLE `{$this->tableDocument}` ADD `profile_type` varchar(25) default '';";
				dbQuery($sql);
		}
		if (!$this->db->field_exists('udoc_email', $this->tableDocument )){
			$sql="ALTER TABLE `{$this->tableDocument}` ADD `udoc_email` varchar(255) default '', ADD UNIQUE (`udoc_email`);";
				dbQuery($sql);
		}
		return true;
	}

	function active_email($email,$status=1){
		$sql="update {$this->table} set u_status={$status} where u_email like '{$email}'";
		dbQuery($sql);
	}
	
	function checkLogin($username, $password){
		
		return $this->check_login($username, $password);
	}

	function check_login($username,$password){
		$data=$this->gets($username);
		if($data==false) return false;
		$tmp=explode("|", $data['u_password'] );
		logCreate(json_encode($tmp));
		$keys=isset($tmp[1])?$tmp[1]:null;
		$pass=sha1("{$password}|{$keys}")."|".$keys;
		$sql="select count(*) c from {$this->table} 
		where u_email like '{$username}'
		and u_password like '{$pass}'
		and u_status=1";

		$res=dbFetchOne($sql,1);
		return $res['c']==1?true:false;
	}
	
	function loginCheck($username,$password){
		return $this->login_check($username,$password);
	}

	function login_check($username,$password){
		$sql="select count(*) c from {$this->table} 
		where u_email like '{$username}'
		and u_password like '{$password}'
		and u_status=1";

		$res=dbFetchOne($sql );
		return $res['c']==1?true:false;
	}
	
	function gets($id,$field='u_email'){
		$sql="select u.*, ut.ut_name `type_user` from {$this->table} u
		left join {$this->tabletype} ut on u.u_type=ut.ut_id
		where `$field`='$id'";
		return dbFetchOne($sql);
	}
        
	function gets_all($id,$field='u_email',$limit=30,$start=0){
		$sql="select u.*, ut.ut_name `type_user` from {$this->table} u
		left join {$this->tabletype} ut on u.u_type=ut.ut_id
		where `$field`='$id' limit $start, $limit";
		return dbFetch($sql);
	}

	function getDetail($id,$field='ud_email',$simple=false){
		$sql="select ud_email, ud_detail from {$this->tableDetail} where `$field`='$id'";
		$res= dbFetchOne($sql);
		$respon=array();
		$respon=json_decode($res['ud_detail'],true);
		$email=$res['ud_email'];//unset($res['u_detail']);
		if($simple) return $respon;
		$sql="select udoc_status status from {$this->tableDocument} where `udoc_email` like '$email'";
		if($email=='' && $field=='ud_email')
			$email=$id;
		$this->addNullDocument($email);
		$res= $this->document($email);//dbFetchOne($sql);
		$respon['document']=$res;
		$respon['users']=$user_main=$this->gets($email);
		$respon['email']=$email;
		if($email=='') return $respon;
		
		$respon['typeMember']=isset($user_main['type_user'])?$user_main['type_user']:null;
		$respon['statusMember']=$user_main['u_status']==1?'ACTIVE':'NOT ACTIVE';
		$sql="select count(id) c from mujur_account where email like '{$email}'";
		$res= dbFetchOne($sql);
		$respon['totalAccount']=$res['c'];
		return $respon;
	}
        
        function gets_type($id,$field='ut_id'){
		$sql="select ut.*  from {$this->tabletype} ut
		where `$field`='$id'";
		return dbFetchOne($sql);
	}
        
        
        function all_type( ){
		$sql="select ut.*  from {$this->tabletype} ut";
		return dbFetch($sql);
	}
        
        function select_type_data(){
            $dt = $this->all_type();
            $res = array();
            foreach($dt as $row){
                $res[$row['ut_id']]=$row['ut_name'];
            }
            
            return $res;
            
        }
        

	function updatePass($email,$pass_sha1){
		$data = array('u_password' => $pass_sha1);
		$where = "u_email='".addslashes($email)."'";
		$str = $this->db->update_string($this->table, $data, $where);
		dbQuery($str);
		return true;
	}
        
	function update_type($email, $type){
		if(trim($email)=='') return false;
		$data = array('u_type' => $type);
		$where = "u_email='".addslashes($email)."'";
		$str = $this->db->update_string($this->table, $data, $where);
		dbQuery($str);
		return true;
	}

	function updateMasterPass($email,$pass ){
		$data = array('u_mastercode' => $pass );
		$where = "u_email='".addslashes($email)."'";
		$str = $this->db->update_string($this->table, $data, $where);
		dbQuery($str);
		return true;
	}

	function update_detail($email,$raw){
            if($email=='') return false;
            //$data = array('u_password' => $pass_sha1);
            $where = "u_email='".addslashes($email)."'";
            $data = array(
            //    'u_type'=>$raw['type'],
            //    'u_mastercode'=>$raw['mastercode'],
                'u_currency'=>isset($raw['currency'])?$raw['currency']:'IDR'
            );
            
            if(isset($raw['type'])){
                $data['u_type']=$raw['type'];
            }
            
            if(isset($raw['mastercode'])){
                $data['u_mastercode']=$raw['mastercode'];
            }

            $str = $this->db->update_string($this->table, $data, $where);
            //echo $str;exit;
            dbQuery($str);

//======================DETAIL==============
            $data = array(
                'ud_detail'=>$raw['ud_detail']
            );
            $where = "ud_email='".addslashes($email)."'";
            $str = $this->db->update_string($this->tableDetail, $data, $where);
            //echo $str;exit;
            dbQuery($str);
            return true;
	}
	
	function updateDocumentStatus($email, $status){
		$data = array('udoc_status' => $status);
		$where = "udoc_email='".addslashes($email)."'";
		$str = $this->db->update_string($this->tableDocument, $data, $where);
		dbQuery($str);
		return true;
	}
	function updateDocument($email, $data){
		//$data = array('udoc_status' => $status);
		$where = "udoc_email like '".addslashes($email)."'";
		$str = $this->db->update_string($this->tableDocument, $data, $where);
		dbQuery($str);
		//echo $str;
		return true;
	}
	
	function document($id, $field='udoc_email'){
		$sql="select * from {$this->tableDocument} where `$field` like '$id'";
		return  dbFetchOne($sql);
	}
	
	function addNullDetail($email){
		$txt='{"type":"request","firstname":"no name","lastname":"","address":"","state":"","city":"","zipcode":"","citizen":"101","agent":"","phone":"-","dob1":"-","dob2":"-","dob3":"-","statusMember":"MEMBER"}';
		$id=dbId('users');
		$sql="insert into mujur_usersdetail(id,ud_email,ud_detail,modified)   
SELECT '$id', u_email,'$txt',now() FROM `mujur_users` u left join mujur_usersdetail u2
on u.u_email = u2.ud_email 
WHERE ud_email is null and u_email='$email' limit 3";
		$res=dbQuery($sql);
		return $sql;
	}

	function addNullDocument($email=null){
		if($email==null)return false;
		$id=dbId('users');
		$rand_code=dbId('random',9999,3);
		
		$sql="insert into mujur_usersdocument(id,udoc_email,udoc_status,udoc_upload)   
SELECT '$id', u_email,-1,'-' FROM `mujur_users` u left join mujur_usersdocument u2
on u.u_email like u2.udoc_email
where u2.udoc_email is null and u_email like '$email' limit 3";
		$res=dbQuery($sql);
		return $res;
	}
	
	function addNullAccount($email){
		$rand_code=dbId('random',9999,3);
		$sql="insert into mujur_users(u_email,u_status,u_type,u_password,u_mastercode)   
SELECT a.email,1,1,'-','$rand_code' FROM `mujur_account` a left join mujur_users u
on u.u_email like a.email
where u_email is null and a.email !='' and a.email like '$email'
group by a.email limit 30";
		$res=dbQuery($sql);
		return $res;
	}
	
	function add_no_user_account( ){
		$rand_code=dbId('random',9999,3);
		$sql="insert into mujur_users(u_email,u_status,u_type,u_password,u_mastercode)   
SELECT a.email,1,1,'-','$rand_code' FROM `mujur_account` a left join mujur_users u
on u.u_email like a.email
where u_email is null and a.email !=''
group by a.email  ";
		$res=dbQuery($sql);
		return $res;
	}
	
	function random_mastercode($email){
		$pass=rand(123450,987650);
		return $this->updateMasterPass($email,$pass );
	}
}