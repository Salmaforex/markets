<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Advforex_agent extends CI_Driver {
private $urls,$privatekey;
public $CI;
	function __CONSTRUCT(){
		$CI =& get_instance();
		$CI->load->helper('api');
		//$CI->config->load('forexConfig_new', TRUE);
		$this->urls = $urls=$CI->config->item('apiForex_url' );
		$this->privatekey = $CI->config->item('privatekey' );

	}

	function execute($raw_data){
	$CI =& get_instance();
/*----------*/
		$post=$post0=$raw_data['post0'];
	//	$respon['docUpdate']=$CI->users_model->addNullAccount();
		ob_start();
		logCreate('start DATA = agent');
		//api_data
		$respon=array( 'draw'=>isset($post['draw'])?$post['draw']:1);
		$aOrder=array(
		'created','username0','username','email'
		);

		$sql="select count(id) c from `mujur_account` a 
		where type like 'AGENT'";
		$dt=dbFetchOne($sql);
		//$this->db->query($sql)->row_array();
		$respon['recordsTotal']=$dt['c'];
		$respon['recordsFiltered']=$dt['c']; //karena tidak ada filter?!
		logCreate('respon:'.json_encode($respon));

		$start=isset($post0['start'])?$post0['start']:0;
		$limit=isset($post0['length'])?$post0['length']:11;
		$data=array();
		   $orders="order by a.modified desc";
		   if(isset($post0['order'][0])){
				$col=$post0['order'][0]['column'];
				$order=$post0['order'][0]['dir'];
				$col2=$post0['columns'][$col]['data'];
				if($col==0){
					$col2='a.modified';
				}
				if($col==2){
					$col2='a.email';
				}
				if($col==3){
					$col2='a.email';
				}
				if($col==5){
					$col2='a.udoc_status';
				}
				$orders="order by {$col2} {$order}";
				
		   }
		   $where='1';
		$search=isset($post0['search']['value'])?$post0['search']['value']:'';
		if($search!=''&&strlen($search)>2){
			$where="a.email like '%{$search}%'";
			$where.=" or a.accountid like '{$search}%'";
			//$where.=" or ad.detail like '%{$search}%'";
			$sql="select count(id) c from mujur_account a
			where $where and type like 'AGENT'";
		/*
		left join mujur_accountdetail ad 
			on a.username=ad.username
		*/


			$res=dbFetchOne($sql,1);
			$respon['sql'][]=$sql;
			$respon['recordsFiltered']=$res['c'];
			logCreate('respon:'.json_encode($respon));
		}
		else{
			logCreate('no search :'.$search);
		}

		$sql="select a.id acc_id,a.created created, u.*, a.email main_email, a.accountid username from 
		`mujur_account`  a 
		left join `mujur_users` u on u.u_email like a.email
		where $where and type like 'AGENT'
                $orders
		limit $start,$limit";
                //group by acc_id
		/*
		left join mujur_accountdetail ad 
			on a.username=ad.username
		*/	
		//logCreate('sql :'.$sql);
		$respon['sql'][]=$sql;
		$dt=dbFetch($sql,0,1);
		//$this->db->query($sql)->result_array();
		logCreate('total agent:'.count($dt)); //exit();
		foreach($dt as $row){
		//	var_dump($row);exit();
			$row['raw']=$row;
			$row['raw user']=$detail=$CI->users_model->getDetail($row['main_email']);
			$row['raw account']=$account=$CI->account_model->detail_basic($row['acc_id']);
			//echo_r($detail);exit();
			//detail($row['id']);
			$row['firstname']= isset($detail['firstname'])?$detail['firstname']:'-';

			//logCreate('search :'.$row['id']);
			
			unset($detail['raw']);
			foreach($detail as $nm=>$val){ $row[$nm]=$val; }
			$row['status']='Not Active';
/*
			if($row['status_document']==1)$row['status']='Active';
			if($row['status_document']==2)$row['status']='Review';
*/
			//$row['username']= "total :".$detail['totalAccount'];//$row['accountid'].".";
			$row['action']='';
			$row['email']=$row['main_email'].".";
			$row['accounttype']=$account['type'] ;
			//==1?'MEMBER':'OTHER';
			$data[]=$row;
		}

		$respon['data']=$data;
		$respon['-']=$post0;  
		$warning = ob_get_contents();
		ob_end_clean();
		if($warning!=''){
			$respon['warning']=$warning;     
		}

		if(isset($respon)){ 
				return $respon;
		}
		else{
			return array();
		}
	}
	
	function convert_to_agent(){
	$CI =& get_instance();
		$rand_code=dbId('random',9999,3);
		$respon=array(microtime());
		$sql="insert into mujur_users(u_email,u_status,u_type,u_password,u_mastercode)   
SELECT a.email,1,1,'-','$rand_code' FROM `mujur_account` a left join mujur_users u
on u.u_email like a.email
where u_email is null and a.email !=''
group by a.email limit 30";
		$respon['sql'][]=$sql;
		$res=dbQuery($sql);
		$respon[]=microtime();
		$sql="insert into mujur_usersdocument(id,udoc_email,udoc_status,udoc_upload)   
SELECT '$id', u_email,-1,'-' FROM `mujur_users` u left join mujur_usersdocument u2
on u.u_email like u2.udoc_email
where u2.udoc_email is null and  limit 30";
		$respon['sql'][]=$sql;
		$res=dbQuery($sql);
		$respon[]=microtime();
		$sql="select a.id acc_id,a.created created, u.*, a.email main_email, a.accountid username,u_type

from 		`mujur_account`  a 		left join `mujur_users` u on u.u_email like a.email		where 1 and type like 'AGENT'		group by a.id		
ORDER BY `u`.`u_type` ASC	limit 10";
		$respon['sql'][]=$sql;
		$respon['agent']=dbFetch($sql);
		$respon[]=microtime();
		return $respon;
	}
}