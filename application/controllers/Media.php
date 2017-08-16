<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Media extends MY_Controller {
	function images($name){
		$img=file_get_contents('media/'.$name);
		echo $img;
	}
	function dirs_img($name1,$name2,$name3){
		$img=file_get_contents('media/'.$name2.'/'.$name3);
		echo $img;
	}

}