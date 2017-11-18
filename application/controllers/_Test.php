<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//require_once(APPPATH.'/libraries/SmtpApi.php');
class Test extends CI_Controller {
    function __CONSTRUCT() {
        parent::__construct();
		$list_api=array(
			'user_detail'=>array( 'users/detail','melihat detail ', array('email'=>'gundambison@gmail.com' )),
		);
		$this->list_api=$list_api;
    }
	
    private $list_api;

    function index() {

        die($this->list_my_api());
	}
	
	private function list_my_api(){
		$str='<ol>';
		foreach($this->list_api as $key=>$row){
			$str.="\n<li>".anchor(site_url('test/run/'.$key), $row[1] )."</li>\n";	
		}
		$str.='</ol>';
		return $str;
	}
	
	//==================
	function run($name=''){
		$api = $this->list_api[$name];
		//print_r($api);
		$id_random = (string) dbId('random');
		$url = $api[0];
        $url.="?r=" . $id_random;
		$result = data_api($url, $api[2]);
		echo '<pre>'.print_r($result,1).'</pre>';
		die($this->list_my_api());
	}
}