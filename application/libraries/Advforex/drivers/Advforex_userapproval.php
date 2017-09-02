<?php //Advforex_forex_summary
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
Advforex_forex_balance
*/
class Advforex_userapproval extends CI_Driver {
private $urls,$privatekey;
public $CI;
	function execute($params){
	$CI =& get_instance();
		$result=array('params'=>$params);
		$result['time'][ ]=microtime(true);
		$post0=$params['post0'];
		$times=array( microtime());
	//	return $result;
		ob_start();
		//api_data
		$result=array( 'draw'=>isset($post0['draw'])?$post0['draw']:1);
		$aOrder=array(
		'created','username0','username','email'
		);
		$sql="select count(id) c from `mujur_usersdocument`";
		$dt=dbFetchOne($sql);
		$result['time'][ ]=microtime(true);
		$result['sql'][]=$sql;
		$times['count all'] =microtime();
		$result['recordsTotal']=$dt['c'];
		$result['recordsFiltered']=$dt['c']; //karena tidak ada filter?!

		$start=isset($post0['start'])?$post0['start']:0;
		$limit=isset($post0['length'])?$post0['length']:11;
		$data=array();
		   $orders="order by udoc.modified desc";
		   if(isset($post0['order'][0])){
				$col=$post0['order'][0]['column'];
				$order=$post0['order'][0]['dir'];
				$col2=$post0['columns'][$col]['data'];

				if($col==0){
					$col2='udoc.modified';
				//	$order='desc';
				}
				if($col==3){
					$col2='u.u_email';
				}
				if($col==5){
					$col2='udoc_status';
				}
		 
				$orders="order by {$col2} {$order}";
				
		   }
		   $where='1';
		$search=isset($post0['search']['value'])?$post0['search']['value']:'';

		if($search!=''&&strlen($search)>3){
			$where="u.u_email like '{$search}%'";
			$where.="or ud.ud_detail like '{$search}%'";
			$sql="select count(u.u_id) c from mujur_usersdocument udoc
			left join mujur_users u on u.u_email = udoc.udoc_email
			left join mujur_usersdetail ud on u.u_email = ud.ud_email			
			where u_email is not null and ($where) ";
		/*
		left join mujur_accountdetail ad 
			on a.username=ad.username
		*/	
			$res=dbFetchOne($sql,1);
			$result['time'][ ]=microtime(true);
			$times['count Filtered']=microtime();
			$result['sql'][]=$sql;
			$result['recordsFiltered']=$res['c'];
		}
		else{
			$times['count no search']=microtime();
			logCreate('no search :'.$search);
		}

		$where2='';//' and u_email like "gundambison%" ';
		$sql="select u.u_id id,u.u_email email,udoc.modified,udoc.udoc_status status_document from 
		mujur_usersdetail ud 
		left join mujur_users u on u.u_email = ud.ud_email
		left join mujur_usersdocument udoc on u.u_email = udoc.udoc_email
			where ud_email is not null and ($where) $where2

		$orders limit $start, $limit";
		/*
                 * 		group by u_email
		left join mujur_accountdetail ad 
			on a.username=ad.username
		*/	
		//
		$result['sql'][]=$sql;
		$data=array();
		logCreate('sql :'.$sql);

		$dt=dbFetch($sql);
		$result['time'][ ]=microtime(true);
		$times['run query']=microtime();
		logCreate('total :'.count($data));
		foreach($dt as $row){
			$result['time'][ ]=microtime(true);
			$row['raw']=$detail=$CI->users_model->getDetail( $row['email'] );
			$result['time'][ ]=microtime(true);
			$times['detail '.$row['email']]=microtime();
			$row['firstname']= isset($detail['name'])?$detail['name']:'User Forex';
			logCreate('search :'.$row['email']);
			
			unset($detail['raw']);
		//	foreach($detail as $nm=>$val){ $row[$nm]=$val; }
			$row['status']='Not Active';
			$status_document=isset($detail['document']['udoc_status'])?$detail['document']['udoc_status']:false;

			if($status_document==1)$row['status']='Active';
			if($status_document==0||$status_document==2 )$row['status']='Review';
			
			$row['action']='';
			$row['full_name']=isset($detail['name'])?$detail['name']:'User Forex';
		//accountid
			$row['main_email']= $row['email'];
			$row['username']= "total :".$detail['totalAccount'];
			$row['created']=isset($detail['document']['modified'])?$detail['document']['modified']:false;
			$row['accounttype']=isset($detail['typeMember'])?$detail['typeMember']:false;
			$data[]=$row;
		}
 
		$result['data']=$data;
		$result['times']=$times;
		unset($result['time']);
		$result['params']=$params;
		$warning = ob_get_contents();
		ob_end_clean();
		if($warning!=''){
			$result['warning']=$warning;     
		}

		return $result;
	}

	function __CONSTRUCT(){
	$CI =& get_instance();
	$CI->load->helper('api');
	$CI->load->model('users_model');
	//$CI->config->load('forexConfig_new', TRUE);
    $this->urls = $urls=$CI->config->item('apiForex_url' );
    $this->privatekey = $CI->config->item('privatekey' );

	}	
}