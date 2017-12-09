<?php

defined('BASEPATH') OR exit('No direct script access allowed');
//die(APPPATH);
require_once(APPPATH . 'core/MY_Partner.php');

class Profile extends MY_Partner { //MY_Controller

    public $param;
    public $folderUpload;

    function __CONSTRUCT() {
        parent::__construct();
    }

    public function index_0() {
        logCreate('cek login');
        $this->checkLogin();
        logCreate('cek login OK');
        $this->param['title'] = 'SECURE ACCOUNT | Dashboard';
        $this->param['content'] = array('welcome');

        $this->param['footerJS'][] = 'js/login.js';
        $this->showView('newbase_view');
    }

    public function detail() {
        $this->index();
    }

    public function index() {
        $this->checkLogin();
        $this->param['title'] = 'SECURE ACCOUNT | Profile';
        $this->param['content'] = array(
            'detail',
        );
        $this->param['footerJS'][] = 'js/login.js';
        $this->showView();
    }

    public function edit($warn = 0) {
        $this->checkLogin();
        if ($this->input->post('rand')) {
            $rand = $this->input->post('rand');
            $param = array(
                'type' => 'updateDetail',
                'data' => array(
                ),
                'recover' => true
            );
            $this->param['post'] = $post = $param['post'] = $this->input->post();
            $user_detail = $this->users_model->getDetail($post['email'], 'ud_email', true);
            echo_r($user_detail);
            foreach ($post['detail'] as $name => $values) {
                $user_detail[$name] = $values;
            }
            //echo_r($user_detail);exit;
            $params = array('ud_detail' => json_encode($user_detail));

            $this->users_model->update_detail($post['email'], $params);
            //========update detail forex
            $this->load->driver('advforex'); /* gunakan hanya bila diperlukan */
            $driver_core = 'advforex';
            $driver_name = 'update_detail';
            $func_name = 'execute';
            if (!method_exists($this->{$driver_core}->{$driver_name}, $func_name)) {
                $output = array('func.tion "' . $func_name . '" unable to declare');
                die(json_encode($output));
            } else {
                $row = $params = array($post['email']);
                $params = $this->{$driver_core}->{$driver_name}->{$func_name}($row);

                //echo_r($params);exit;
            }

            redirect(site_url('partner/edit') . "?rand=$rand");
            /*
              $param['username']= $this->param['detail']['username'];
              $param['post']['detail']= $this->param['detail']['detail'];

              $url=$this->forex->forexUrl('local');

              $param['data']=$this->convertData();

              $ar=$this->load->view('depan/data/updateDetail_data',$param,true);
              $result=json_decode($ar,1);
             */
            if (isset($result['status']) && (int) $result['status'] == 1) {
                redirect(site_url('partner/detail'));
            } else {
                redirect(site_url('partner/edit'));
            }
        } else {
            $this->param['title'] = 'EDIT SECURE ACCOUNT';
            $this->param['content'] = array(
                'detail_edit',
            );
            $this->param['footerJS'][] = 'js/login.js';
            $this->param['warning'] = $warn;
            $this->load->driver('advforex'); /* gunakan hanya bila diperlukan */
            $driver_core = 'advforex';
            $func_name = 'detail';
            //$param1=array('post0'=>$param);
            $param = array();
            $driver_name = 'user';
            /*
              $data=$this->{$driver_core}->{$driver_name}->{$func_name}($param);
              $result=array('status'=>true);
              $this->param['user_detail']=$data['user_detail'];
             */
            //echo_r($this->param);exit;
            $this->showView();
        }
    }

    public function editpassword() {
        $this->checkLogin();
        if ($this->input->post('rand')) {
            $post0 = $this->input->post();
            $userlogin = $this->param['userlogin'];
            //	echo '<pre>'.print_r($userlogin,1);exit();
            $data = array(
                'user' => $userlogin,
                'password' => $post0['main1']
            );
            $message = $this->load->view('email/emailPasswordChange_email', $data, true);
            //die($email);
            _send_email($to = $userlogin['email'], $subject = '[Salmamarkets] Cabinet Password', $message);
            //echo_r($post0);exit;
            $post = array(
                'password' => $post0['main1'],
                'email' => $userlogin['email']
            );
            $p = _localApi('users', 'update_password', $post);
            //echo_r($p);
            //echo 'not valid';
            redirect(site_url("partner/editPassword"));
            exit;
        }
        $this->param['title'] = 'Secure Account | PASSWORD Edit';
        $this->param['content'] = array(
            'passwordEdit', 'modal'
        );
        $this->param['footerJS'][] = 'js/login.js';
        $this->showView();
    }

