<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//require_once(APPPATH.'/libraries/SmtpApi.php');
class Test extends CI_Controller {

    function __CONSTRUCT() {
        parent::__construct();
        $list_api = array(
            'user_detail' => array('users/detail', 'melihat detail ', array('email' => 'gundambison@gmail.com')),
            'user_exist' => array('users/exist', 'cek user ', array('email' => 'gundambison@gmail.com')),
            'user_exist2' => array('users/exist', 'cek user (tidak ditemukan) ', array('email' => 'gundambisonxxx@gmail.com')),
            'login1' => array('users/login', 'login OK ', array('email' => 'Ade.yogi.putra@gmail.com', 'password' => '1231234')),
            'login2' => array('users/login', 'login Error (tidak ditemukan) ', array('email' => 'gundambisonxxx@gmail.com', 'password' => 'gundam1981')),
            'login3' => array('users/login', 'login Error (password salah) ', array('email' => 'Ade.yogi.putra@gmail.com', 'password' => 'gundam1981')),
            'user_detail2' => array('users/detail', 'detail user (tidak ditemukan) ', array('email' => 'gundambisonxxx@gmail.com')),
            'user_balance' => array('account/balance', 'balance ', array('accountid' => 9999)),
            'setting_duit' => array('settings/currency', 'Data mata Uang (aktif)', array('action' => 'show_some')),
            'setting_duit2' => array('settings/currency', 'Data mata Uang (semua)', array('action' => 'show_all')),
            'country' => array('settings/country', 'Negara (semua)', array('action' => 'show_all')),
            'email' => array('email/batch', 'kirim email',
                array(
                    'from' => 'xx',
                    'to' => 'yy',
                    'title' => 'zzz',
                    'message' => 'xxxx ' . date("H:i:s"),
                    'type' => 'demo'
                )
            ),
            'email_list' => array('email/list_all', 'list email aktif', array('active' => TRUE)),
            'email_list2' => array('email/list_all', 'list email', array('active2' => TRUE)),
            'email_send' => array('email/list_all', 'list email aktif lalu kirim', array('active2' => TRUE))
        );
        $this->list_api = $list_api;
    }

    private $list_api;

    function index() {

        die($this->list_my_api());
    }

    private function list_my_api() {
        $str = '<ol>';
        foreach ($this->list_api as $key => $row) {
            $str.="\n<li>" . anchor(site_url('test/run/' . $key), $row[1]) . "</li>\n";
        }
        $str.='</ol>';
        return $str;
    }

    //==================
    function run($name = '') {
        $api = $this->list_api[$name];
        //print_r($api);
        $other_run = array(
            'email_send',
        );
        if (in_array($name, $other_run, $name)) {
            $this->$name();
        } else {
            $id_random = (string) dbId('random');
            $url = $api[0];
            $url.="?r=" . $id_random;
            $param = $api[2];
            $param['debug'] = 1;
            $result = data_api($url, $param);
            echo '<pre>' . print_r($result, 1) . '</pre>';
        }
        die($this->list_my_api());
    }

    function cek_data() {
        $url = 'http://data.salmaforex.com/send_email.php';
        $result = _runApi($url);
        echo '<pre>' . print_r($result, 1);
    }

    function email_send() {
        $url = 'email/list_all';
        $param = array('active' => TRUE);
        $result = data_api($url, $param);
        if (isset($result['email'])) {
            $email = $result['email'];
            foreach ($email as $row) {
                $id = $row['id'];
                echo "<br/> send $id";
                $url = 'email/send';
                $param = array('id' => $id);
                $result_send = data_api($url, $param);
                echo '<pre>' . print_r($result_send, 1) . '</pre>';
            }
        } else {
            print_r($result);
        }
        echo "<hr>DONE";
    }

}
