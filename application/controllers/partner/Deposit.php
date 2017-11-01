<?php

defined('BASEPATH') OR exit('No direct script access allowed');
//die(APPPATH);
require_once(APPPATH . 'core/MY_Partner.php');

class Deposit extends MY_Partner { //MY_Controller

    public $param;
    public $folderUpload;

    function __CONSTRUCT() {
        parent::__construct();
    }

    function index($status = 'none') {
        $this->checkLogin();
        $this->param['content'] = array();
        $uDetail = $userlogin = $this->param['userlogin'];
        if (!isset($uDetail['bank']) || $uDetail['bank'] == '') {
            $notAllow = 1;
            $uDetail['bank'] = '';
        }

        if (!isset($uDetail['bank_norek']) || $uDetail['bank_norek'] == '') {
            $notAllow = 1;
            $uDetail['bank_norek'] = '';
        }

        if (isset($notAllow)) {
            //echo_r($uDetail);
            //exit;
            $url = site_url("partner/edit/warning") . "#frmLiveAccount3";
            //	$this->session->set_flashdata('notif', array('status' => false, 'msg' => 'Update nomor rekening!'));
            redirect($url, 1);
        }

        $email = $uDetail['email'];
        $res = $this->account->get_by_field($email, 'email');
        $this->param['account_list'] = $res;

        if ($status == 'done') {
            $info = $this->session->flashdata('info');
            if ($info == 1) {
                $this->param['done'] = 1; //$this->param['content'][]='done' ;
            }
        }

        if ($this->input->post('orderDeposit')) {

            $this->param['post0'] = $post0 = $this->input->post();

            $this->param['rate'] = $rate = $this->forex->rateNow('deposit', $post0['currency']);
            $this->param['post0']['order1'] = $post0['order1'] = $rate['value'] * $post0['orderDeposit'];
            $this->param['post0']['rate'] = $rate['value'];

            $dataDeposit = $post0;
            $dataDeposit['userlogin'] = $this->param['userlogin'];
            $dataDeposit['rate'] = $rate['value'];
            $dataDeposit['currency'] = $rate['code'];
            //	echo_r($this->param['userlogin']);exit;
            $userlogin = $this->param['userlogin'];
            //$email = $this->param['userlogin']['email'];
            $phone = $this->users_model->phone_by_email($email);
            //echo_r($phone);echo_r($this->param);die;
            $message = $this->load->view('email/email_deposit_member_view', $this->param, true);
            //die($message);
            _send_email($userlogin['email'], '[salmamarkets] Confirmation to Deposit', $message);

            $data = array('url' => 'Deposit',
                'parameter' => json_encode($post0),
                'error' => 2,
                'response' => "rate:" . json_encode($rate) . "\n" . json_encode($this->param['userlogin'])
            );

            dbInsert($this->forex->tableAPI, $data);

            $id_trans = $this->forex->flowInsert('deposit', $dataDeposit);
            //dbInsert('deposit', $data);
            if ($post0['submit'] == 'fasapay') {
                redirect(site_url('partner/deposit/fasapay/' . $id_trans), true);
            }

            //echo "<pre>" . print_r($post0, 1);
            //exit;

            $this->session->set_flashdata('info', $post0);
            redirect(site_url('partner/deposit/index/done?date=' . rand(100, 999)), true);
            exit();

        } else {
            $this->param['title'] = 'Secure Account | Deposit ';
            if ($status == 'done') {
                $this->param['show_done'] = 'deposit_done';
            }

            $this->param['content'][] = 'transaksi/deposit';
            if ($this->input->get('date') == '') {
                $url = site_url('partner/deposit') . "?date=" . date("YmdHis") . "#depositForm";
                redirect($url);
            }
            $this->param['button_fasapay'] = TRUE;
        }

        $this->param['footerJS'][] = 'js/login.js';
        $this->showView();
    }

    public function fasapay($id_trans) {
        $data = $this->forex->flow_data($id_trans);

        $deposit_num = $data['detail']['orderDeposit'];
        $u_type = isset($data['detail']['userlogin']['users']['u_type']) ? $data['detail']['userlogin']['users']['u_type'] : '00';
        $account = $data['accountid'];
        $email = $data['email'];
        $currency = $data['currency'];
        $form['target'] = ciConfig('fasapay_url');
        $param['fp_acc'] = ciConfig('fp_acc');
        $param['fp_comments'] = 'Deposit $' . $deposit_num;
        $form['fp_cart'] = array(
            array(
                'item' => 'Deposit',
                'price' => 1,
                'qty' => $deposit_num
            )
        );
        $param['fp_amnt'] = trim(sprintf("%10.2f", $deposit_num));
        $param['fp_currency'] = ciConfig('fp_currency');
        $param['fp_merchant_ref'] = 'DE' . sprintf("%02s-%011s", $u_type, $id_trans);

        $param['fp_success_url'] = site_url('partner/deposit/result/success/' . $id_trans);
        $param['fp_success_method'] = 'POST';
        $param['fp_fail_url'] = site_url('partner/deposit/result/failed/' . $id_trans);
        $param['fp_fail_method'] = 'POST';
        $param['fp_status_url'] = site_url('partner/deposit/status/' . $id_trans);
        $param['fp_status_method'] = 'POST';
        $param['fp_resend_callback'] = 3;
        $param['fp_item'] = "Deposit $" . $deposit_num . " to account_id {$account} ";
        //$param['fp_sci_link']=TRUE;
        //==============additional===============
        $param['sal_acc'] = $account;
        $param['sal_email'] = $email;
        $param['sal_currency'] = $currency;
        $param['sal_type'] = $data['types'];
        $param['fp_fee_mode'] = 'FiS';

        $form['params'] = $param;

        $this->load->view('form_auto_post', $form);

        //echo '<pre>';print_r($form);print_r($data);

    }

