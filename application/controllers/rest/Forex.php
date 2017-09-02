<?php
require_once APPPATH.'/libraries/REST_Controller.php';
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
class Forex extends REST_Controller {
    function __construct(){
	parent::__construct();
	$this->load->helper('api');
	$this->load->database();
	$this->load->model('account_model');
	$this->load->model('password_model');
	$this->load->model('forex_model');
	header('Access-Control-Allow-Origin: *'); 
    }
	
	function index_post(){
		$post=$param=$this->post();
		$function=isset($post['function'])?$post['function']:'fail';
		$res=array('message'=>'unknown??', 'param'=>$post);
		logCreate('rest/forex post:'.json_encode($post));
		if(method_exists($this, $function)){
			logCreate('function (OK)'.$function);
			$data=isset($post['data'])?$post['data']:array();
			$res=$this->$function($data);
			if(isset($res['rest_code'])){
				$code=$res['rest_code'];
				unset($res['rest_code']);
				
			}
			else{
				$code=200;
			}
			$param['function']=$function;
			logCreate('rest/forex code:'.$code.'result'.json_encode($res));
			save_and_send_rest($code,$res,$param);
		}
		else{
			save_and_send_rest(204,$res,$param);
		}
	}

	function summary($param){
		$row=isset($param[0])? $param[0] :'';

		return $this->driver_run(array($row),'forex_summary');
	}

	function balance($param){
		$row=isset($param[0])? $param[0] :'';

		$return = $this->driver_run(array($row),'forex_balance');
		logCreate("forex balance data:".json_encode($param)." |result:".json_encode($return));
		return $return;
	}
	
	private function driver_run($array,$driver_name='forex_summary'){
		$this->load->driver('advforex'); /*gunakan hanya bila diperlukan*/
		$driver_core = 'advforex';
		$func_name='execute';
		$list_driver=$this->{$driver_core}->valid_drivers;
		//$func_name='execute';
		if( !in_array($driver_name, $list_driver)){
			$result=array('status'=>false, $driver_name.'(FAIL)',$driver_core.'(OK)',
			'tambahkan di list driver',$list_driver,
			'rest_code'=>245
			);
			return $result;
		}

		if( !method_exists($this->{$driver_core}->{$driver_name},$func_name) ){
			$result=array('status'=>false, $func_name.'(FAIL)',$driver_core.'(ok)', $driver_name.'(OK)',
			'rest_code'=>246);
			return $result;
		}
		else{
			$result=$this->{$driver_core}->{$driver_name}->{$func_name}($array);
			
		}
		return $result;
	}

	function account($param){
		$row=isset($param[0])? $param[0] :'';

		return $this->driver_run(array($row),'forex_account');
	}
        
   function report_email_get(){
       $param=array('post'=>$this->post());
       $param['start']=  microtime();
       $table=$this->forex_model->tableBatchEmail;
       $sql="select date(created) tgl, hour(created) jam, count(*) total  from `$table` where 1
 group by date(created), hour(created) order by created desc limit 100;";
       $code=200;
       $res=array('email'=>dbFetch($sql));
       $param['end']=  microtime();
       
       $res['query']=$sql;
       save_and_send_rest($code,$res,$param);
   }
   
   function report_email_post(){
       $this->report_email_get();
   }
   
   function report_trans_get(){
       $query=array();
   $param=array('post'=>$this->post());
       $param['start']=  microtime();
       $table=$this->forex_model->tableFlowlog;
      $code=200;
       $sql="select month(created) bulan,year(created) tahun,types,count(id) total "
               . "from `$table` group by month(created),year(created),types order by created asc";
       $res=$res_data=dbFetch($sql);
       $query[]=$sql;
       
       $result=array();
       foreach($res as $row){
           $key = $row['bulan']."_".$row['tahun']."_".$row['types'];
           $result[$key]=array('total'=>$row['c'],'valid'=>0);
           
       }
       //=====
       $sql="select month(created) bulan,year(created) tahun,types,count(id) total "
               . "from `$table` where status=1"
               . "group by month(created),year(created),types order by created asc";
       $res=$res_dataOK=dbFetch($sql);
       $query[]=$sql;
       
       $result=array();
       foreach($res as $row){
           $key = $row['bulan']."_".$row['tahun']."_".$row['types'];
           $result[$key]['valid']= $row['c'];
           
       }
       
       $result_show=array($res_data,$res_dataOKs,'trans'=>array() );
       foreach($result as $row){
           $result_show['array'][]=$row;
       }
       
       $param['end']=  microtime();
       $result_show['query']=$query;
       save_and_send_rest($code,$result_show,$param);
   }
   
   function last_report_get( ){
       $code=200;
       $params=array();
       $tables=array(  'balances','batchemails','emails','localapis','redirects','run_apis','save_emails' );
       log_info_table('save_email',false);
       log_info_table('batchemail',false);
        foreach($tables as $name){
            $result[$name]=array();
            
            for($tahun=date("Y");$tahun>=2017;$tahun--){
                { //for($i=1;$i<=12;$i++){
                    $bln= "";// sprintf("%02s",$i);
                    $tablename = "y_".$name.$tahun.$bln;
                    $sql = "select max(modified) last,count(id) total, tmp1 txt from ".$tablename." ";
                    $result[$name][$tahun.$bln]=dbFetchOne($sql);
                    $result['sql'][]=$sql;
                }
                
            }
            
        }
        
       save_and_send_rest($code,$result ,$param);
   }
   
}

/*
 * select date(created) tgl, hour(created) jam, count(*) c  from zbatch_email where 1
 group by date(created), hour(created) order by created asc;
 * 
select 'total perbulan pertipe', month(created) bulan,year(created) tahun,types,count(id) c from mujur_salma.mujur_flowlog group by month(created),year(created),types order by created asc
select 'total perbulan pertipe (OK)',month(created) bulan,year(created) tahun,types,count(id) c from mujur_salma.mujur_flowlog where status=1 group by month(created),year(created),types order by created asc;
 * 
| y_balances201708            |
| y_batchmails201708          |
| y_drivers201708             |
| y_emails201708              |
| y_localapis201708           |
| y_redirects201708           |
| y_run_apis201708            |
| y_savemails201708           |
 *  */
//API
/*
sum_trading = account-summary-trading
result: {"AccountID":"xxx","TotalProfit":"-3.51","TotalTransactions":"24","TotalOpennedTransactions":"22","TotalFloatingTransactions":"0","TotalClosedTransactions":"22","TotalOpennedVolumeTransaction":"23","TotalFloatingVolumeTransaction":"0","TotalClosedVolumeTransaction":"23","TotalVolume":"22","TotalCommission":"0","TotalAgentCommission":"0","TotalWithdrawal":"0","TotalDeposit":"100","ResponseCode":"0","ResponseText":"Success"}
*/

/*
get-margin
{"AccountID":"7896528","Balance":"0.000000","Credit":"0.000000","Equity":"0.000000","FreeMargin":"0.000000","ResponseCode":"0","ResponseText":"Get margin success"}
*/