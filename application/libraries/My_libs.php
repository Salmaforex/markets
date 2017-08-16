<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_libs {
public $param, $CI;
	function checkView($target,$stat='view'){
		//return true;
		if(!is_file("views/".$target.".php") && ($stat=='view'||$stat=='body') ){
			$txt="<?php 
/****
	views	: $target
	created	: ".date("d-m-Y H:i:s")."
	By		: CI3 Stock Controllers
****/
defined('BASEPATH') OR exit('No direct script access allowed');";
			if($stat=='view')
			  $txt.="\n?>\n
<div class='container'><div class='row'>\n
<!-- content Start-->\n\n<!-- content End-->\n
</div></div>";
			file_put_contents ("views/".$target.".php", $txt,LOCK_EX );
			logCreate('create file:'."views/".$target.".php");
		}else{}
		
		if(!is_file("assets/js/".$target ) && $stat=='js'  ){
			$txt="/****
	Javascript	: $target
	created	: ".date("d-m-Y H:i:s")."
	By		: CI3 Stock Controllers
****/";	
			file_put_contents ("assets/js/".$target , $txt,LOCK_EX );
			logCreate('create file:'."assets/js/".$target);
		}else{}
	}

	function params(){
	$CI=$this->CI;
	date_default_timezone_set('Asia/Jakarta');
		$this->param['today']=date('Y-m-d');		
		$this->param['footerJS']=array();
		$this->param['mainMenu']=$CI->menu->generate('main');
		
		$this->param['thisUrl']= $CI->uri->segment(1).'/'.$CI->uri->segment(2);
		$this->param['css']=array(			
			'cupertino/jquery-ui-1.10.3.custom.min.css',
			'jqgrid3.8/ui.jqgrid.css'
		);
		$this->param['js']=array(
			'jquery-1.9.min.js',	
			'bootstrap.js',
		);
		
		$this->param['footerJS']=array(			
			'jquery-ui-1.9.2.min.js',			
			'jqgrid3.8/i18n/grid.locale-en.js',
			'jqgrid3.8/jquery.jqGrid.js', 
		);
		return $this->param;
	}
//===========START==========
	public function __construct($params=array() )
    {
		$this->param=$params;
		$this->CI =& get_instance();
   // Do something with $params
		$this->CI->load->driver('stocks');
    }
}