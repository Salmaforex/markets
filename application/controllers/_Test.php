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
            'user_balance' => array('account/balance', 'balance ', array('accountid' => 9999))
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
        $id_random = (string) dbId('random');
        $url = $api[0];
        $url.="?r=" . $id_random;
        $param = $api[2];
        $param['debug'] = 1;
        $result = data_api($url, $param);
        echo '<pre>' . print_r($result, 1) . '</pre>';
        die($this->list_my_api());
    }
    
    function cek_data(){
        $url='http://data.salmaforex.com/send_email.php';
        $result = _runApi($url);
        echo '<pre>'.print_r($result,1);
    }

}
