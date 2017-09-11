<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Salma extends CI_Driver_Library {
    public $driver_name;
    public $valid_drivers;
    public $CI;
/***
Daftar Fungsi Yang Tersedia :
*	__construct()
***/
    function __construct(){
        $this->CI =$CI  =& get_instance();
        $config_file='driver_gw';
        $driver_core='salma';
        $CI->config->load($config_file);
        $valid_drivers= $CI->config->item('drivers_'.$driver_core);
        $this->valid_drivers = $valid_drivers;
    }
    
    function guest($params){
        //=============default================
        $debug=array('time'=>array(microtime()),'params'=>$params);
        //debug berisi time eksekusi dan param
        //-------------------//
        $pesan="Ini adalah Pesan";
        $return_code =200; //ignore saja.. saya perlu ini untuk API 
        
//-------------show debug
        $show_debug = isset($params['debug'])&&$params['debug']!=false?true:false;
    
        unset( $params['debug']);
        
        $debug[]="untuk menjalankan fungsi yang di inginkan.. diketik dalam fungsi di dalamnya";
        $debug[]="gunakan perintah driver_run()";
        
        $new_params = $this->clean_first_params($params);
        $function_run = isset($params['function'])?$params['function']:'executed';
        
        $return=driver_run('salma','guest_'.$params[0], $function_run, $new_params );
        
        $debug['time'][]=  microtime();
        //============NAMBAH DEFAULT CSS,JS
        $return['fileCss']=array(	
			'css001/style_salmamarkets.css',
			'css001/bootstrap.css',
		);
        $return['fileJs']=array(
                'js/jquery-1.7.min.js',
        );

        $return['shortlink']=base_url();
        $return['footerJS']=array(	 
                'js/bootstrap.min.js',
                'js/formValidation.min.js',
                'js001/scripts.js'
        );
                
        
        return driver_return($return_code,  $pesan, $return, $debug, $show_debug );
    }
         
    function partner($params){
        //=============default================
        $debug=array('time'=>array(microtime()),'params'=>$params);
        //debug berisi time eksekusi dan param
        //-------------------//
        $pesan="Ini adalah Pesan";
        $return_code =200; //ignore saja.. saya perlu ini untuk API 
        
//-------------show debug
        $show_debug = isset($params['debug'])&&$params['debug']!=false?true:false;
    
        unset( $params['debug']);
        
        $debug[]="untuk menjalankan fungsi yang di inginkan.. diketik dalam fungsi di dalamnya";
        $debug[]="gunakan perintah driver_run()";
        
        $new_params = $this->clean_first_params($params);
        $function_run = isset($params['function'])?$params['function']:'executed';
        
        $return=driver_run('salma','partner_'.$params[0], $function_run, $new_params );
        
        $debug['time'][]=  microtime();
        //============NAMBAH DEFAULT CSS,JS

        return driver_return($return_code,  $pesan, $return, $debug, $show_debug );
    }  
    function login($params){
        if(isset($_SERVER['HTTP_REFERER'])){
            logCreate('login| member| from:'.$_SERVER['HTTP_REFERER']);
            
        }
        
        //=============default================
        $debug=array('time'=>array(microtime()),'params'=>$params);
        //debug berisi time eksekusi dan param
        //-------------------//
        $pesan="Ini adalah Pesan";
        $return_code =200; //ignore saja.. saya perlu ini untuk API 
        
//-------------show debug
        $show_debug = isset($params['debug'])&&$params['debug']!=false?true:false;
    
        unset( $params['debug']);
//=============RUN================
        $new_params = $this->clean_first_params($params);
        $function_run = isset($params['function'])?$params['function']:'executed';
        
        $return=driver_run('salma','login_'.$params[0], $function_run, $new_params );
        $return['login_page']=true;
        
        $debug['time'][]=  microtime();
        
        return driver_return($return_code,  $pesan, $return, $debug, $show_debug );
    }
    
    private function clean_first_params($params) {
        $result=array();
        $n=0;
        foreach($params as $name=>$value)
            if($name!=0){
                $result[$n]=$value;
                $n++;
            }

        
        return $result;
    }
}