    function result($status = 'NONE', $id_trans) {
        //echo '<pre>stat=' . $status . "\tid:" . $id_trans;
        logCreate('deposit_result:' . $status);
        logCreate($this->input->post(), 'result:' . $status);
        $post = $this->input->post();
        //print_r($post);
        if ($status=='success'){
            /*
             * Array
              (
              [fp_paidto] => FP217906
              [fp_amnt] => 13.00
              [fp_currency] => USD
              [fp_store] =>
              [fp_merchant_ref] => DE10-01709184286
              [sal_acc] => 7000421
              [sal_email] => 07adjie@gmail.com
              [sal_currency] => IDR
              [sal_type] => deposit
              [fp_cart] => Array
              (
              [0] => Array
              (
              [item] => Deposit
              [price] => 1
              [qty] => 13
              )

              )

              [yt0] => Back to Merchant
              )
             */
            $data=array(
                'status'=>$status,
                $post['fp_paidto'],
                $post['fp_paidby'],
                $post['fp_fee_amnt'],
                $post['fp_fee_mode'],
                $post['fp_currency'],
                $post['fp_batchnumber'],
                $post['fp_total'],
                $post['fp_amnt'],
                $post['fp_merchant_ref'],
                $id_trans,
                $post['yt0']
            );
            
            log_info_table('fasapay', $data);
            unset($data['status']);
            log_info_table('fasapay_success',$data);
            $info = 'Fasapay Payment Has Success number:'.$post['fp_batchnumber'];
             $aTrans = explode("-", $post['fp_merchant_ref']);
            $iTrans = $aTrans[1];
            
            $this->forex->flow_data_update($iTrans, 1, $info);
            
            //exit;
            $info = array(
                'status' => TRUE,
                'message' => 'Please Deposit to Our Bank Account and Contact CS for further Information'
            );

            $this->session->set_flashdata('messages', $info);
            js_goto(site_url('partner/deposit/index/fasapay_success/?date='.date("YmdHis").'&#info_message'), true);
            
        }
        
        if ($status == 'failed') {
            /* stat=failed	id:1709184286Array
              (
              [fp_paidto] => FP217906
              [fp_amnt] => 13.00
              [fp_currency] => USD
              [fp_store] =>
              [fp_merchant_ref] => DE10-01709184286
              [sal_acc] => 7000421
              [sal_email] => 07adjie@gmail.com
              [sal_currency] => IDR
              [sal_type] => deposit
              [fp_cart] => Array
              (
              [0] => Array
              (
              [item] => Deposit
              [price] => 1
              [qty] => 13
              )

              )

              [yt0] => Back to Merchant
              )
             */
            $aTrans = explode("-", $post['fp_merchant_ref']);
            $iTrans = $aTrans[1];
            //$data = $this->forex->flow_data($id_trans);
            //print_r($data);
            $data=array(
                'status'=>$status,
                $post['fp_paidto'],
                $post['fp_amnt'],
                $post['fp_merchant_ref'],
                $iTrans,
                $post['yt0']
            );
            
            log_info_table('fasapay', $data);
            unset($data['status']);
            log_info_table('fasapay_failed',$data);
            
            $info = 'Failed Fasapay Payment';
            $this->forex->flow_data_update($iTrans, false, $info);
            $data = $this->forex->flow_data($id_trans);
            //print_r($data);
            //exit;
            $info = array(
                'status' => FALSE,
                'message' => 'Please Deposit to Our Bank Account and Contact CS for further Information'
            );

            $this->session->set_flashdata('messages', $info);
            js_goto(site_url('partner/deposit/index/failed/?date='.date("YmdHis").'&#info_message'), true);
        }
    }

    function status($id_trans) {
        echo '<pre>status' . $id_trans;
        $post = $this->input->post();
        print_r($post);
        logCreate('deposit_status:' . $id_trans);
        logCreate($this->input->post(), 'trans:' . $id_trans);
    }

}
