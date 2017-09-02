<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (   function_exists('logFile')){ logFile('model','password_model.php','model'); };
class Password_model extends CI_Model {
public $table='mujur_password';
	function random($num=1){
		$sql="select password from `{$this->table}` order by rand() limit {$num}";
		return dbFetch($sql);
	}
	
	
}