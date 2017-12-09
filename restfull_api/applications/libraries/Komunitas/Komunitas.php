<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Komunitas extends CI_Driver_Library {
    public $driver_name;
    public $valid_drivers;
    public $CI;
/***
Daftar Fungsi Yang Tersedia :
*	__construct()
***/
    function __construct(){
        $this->CI =$CI  =& get_instance();
        /*
        $config_file='driver_gw';
        
        $CI->config->load($config_file);
        $valid_drivers= $CI->config->item('drivers_'.$driver_core);
         * 
         */
        $driver_core='komunitas';
        $valid_drivers = config_site('drivers_'.$driver_core);
        $this->valid_drivers = $valid_drivers;
    }
//====================================DEMO================
    function demo($params){
//=============default================
        $debug=array('time'=>array(microtime()),'params'=>$params);
        //debug berisi time eksekusi dan param
        //-------------------//
        $pesan="Ini adalah Pesan";
        $return_code =200; //ignore saja.. saya perlu ini untuk API 
        
//-------------show debug
        $show_debug = isset($params['debug'])&&$params['debug']!=false?true:false;
    
        unset( $params['debug']);
        
        $return = array();
        $return[]="untuk menjalankan fungsi yang di inginkan.. diketik dalam fungsi di dalamnya";
        $return[]="gunakan perintah driver_run()";
        
        $debug['time'][]=  microtime();
        
        return driver_return($return_code,  $pesan, $return, $debug, $show_debug );
    }
}