    public function edit_master_password() {
        $this->checkLogin();
        if ($this->input->post('rand')) {
            $post0 = $this->input->post();
            $userlogin = $this->param['userlogin'];
            //	$notif=$CI->session->flashdata('notif');
            if ($post0['oldcode'] != $userlogin['users']['u_mastercode']) {
                $this->session->set_flashdata('notif', array('status' => false, 'msg' => 'old code not valid'));
                redirect(site_url('partner/edit_master_password') . "?p=" . $userlogin['users']['u_mastercode']);
            }
            if ($post0['code'] != $post0['code2']) {
                $this->session->set_flashdata('notif', array('status' => false, 'msg' => 'new password not valid'));
                redirect(site_url('partner/edit_master_password') . "?p=444");
            }

            //	echo '<pre>'.print_r($userlogin,1);exit();
            //	echo_r($post0);echo_r($userlogin);exit;
            $data = array(
                'user' => $userlogin,
                'password' => $post0['code']
            );
            $message = $this->load->view('email/emailMasterCodeChange_email', $data, true);
            //die($message);

            _send_email($to = $userlogin['email'], $subject = '[Salmamarkets] Master Code Change', $message);
            $post = array(
                'password' => $post0['code'],
                'email' => $userlogin['email']
            );
            $p = _localApi('users', 'update_master_password', $post);
            //echo_r($p);exit;
            $this->session->set_flashdata('notif', array('status' => true, 'msg' => 'Success'));
            redirect(site_url("partner/edit_master_password?success=" . date("Ymd")));
            exit;
        }
        $userlogin = $this->param['userlogin'];
        if (isset($userlogin['users']['u_mastercode']) && trim($userlogin['users']['u_mastercode']) != '') {
            $this->param['title'] = 'Secure Account | PASSWORD Edit';
            $this->param['content'] = array(
                'masterpassword_edit', 'modal'
            );
            $this->param['footerJS'][] = 'js/login.js';
            $this->showView();
        } else {
            $userlogin = $this->param['userlogin'];
            $this->users_model->random_mastercode($userlogin['email']);
            redirect(site_url("partner/edit_master_password?reload=" . date("Ymd")));
            exit;
        }
    }

    public function uploads($warn = 0) {
        $this->checkLogin();

        if ($this->input->post('rand')) {
            $post = $this->input->post();
            $session = $this->session->all_userdata();
            $rand = dbId();
            //		echo_r($_POST);echo_r($_FILES);exit;
            $files = $_FILES['doc'];
            //var_dump($files);die();
            if ($files['size'] > 550000) {
                $post['message'] = "document upload to big";
                $this->session->set_flashdata('login', $post);
                redirect(site_url('partner/uploads/' . $rand));
                exit();
            }
            if (isset($_FILES['profile_pic'])) {
                $files = $_FILES['profile_pic'];
                if ($files['size'] > 550000) {
                    $post['message'] = "profile picture upload to big (less than 500kb)";
                    $this->session->set_flashdata('login', $post);
                    redirect(site_url('partner/uploads/' . $rand));
                    exit();
                }
            }

            $user = $this->param['userlogin'];
            $rand = $user['users']['u_id'];

            $files = $_FILES['doc'];
            $file_data = array();
            if ($files['tmp_name'] != '') {
                $filename = "doc_" . url_title($session['username']) . "_" . $rand . ".tmp";
                copy($files['tmp_name'], $this->folderUpload . $filename);
                $url = $this->folderUpload . $filename;
                $file_data = array(
                    'udoc_status' => 0,
                    'udoc_upload' => $url,
                    'filetype' => $files['type']
                );
                logCreate('upload bukti dokumen');
            }

            if (isset($_FILES['profile_pic'])) {
                $files = $_FILES['profile_pic'];
                if ($files['tmp_name'] != '') {
                    $filename = "pp_" . url_title($session['username']) . "_" . $rand . ".tmp";
                    copy($files['tmp_name'], $this->folderUpload . $filename);
                    $url2 = $this->folderUpload . $filename;
                    $type2 = $files['type'];
                    $file_data['profile_pic'] = $url2;
                    $file_data['profile_type'] = $type2;
                    logCreate('upload pp');
                }
            }

            //$url,$files['type']
            //	echo_r($file_data);exit;
            $this->users_model->updateDocument($user['email'], $file_data);
            //echo_r($user);echo_r($post);echo_r($file_data);exit;
            //exit('file:'.$url);
            redirect(site_url('partner/profile') . "?msg=save_profile");
        }

        $this->param['title'] = 'UPLOAD DOCUMENT';
        $this->param['content'] = array(
            'detailUpload',
        );
        $this->param['footerJS'][] = 'js/login.js';
        $this->param['warning'] = $warn;
        $this->showView();
    }

    function show_upload($id = null) {
        $data = $this->users_model->document($userid, 'id');
        //echo'<pre>';var_dump($data);die();
        header('content-type:' . $data['type']);
        header('Content-Disposition: attachment; filename="' . url_title($data['udoc_email']) . '"');

        $txt = file_get_contents($data['udoc_upload']);
        echo $txt;
    }

    function show_profile($userid = null) {
        $data = $this->users_model->document($userid, 'id');
        //echo'<pre>';var_dump($data);die();
        header('content-type:' . $data['profile_type']);
        header('Content-Disposition: attachment; filename="' . url_title($data['udoc_email']) . '"');

        $txt = file_get_contents($data['profile_pic']);
        echo $txt;
    }

}
