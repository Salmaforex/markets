<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ujian_model extends CI_Model {
public $table='z_temp_local';
public $tables=array();
private $db_log;
	function random($num=1){
		$sql="select password from `{$this->table}` order by rand() limit {$num}";
		return dbFetch($sql);
	}
		
        public function __construct()
        {
            parent::__construct();
        }
}