<?php

defined('BASEPATH') OR exit('No direct script access allowed');
//die(APPPATH);
require_once(APPPATH . 'core/MY_Partner.php');

class Dashboard extends MY_Partner { //MY_Controller

    public $param;
    public $folderUpload;

    function __CONSTRUCT() {
        parent::__construct();
    }

     public function index() {
        logCreate('cek login');
        $this->checkLogin();
        logCreate('cek login OK');
        $this->param['title'] = 'SECURE ACCOUNT | Dashboard';
        $this->param['content'] = array('welcome');

        $this->param['footerJS'][] = 'js/login.js';
        $this->showView('newbase_view');
    }
    
    function reload() {
        redirect('partner');
    }
    
    public function logout() {
        foreach ($_SESSION as $name => $val) {
            $_SESSION[$name] = '';
            $_SESSION['password'] = '';
        }
        redirect(site_url("login"));
    }
    
    function promotion($type = null) {
        $this->checkLogin();
        $params = array('promotion', $type, $this->param['userlogin']);
        $params['debug'] = true;
        //$result=driver_run_action('salma', 'guest', $params);

        $options = array(
            'parent' => 'salma',
            'sub' => 'partner',
            'params' => $params,
            'debug' => false,
                //    'mode'=>'api'
        );

        $result = $this->basic->driver_action($options);
        //driver_run_action
        //libraries/salma/drivers/salma_guest_home
        //echo_r($result);exit;
        if (isset($result['data'])) {
            $this->parse_params($result['data']);
        }

        $this->param['outerJS'][] = $this->param['url_js_partner']; //site_url('partner/js/partner_promotion').'?account_id='.$account_id.'&name='.$name ;
        $this->param['outerJS'][] = site_url('partner/js/ajax_native');
        $this->param['footerJS'][] = 'js001/jquery.dataTables.min.js';
        $this->param['footerJS'][] = 'js001/jquery.zeroclipboard.min.js';

        $this->showView();
    }
    function promotion_html() {
        $post = $this->input->post();
        $url = $post['url'];
        $ar = explode('/', $post['gambar']);
        $num = count($ar);
//		die( json_encode($post));
        $config['set'] = $set = $ar[$num - 2];
        $config['dimension'] = $dimension = str_replace('.png', '', $ar[$num - 1]);

        $start = strlen(base_url());
        $config[] = array($start, base_url());
        $config['image_file'] = $image_file = substr($post['gambar'], $start, strlen($post['gambar']) - $start);

        $imagedata = file_get_contents($image_file);
        // alternatively specify an URL, if PHP settings allow
        $base64 = base64_encode($imagedata);
        $src = $post['gambar'];
        //'data: '.mime_content_type($image_file).';base64,'.$base64;
        // Echo out a sample image


        if (substr($dimension, 0, 3) == 'set') {
            $dimension = substr($dimension, 5, strlen($dimension) - 5);
        }

        $open = 'media/banners/' . $set . '/' . $dimension . '/index.html';
        $txt = file_get_contents($open);
        $txt = str_replace('https://www.salmaforex.com/', $url, $txt);
        $txt = str_replace('source="', 'source="' . base_url('media/banners/' . $set . '/' . $dimension) . '/', $txt);
        $arr = array('html2' => '<div id="salmamarkets_banner_' . $set . '_' . $dimension . '">' . $txt . '</div>');
        $arr['input'] = $post;
        $arr['config'] = $config;

        $style = 'cursor: pointer;margin:5px;padding:5px';
        $arr['html'] = '<span> <img style="' . $style . '" onclick="window.open(\'' . $url . '\')" src="' . $src . '"></span> ';


        echo json_encode($arr);
        //print_r($post);
        //print_r($config);
    }

    public function history($status = 'none') {
        $this->checkLogin();
        $session = $this->param['session'];
        $res = _localApi('account', 'lists', array($session['username']));
        //echo_r($res);
        $account = isset($res['data']) ? $res['data'] : array();
        //echo_r($account);
        $history = array();
        foreach ($account as $row) {
            $data = $this->forex->flow_member($row['accountid']);
            if ($data != false) {
                foreach ($data['data'] as $row) {
                    $history[] = $row;
                }
            }
        }
        //echo_r($history);exit;
        $this->param['history'] = $history;
        $this->param['content'] = array();
        $this->param['title'] = 'OPEN SECURE HISTORY';
        $this->param['content'][] = 'history';
        $this->param['content'][] = 'modal';
        $this->param['footerJS'][] = 'js/login.js';
        $this->param['footerJS'][] = 'js/jquery.dataTables.min.js';
        $this->param['footerJS'][] = 'js/api.js';
        $this->param['fileCss']['dataTable'] = 'css/jquery.dataTables.min.css';
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
