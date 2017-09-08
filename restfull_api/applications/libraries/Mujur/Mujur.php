<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Mujur extends CI_Driver_Library {
	public $driver_name;
    public $valid_drivers;
    public $CI;
/***
Daftar Fungsi Yang Tersedia :
*	__construct()
***/
	function __construct(){
        $this->CI =$CI  =& get_instance();
        $driver_core='mujur';
        $valid_drivers = config_site('drivers_'.$driver_core);
        $this->valid_drivers = $valid_drivers;
        /*
		$config_file='driver_gw';
		$driver_core='mujur';
		$CI->config->load($config_file);
		$valid_drivers= $CI->config->item('drivers_'.$driver_core);
		$this->valid_drivers = $valid_drivers;
         * 
         */
    }
}