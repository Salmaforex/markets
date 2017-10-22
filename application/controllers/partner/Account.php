<?php

defined('BASEPATH') OR exit('No direct script access allowed');
//die(APPPATH);
require_once(APPPATH . 'core/MY_Partner.php');

class Account extends MY_Partner { //MY_Controller

    public $param;
    public $folderUpload;

    function __CONSTRUCT() {
        parent::__construct();
        
    }

         public function account_list($accountid = 0) {
        if ($accountid != 'all') {
            js_goto(site_url('partner/account_list/all'));
            exit;
        }
        $this->checkLogin();
//======BALANCE
        $this->param['accountid'] = $accountid;
        $this->param['title'] = 'SECURE ACCOUNT | Profile';
        $this->param['content'] = array(
            'account_list',
        );
        $this->param['footerJS'][] = 'js/login.js';
        $this->showView();
    }

    public function account_id($accountid = 0) {
        $this->checkLogin();
        $get = $this->input->get();
        
//======BALANCE
        $array = array('accountid' => $accountid);
        $account_detail = $this->account->detail($accountid, 'accountid');
        $array['balance'] = isset($account_detail['balance']) ? $account_detail['balance'] : array();
        $array['summary'] = isset($account_detail['summary']) ? $account_detail['summary'] : array();
        $this->param['userlogin']['accountid'] = $accountid;
        //$array['balance']
        $balance0 = isset($this->param['userlogin']['balance']) ? $this->param['userlogin']['balance'] : 0;
        $session = $this->param['session'];

        if ($session['balance'] != $array['balance']) {
//		$session=$this->param['session'];echo_r($session);echo_r($array);exit;
            //if( $balance0 != $array['balance'] ||$this->param['userlogin']['summary'] != $array['summary']  ){
            //	$this->param['userlogin']['balance']=$array['balance'];
            $this->session->set_userdata($array);
            if ($this->input->get('d')) {
                redirect('partner');
                echo_r($session);
                echo_r($array);
                exit;
                die('error');
            }

            if (!isset($get['act'])) {
                redirect('partner/account_id/' . $accountid . '?act=save_balance&d=' . date("his"));
            } else {
                
            }
        } else {
            $session = $this->param['session'];
            //echo_r($session);echo_r($array);exit;
        }

        $this->param['accountid'] = $accountid;
        $this->param['title'] = 'SECURE ACCOUNT | Profile';
        $this->param['content'] = array(
            'account_detail',
        );
        $this->param['footerJS'][] = 'js/login.js';
        $this->showView();
    }

    function account_password($accountid) {
        $this->checkLogin();
        if ($this->input->post('rand')) {

            $post = $this->input->post();
            $account = $this->account->gets($post['accountid'], 'accountid');
            $email = $account['email'];
            //echo_r($account);echo_r($post);exit;
            $param2 = array(
                'username' => $post['accountid'],
                //$this->param['detail']['username'],
                'masterpassword' => $post['trading1'],
                'investorpassword' => $post['investor1'],
                'email' => $email,
                'userlogin' => $this->param['userlogin']
            );

            saveTableLog('member', 'change pass', $param2);
            $param2['emailAdmin'] = array(); //$this->forex->emailAdmin;
            //email dahulu
            $message = $this->load->view('email/account_password_change_view', $param2, true);
            //	die($message);
            _send_email($to = $email, $subject = '[Salmamarkets] Account Password Change (' . $post['accountid'] . ')', $message);
            //	$this->load->view('depan/email/account_password_change_view',$param2);
            //emailPasswordChange_view
            //========update detail forex
            $this->account->update_password($post['accountid'], $post['trading1'], $post['investor1']);
            $this->load->driver('advforex'); /* gunakan hanya bila diperlukan */
            $driver_core = 'advforex';
            $driver_name = 'account_update_password';
            $func_name = 'execute';
            if (!method_exists($this->{$driver_core}->{$driver_name}, $func_name)) {
                $output = array('func.tion "' . $func_name . '" unable to declare');
                die(json_encode($output));
            } else {
                $row = $params = array($post['accountid'], $post['trading1'], $post['investor1']);
                $params = $this->{$driver_core}->{$driver_name}->{$func_name}($row);

                //echo_r($params);exit;
            }
            redirect(site_url('member'));
        }
        $this->param['accountid'] = $accountid;
        $this->param['title'] = 'SECURE ACCOUNT | Profile';
        $this->param['content'] = array(
            'account_password',
        );
        $this->param['footerJS'][] = 'js/login.js';
        $this->showView();
    }
    
   function list_member($type = null) {
        $this->param['title'] = 'SECURE ACCOUNT | List Member';
        $contents = array('partner_members');

        $this->checkLogin();
        if (!isset($contents))
            redirect('partner/detail');
        //	$email = $this->param['userlogin']['email'];
        //	$this->param['accounts']=  $this->account->gets($email );
        $this->param['content'] = $contents;
        $this->param['footerJS'][] = 'js/partner_listmember.js';
        $this->showView();
    }

}
