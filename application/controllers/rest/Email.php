<?php

require_once APPPATH . '/libraries/REST_Controller.php';
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Email extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('api');
        $this->load->database();
        $this->load->model('account_model');
        $this->load->model('password_model');
        $this->load->model('forex_model');
        header('Access-Control-Allow-Origin: *');
    }
  
    function index_post(){
        $code = 200;
        $param = array('post' => $this->post());
        $data=$this->forex_model->emailData(10);
        $result['email']=$data;
        save_and_send_rest($code, $result, $param);
    }
    
    function index_get(){
        $this->index_post();
    }
    
    function hide_post(){
        $code = 200;
        $post=$this->post();
        $id=$post['id'];
        $param = array('post' => $this->post());
        $row= $this->forex_model->emailHide( $id );
        
        $to = $row['to'];
        $subject = $row['subject'];
        
        log_info_table('email', array($to, $subject));
        $result['email']=$row;
    //    $result[]=$param;
    //    $result[]=$id;
        save_and_send_rest($code, $result, $param);
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