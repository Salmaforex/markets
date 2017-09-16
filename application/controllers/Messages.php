<?php 
require_once APPPATH.'/libraries/Api_sms_class.php';
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
//require_once(APPPATH.'/libraries/SmtpApi.php');
class Messages extends CI_Controller {
    function index(){
        //redirect('welcome');
        $api= config_site('sms_api','forexConfig_new');
        $example_hp = config_site('sms_example_hp','forexConfig_new');
        $target ='{
"apikey":"your_api_key",
"callbackurl":"your_url_for_get_auto_update_status_sms",
"datapacket":
[
{"number":"6281xxx","message":"Message1","sendingdatetime":""},
{"number":"081xxx","message":"Message2","sendingdatetime":"2017-01-01 23:59:59"}
]
}';
        echo '<pre>'.$target;
        $arr=array(
            'apikey'=>$api,
            'callbakurl'=>site_url('messages/returns'),
            'datapacket'=>array()
        );
        
        $arr['datapacket'][]=array(
            "number"=>$example_hp,
            "message"=>"hello.. berikut adalah sms dari sistem",
            "sendingdatetime"=>date("Y-m-d H:i:s")
        );
        
        echo "\n".json_encode($arr);
       // echo 'hello';
        /*
         * {

    "apikey":"73ff6a21b550c6f601e988300103846a",
    "callbakurl":"http:\/\/advance.forex\/index.php\/messages\/returns",
    "datapacket":[
        {
            "number":"08568132429",
            "message":"hello.. berikut adalah sms dari sistem",
            "sendingdatetime":"2017-09-16 17:18:17"
        }
    ]

}
         */

        $sms = new sms_class_reguler_json();
        echo"\n";
        $sms->status();
/*                
        $sms->setIp($ipserver);
        $sms->setData($senddata);
        $responjson = $sms->send();
        header('Content-Type: application/json');
        echo $responjson;
*/
    }
    
    function returns(){
        $txtMessage = 'DEMO message |returns |';
        $post = $this->input->post();
        logCreate($txtMessage.'post:'.  json_encode($post));
        $get = $this->input->post();
        logCreate($txtMessage.'get:'.  json_encode($get));
        
    }
    
    function error404(){
        echo_r($_SERVER);
    }
    
    function __CONSTRUCT(){
	parent::__construct();

    }        
}