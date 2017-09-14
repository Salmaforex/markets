<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Advforex_register extends CI_Driver {
private $urls,$privatekey;
public $CI;
function execute(){
//	if($row==false) return false;
	$CI =& get_instance();
//====================
		$CI->load->model('forex_model','forex');
		$CI->load->model('account_model','account');
		$CI->load->model('country_model','country');
		$defaultLang="english";
		$CI->lang->load('forex', $defaultLang);
//====================
	ob_start();
	$succes=false;
	$register= $CI->account->newAccountWithoutPassword(40,'where reg_status=1');
	foreach($register as $row){
		$post=array('username'=>$row['username']);
		$params2=array('post'=>$post);
		$CI->load->view('depan/data/login_data',$params2);
	} 

	$register=$CI->forex->regisAll(40,'where reg_status=1');
	logCreate("register:".json_encode($register));
	$data=array();

	foreach($register as $row){
		$reg_id=$row['id'];
		echo "\n<br/>start reg_id:{$reg_id}";
		$dt0=$CI->forex->regisDetail($row['id']);
		$full_name=isset($dt0['detail']['firstname'])?$dt0['detail']['firstname']:'';
		$full_name.=" ". (isset($dt0['detail']['lastname'])?$dt0['detail']['lastname']:'');
		$full_name=substr($full_name,0,126);
	//	print_r($dt0['']);
		if($dt0['status']!=1){
			logCreate("register id:".$row['id']."|status:".$dt0['status'],'info');
			echo "\n<br/>(failed) status reg_id:{$reg_id}. =".$dt0['status'];
			continue;
		}
		else{
			logCreate("register id:".$row['id']."|".json_encode($dt0));	
			echo "\n<br/>status reg_id:{$reg_id} =".$dt0['status'];
		}
		
		$email=trim($dt0['email']);
		
		//$account= $CI->forex->accountDetail($email,'email');
		
		if(trim($email)==''){//$account!==false||
			logCreate("register delete ($email) (empty):".print_r($account,1));
			$CI->forex->regisDelete($dt0['email']);
			echo "\n<br/>status reg_id:{$reg_id}| email:".$email;
			continue;
			
		}
		else{ 
			logCreate("register email:($email)");
			echo "\n<br/>register reg_id:{$reg_id}. email:".$email;
			if($email===NULL){
				$CI->forex->regisErase($reg_id,'reg_id',-3);
				echo "\n<br/>status reg_id:{$reg_id}. email:".$email;
				continue;
			}
		}
	//	die('email??:'.$email.print_r($account,1) );
		$arr=array( 'raw'=>$dt0);
		$dt=$dt0['detail'];
	//=================send
		$url=$this->urls['register'];//$CI->forex->forexUrl('register');
		
		$param=array( );
		$param['privatekey']	= $this->privatekey; //$CI->forex->forexKey();
	//======Required 
		$param['username']	=   $full_name; //$dt0['detail']['firstname'];
		if($dt['email']!=''){
				$param['email']		=substr($dt['email'],0,47);
		}
	//======Optional
		if($dt['address']!='')
			$param['address']	=trim($dt['address']);
                
		if($dt['zipcode']!='')
			$param['zip_code']	=trim($dt['zipcode']);
                
		if($dt['email']!='')
			$param['email']		=$dt['email'];
                
		if($dt['country']['name']!='')
			$param['country']	=$dt['country']['name'];
                
		if($dt['phone']!='')
			$param['phone']		=$dt['phone'];

		if($dt0['agent']!=''){
			$param['agentid']	=trim($dt0['agent']);	
		}

	//	$url.="?".http_build_query($param);
	//	$arr['param']=$param;
	//	$arr['url']=$url;
	//--------- PERINTAH PEMBUATAN	
	/*
		logCreate("param:".print_r($param,1));
		$rawAccount=$CI->account->detail($reg_id, 'reg_id');
		//apabila ada reg_id yang sama maka cancel	

			if($rawAccount!=false){
				logCreate("register not continue reg_id exist:".json_encode($rawAccount));
				continue;
			}
			else{
				$result0= _runApi($url );
			}
	*/
		echo "\n<br/>run register param total:".count($param);
		$res= $CI->advforex->runApi($url,$param);
		$result0=isset($res['response'])?$res['response']:false;
		//echo "<pre>".print_r($res,1)."</pre>";
		if( !isset($result0['ResponseCode'])){
			echo "<pre>".print_r($result0,1)."</pre>";
			continue;
                        
		}

		if( isset($result0['ResponseCode'])&& $result0['ResponseCode']==0){
			echo "\nrespon=0 (OK)";
			$result=array(
				'accountid'=>isset($result0['AccountID'])?$result0['AccountID']:null,
				'masterpassword'=>isset($result0['MasterPassword'])?$result0['MasterPassword']:null,
				'investorpassword'=>isset($result0['InvestorPassword'])?$result0['InvestorPassword']:null,
				'responsecode'=>isset($result0['ResponseCode'])?$result0['ResponseCode']:-100,
				
			);
			$dtAPI=array(
				'url'=>'register new(1)',
				'parameter'=>json_encode($param),
				'response'=>json_encode($result),
				'error'=>'-1'
			);
			$sql=$CI->db->insert_string($CI->forex->tableAPI, $dtAPI);
            dbQuery($sql);
                        //$CI->db->insert($CI->forex->tableAPI,$dtAPI);
		}
		else{
			echo "\nrespon=?? (not OK)|".$result0['ResponseCode'];
			$result=$result0;
			if(!isset($result['ResponseCode'])){
				$result['responsecode']=-100;
			}
			else{
				$result['responsecode'] = $result['ResponseCode'];
			}

		}
//=========AGENT MASALAH??
		if(isset($result['responsecode'])&& ((int)$result['responsecode']==7||(int)$result['responsecode']==5||(int)$result['responsecode']==2) ){
			logCreate("agent bermasalah?:".print_r($result ,1));
			echo "\nagent bermasalah?:".$result['responsecode'];
			//=================send
		   		
		   $param=array( );
		   $param['privatekey']	=$this->privatekey	;
	//======Required 
		   $param['username']	=   $full_name;
		   if($dt['email']!=''){
				$param['email']		=substr($dt['email'],0,47);
		   }
	/*		   
	//======Optional	
		   if($dt['address']!='')
			$param['address']	=$dt['address'];	
		   if($dt['zipcode']!='')
			$param['zip_code']	=$dt['zipcode'];	
		   
		   
	*/
                    if($dt['country']['name']!=''){
			$param['country']	=$dt['country']['name'];
                    }
                    
		   if($dt['phone']!=''){
			$param['phone']		=$dt['phone'];
                   }
                   
                    $url.="?".http_build_query($param);
                    $res= $CI->advforex->runApi($url);//,$param);
                    $result0=isset($res['response'])?$res['response']:false;

                    if(isset($result0['ResponseCode'])&& ((int)$result0['ResponseCode']==1||(int)$result0['ResponseCode']==9) ){
		   //isset($result0['status'])&&isset($result0['code'])&&$result0['status']==1&&$result0['code']==9){
                        $result= $result0;
                        logCreate("agent bermasalah V1 result:".print_r($result,1)); 
                        echo "\nagent bermasalah(2)?:".$result['ResponseCode'];
                    }
                    else{
                        $result=array(
                        'accountid'=>isset($result0['AccountID'])?$result0['AccountID']:null,
                        'masterpassword'=>isset($result0['MasterPassword'])?$result0['MasterPassword']:null,
                        'investorpassword'=>isset($result0['InvestorPassword'])?$result0['InvestorPassword']:null,
                        'responsecode'=>isset($result0['ResponseCode'])?$result0['ResponseCode']:-100,

                        );
                        echo "\nagent bermasalah(3)?:".print_r($result ,1);
                        logCreate("agent bermasalah? agent bermasalah v2 result:".print_r($result ,1)); 

                        $dtAPI=array(
                                'url'=>'register new(2)',
                                'parameter'=>json_encode($param),
                                'response'=>json_encode($result),
                                'error'=>'-1'
                        );
			$sql=$CI->db->insert_string($CI->forex->tableAPI, $dtAPI);
			dbQuery($sql);
                        //$CI->db->insert($CI->forex->tableAPI,$dtAPI);
		   }
		   
		}
		else{}
	/*	
		if(isset($result['responsecode'])&&(int)$result['responsecode']==5){
			logCreate("delete Respon code 5");
			$CI->forex->regisDelete($dt0['email'],5); 
			continue;
		}
	*/	
		if(isset($result['responsecode'])&&(int)$result['responsecode']==8){
			logCreate("delete Respon code 8");
	//		$CI->forex->regisDelete($dt0['email'],8);
			echo "\n<br/>register bermasalah?:".$result['responsecode'];
			continue;
		}
		
		if(isset($result['responsecode'])&&(int)$result['responsecode']==0){
			logCreate('register member |url: '.$CI->forex->forexUrl().'|respon:'.print_r($result,1).' |url:'.$url, 
				'info');
			$param=array( );
			$param['privatekey']	=$this->privatekey;
			$param['accountid']=(int)$result['accountid'];
			$param['allowlogin']=1;
			$param['allowtrading']=1;
			
			$url=$this->urls['update'];
		//	$url.="?".http_build_query($param);
			logCreate("update allow:".print_r($param,1)."|url:$url");
			$arr['param']=$param;
			$arr['url']=$url;
			//$result0= _runApi($url );
			$res= $CI->advforex->runApi($url,$param);
			$result0=isset($res['response'])?$res['response']:false;
			logCreate("update allow result:".print_r($result0,1));
			$dtAPI=array(
				'url'=>'update new(1)',
				'parameter'=>json_encode($param),
				'response'=>json_encode($res),
				'error'=>'-1'
			);
			$sql=$CI->db->insert_string($CI->forex->tableAPI, $dtAPI);
                        dbQuery($sql);
                        //$CI->db->insert($CI->forex->tableAPI,$dtAPI);
			
			$id=$CI->forex->accountActivation($row['id'],$result);
			$arr['accountActivation']=$id;
			echo "\n<br/>activasi :".json_encode($result0);
	 
		}
		else{ 
			$arr['accountActivation']=false;
			$num=isset($result['responsecode'])?$result['responsecode']:'unknown';
			if(lang('resApi_'.$num)=='')$num='unknown';
			logCreate('register member |num:'.$num.' |message:'.lang('resApi_'.$num),'error');
			logCreate('register member |url:'.$CI->forex->forexUrl().'|respon:'.print_r($result,1).'|url:'.$url, 
				'error');
			echo "\n<br/>activasi Bermasalah?:".json_encode($result);
			
		}
		echo "\nregister end (next)" ;
		$arr['result']=$result;	
		$data[]=$arr;
	}
		echo "\nregister end (DONE)" ;

	$succes=true;

	$content=ob_get_contents();
	ob_end_clean();

	$result=array(
		'body'=>$content, 
		'data'=>$data, 
	);

	//==========IF OK
	if($succes===true)
		$result['succes']=true;

	$result['total_users']=$this->update_login();

	return $result;
	}

	function update_login(){
	$CI =& get_instance();
	return true;
	$sql="insert into mujur_users (u_email,u_password,u_type,u_status)
SELECT a.email, md5('salmamarket'), 1,1 
FROM `mujur_account` a
left join mujur_users u on a.email=u.u_email
where u.u_email is null and a.email !=''
 group by email";
	dbQuery($sql);
        $sql="alter table mujur_users order by u_email";
	dbQuery($sql);
        $sql="select count(*) c from mujur_users";
	$res=dbFetchOne($sql);
	return $res['c'];
    }   

	function example($row=false){
		if($row==false) return false;
		$CI =& get_instance();

	}

    function __CONSTRUCT(){
	$CI =& get_instance();
	$CI->load->helper('api');
	//$CI->config->load('forexConfig_new', TRUE);
        $this->urls = $urls=$CI->config->item('apiForex_url' );
        $this->privatekey = $CI->config->item('privatekey' );

    }
	
    function simple_register($params){
        $CI =& get_instance();
        $tableRegis= $CI->account_model->tableRegis;

        $res=array('params'=>$params);
        $param=$data_table=array( );
        $data_table['reg_id']=dbId();
        $data_table['reg_status']=1;
        $param['privatekey']	= $this->privatekey; //$CI->forex->forexKey();
//======Required

        if(isset($params['username'])&&$params['username']!=''){
            $param['username']	= $data_table['reg_username'] =  substr($params['username'],0,47); //$dt0['detail']['firstname'];
        }
        elseif(isset($params['name'])&&$params['name']!=''&&$params['name']!==false){
            $param['username']	= $data_table['reg_username'] =  substr($params['name'],0,47);
        }
        else{
            $param['username']	= $data_table['reg_username'] = 'user forex';
        }
        //unset($param['username']);

        if(isset($params['email'])&&$params['email']!=''){
                $param['email'] = $data_table['reg_email']=trim($params['email'] );
        }

        if(isset($params['agent'])&&$params['agent']!=''){
                $param['agentid']	= $data_table['reg_agent'] = $params['agent'];
        }
        
        $param['country'] = isset($params['country'])?$params['country']:'Indonesia';
        
        ///===========SAVE REGISTER
        $data_table['reg_password']='----';
        $data_table['reg_investorpassword']='---';
        $data_table['reg_detail']='---';
        $data_table['reg_created']=date("Y-m-d H:i:s");
        $data_table['reg_agent']=isset($params['agent'])&&$params['agent']!=''?$param['agentid']:'';

        $res[]=$data_table;
        $url=$this->urls['register'];
        $res[]="run url:".$url;
        $res[]=$param;
//		return array($res, $data_table);
        dbInsert($tableRegis, $data_table);

 
        
        logCreate('run register => account (0):'.$url."=".json_encode(array_keys($param)));
        //$url.="?".http_build_query($param);
       if(defined('LOCAL')){
                $res['account']= $run =
                array(
                    'AccountID'=>'2000fake',
                    'MasterPassword'=>'xxxxxx',
                    'InvestorPassword'=>'zzzzzzz',
                    'ResponseCode'=>0,
                );
                return $res;
        }
        else{
            $run=_runApi($url ,$param);
        }
        
        $res[]=$run;
        if(isset($run['ResponseCode'])&&(int)$run['ResponseCode']==0){
            $res['account']=$run;
            logCreate('SUCCESS create account (1):'.json_encode($run));
            $res['save']=$this->save_table_account($run, $data_table);
            return $res;
        }

        logCreate('FAILED create account (2):'.json_encode($run));
        unset($param['agentid'],$data_table['reg_agent']);
        $url=$this->urls['register'];
        logCreate('run register create account (3):'.$url."=".json_encode(array_keys($param)));
        $url.="?".http_build_query($param);
        $run= _runApi($url);//,$param);
        $res[]=$run;
        if(isset($run['ResponseCode'])&&(int)$run['ResponseCode']==0){
            logCreate('SUCCESS create account (4):'.json_encode($run));
            $res['account']=$run;
            $res['save']=$this->save_table_account($run, $data_table);
            return $res;
        }
        else{}

        $res=false;
        logCreate('FAILED create account(5):'.json_encode($run));
        //=====================allowlogin dan allowtrading dilakukan via update =====
        return $res;
    }
	
	private function save_table_account($result, $register)
        {
            logCreate('advforex_register save_table_account result:'.json_encode($result)." |register:".  json_encode($register));
            $CI =& get_instance();
            $tableRegis= $CI->account_model->tableRegis;
            $tableAccount= $CI->account_model->tableAccount;
            $tableAccountDetail= $CI->account_model->tableAccountDetail;
            
            $sql="update  `$tableRegis` set `reg_status`=0 where reg_id='{$register['reg_id']}'";
            dbQuery($sql);
            $id=dbId();
            $data_table=array(
                    'id'=>$id,
                    'reg_id'=>$register['reg_id'],
                    'username'=>isset($register['reg_username'])?$register['reg_username']:'',
                    'investorpassword'=>md5($result['InvestorPassword']),
                    'masterpassword'=>md5($result['MasterPassword']),
                    'accountid'=>$result['AccountID'],
                    'email'=>$register['reg_email'],
                    'created'=>date('Y-m-d')
            );
            
            if(isset($register['reg_agent'])){
                    $data_table['agent']=$data_table['reg_agent'];
            }
            
            $data_table['type']='MEMBER';
            dbInsert($tableAccount, $data_table);
            logCreate('advforex_register save_table_account insert:'.$tableAccount." |data:".  json_encode($data_table));

            //email====
            $email_data=array(
                'username'=>$register['reg_email'],
                'email'=>$register['reg_email'],
                'password'=>false
            );

            $email_data['account']=array(
                'MasterPassword'=>$result['MasterPassword'],
                'InvestorPassword'=>$result['InvestorPassword']
            );

            $email_data['show']=true;
            logCreate('advforex_register save_table_account send email register |data: '.  json_encode($email_data));
            $CI->load->view('email/emailRegister_email',$email_data);

            $result[]=$data_table;
            $input=array();
            $input['detail']=json_encode($data_table);
            $input['username']=$result['AccountID'];
            
            dbInsert($tableAccountDetail, $input);
            logCreate('advforex_register save_table_account insert:'.$tableAccountDetail." |data:".  json_encode($input));
            $result[]=$input;
    //===================Update ACCOUNT ALL=================
            $driver_core='advforex';
            $driver_name='update_detail';
            $row=array( $register['reg_email'] );
            $func_name='execute';
            $result_register=$CI->{$driver_core}->{$driver_name}->{$func_name}($row);
            logCreate('advforex_register save_table_account update:'.$driver_name." |data:".  json_encode($row));
            
            return $result;
            
	}
}
