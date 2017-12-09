<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once(APPPATH . 'core/MY_Partner.php');

/**
 * Description of Widtdrawal
 *
 * @author gunawan
 */
class Widtdrawal extends MY_Partner {

    public $param;
    public $folderUpload;

    //put your code here
    public function index($status44 = null) {
        $this->checkLogin();
        $notAllow = true;
        $detail = $uDetail = $userlogin = $this->param['userlogin'];
        if ($status44 == null && isset($detail['document']['udoc_status'])) {
            if ($detail['document']['udoc_status'] == 1) {
                unset($notAllow);
            } else {
                //			echo_r($detail);exit;
                //$this->session->set_flashdata('notif', array('status' => false, 'msg' => 'Supporting Document still reviewed'));
                $info = array(
                    'status' => FALSE,
                    'message' => 'Supporting Document still reviewed'
                );

                $this->session->set_flashdata('messages', $info);
                
                redirect('partner/widtdrawal/index/stop');
            }
        } else {
            if ($detail['document']['udoc_status'] == 1) {
                //clear
            } elseif ($detail['document']['udoc_status'] < 1) {
                $this->session->set_flashdata('notif', array('status' => false,
                    'msg' => 'Reviewed in progress!'));
                redirect('partner/uploads');
            } else {
                //	echo_r($detail);die('----upload--');
                /*$this->session->set_flashdata('notif', array('status' => false,
                    'msg' => 'Please Upload Supporting Document lists!'));
                 * 
                 */
                $info = array(
                    'status' => FALSE,
                    'message' => 'Please Upload Supporting Document lists!'
                );

                $this->session->set_flashdata('messages', $info);
                redirect('partner/uploads');
            }
        }

        $this->withdrawal_page($status44);
 
    }

    function withdrawal($status = null) {
        $this->widtdrawal($status);
    }

    private function withdrawal_page($status = null) {
        $this->checkLogin();
        $uDetail = $this->param['userlogin'];
        if (!isset($uDetail['bank']) || $uDetail['bank'] == '') {
            $notAllow = 1;
            $uDetail['bank'] = '';
        }

        if (!isset($uDetail['bank_norek']) || $uDetail['bank_norek'] == '') {
            $notAllow = 1;
            $uDetail['bank_norek'] = '';
        }

        if (isset($notAllow)) {
            //	echo_r($uDetail);exit;
            redirect(site_url("partner/edit/warning"), 1);
        }

        $this->param['title'] = 'Secure Account | Withdrawal ';
        $this->param['content'] = array();
        $email = $uDetail['email'];
        $res = $this->account->get_by_field($email, 'email');
        $this->param['account_list'] = $res;
        if ($status == 'done') {
            $info = $this->session->flashdata('info');
            if ($info == 1) {
                //$this->param['content'][]='done' ;
                $this->param['done'] = 1;
            }
        }

        if ($this->input->post('orderWidtdrawal')) {
            $this->param['post0'] = $post0 = $this->input->post();
            if ($uDetail['users']['u_mastercode'] != $post0['mastercode']) {
                logCreate('partner |withdrawal_page master code tidak valid', 'error');
                $notif = array('status' => false, 'msg' => 'Master Code not Valid');
                //$notif[]=$uDetail['users']['u_mastercode'];
                $this->session->set_flashdata('notif', $notif);
                //echo_r($uDetail);print_r($notif);exit;
                $info = array(
                    'status' => FALSE,
                    'message' => 'Master Code Not Valid'
                );

                $this->session->set_flashdata('messages', $info);

                redirect('partner/widtdrawal/index/failed');
            }



            $this->param['rate'] = $rate = $this->forex->rateNow('widtdrawal', $post0['currency']);
            $this->param['post0']['order1'] = $post0['order1'] = $rate['value'] * $post0['orderWidtdrawal'];
            $this->param['post0']['rate'] = $rate['value'];

            $dataWD = $post0;
            $dataWD['userlogin'] = $this->param['userlogin'];
            $dataWD['rate'] = $rate['value'];
            $dataWD['currency'] = $rate['code'];
            $dataWD['symbol'] = $rate['symbol'];

            $userlogin = $this->param['userlogin'];

            $message = $this->load->view('email/email_withdrawal_member_view', $this->param, true);
            //echo $message;exit;
            _send_email($userlogin['email'], '[salmamarkets] Confirmation to Withdrawal', $message);

            $data = array('url' => 'widtdrawal',
                'parameter' => json_encode($post0),
                'error' => 2,
                'response' => "rate:" . json_encode($rate) . "\n" . json_encode($this->param['userlogin'])
            );

            dbInsert($this->forex->tableAPI, $data);

            $this->forex->flowInsert('widtdrawal', $dataWD); //dbInsert('deposit', $data);

            $this->session->set_flashdata('info', $dataWD);

            $notif = array('status' => true, 'msg' => 'Your Withdrawal Order Success. Please Check your Email');
            //$this->session->set_flashdata('notif', $notif);
            $info = array(
                    'status' => TRUE,
                    'message' => 'Your Withdrawal Order Success. Please Check your Email'
                );

                $this->session->set_flashdata('messages', $info);
            redirect(site_url('partner/widtdrawal/index/done/' . rand(2100, 8999)), true);
            exit();


 
        } else {
            $this->param['content'][] = 'transaksi/widthdrawal';
            if ($status == 'done')
                $this->param['show_done'] = 'widtdrawal_done';
        }

        $this->param['footerJS'][] = 'js/login.js';
        $this->showView();
    }

    function __CONSTRUCT() {
        parent::__construct();
    }

}
