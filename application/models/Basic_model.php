<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Basic_model
 *
 * @author gunawan
 */
class Basic_model extends CI_Model {
public $table='z_temp_local';
public $tables=array();
private $db_log;

    function driver_run($options=array()){
        /*
        $options =array(
            'parent'=>'mujur',
            'sub'=>'account',
            'function'=>'start',
            'params'=>array(),
        //    'debug'=>false,
        //    'mode'=>'api'
        );
        */
    //===========CORE FUNCTION==================
        $driver_core = isset($options['parent'])?$options['parent']:false;
        $driver_name = isset($options['sub'])?$options['sub']:false;
        $func_name = isset($options['function'])?$options['function']:'executed'; 
        $params = isset($options['params'])?$options['params']:array();
        
    //==============OTHER
        $show_debug = isset($options['debug'])?$options['debug']:false;
        $mode = isset($options['mode'])?$options['mode']:false; //api
        
        
        //$driver_core, $driver_name, $func_name='executed', $params=array()
        
    //==============Debug
        $start = microtime();
        $debug=array($start,'options'=>$options);
        
        //=== Bentuknya seperti helper driver_run
        $this->load->driver($driver_core);
        $result=$CI->{$driver_core}->{$driver_name}->{$func_name}($params);
        
        if($show_debug){
            $debug['driver'] = isset($result['debug'])?$result['debug']:array();
        }
        else{
            unset($result['debug']);
        }
        
        if($mode == 'api'){
            $result = $this->driver_api_return($result);
            $debug['mode']=array('api',  microtime());
        }
        
        if($show_debug){
            $result['debug']=$debug;
        }
        
        logCreate('basic_model| driver_run|'.json_encode($debug),"model");
        
        return $result;
    }
    
    function driver_action($options=array()){
        /*
        $options =array(
            'parent'=>'mujur',
            'sub'=>'account',
            'params'=>array(),
        //    'debug'=>false,
        //    'mode'=>'api'
        );
        */
    //===========CORE FUNCTION==================
        $driver_core = isset($options['parent'])?$options['parent']:false;
        $driver_name = isset($options['sub'])?$options['sub']:false;
        $params = isset($options['params'])?$options['params']:array();
        
    //==============OTHER
        $show_debug = isset($options['debug'])?$options['debug']:false;
        $mode = isset($options['mode'])?$options['mode']:false; //api
        //$driver_core, $driver_name, $func_name='executed', $params=array()
        
    //==============Debug
        $start = microtime();
        $debug=array($start,'options'=>$options);
        
    //==============Executed
        $this->load->driver($driver_core);
        $result=$this->{$driver_core}->{$driver_name}($params);
        
        if($show_debug){
            $debug['driver'] = isset($result['debug'])?$result['debug']:array();
        }
        else{
            unset($result['debug']);
        }
        
        if($mode == 'api'){
            $result = $this->driver_api_return($result);
            $debug['mode']=array('api',  microtime());
        }
        
        logCreate('basic_model| driver_action|'.json_encode($debug),"model");
        
        if($show_debug){
            $result['debug']=$debug;
        }
        
        
        return $result;
        
    }
    
    //put your code here
    private function driver_api_return($result){
        $data = $result;
        if(!isset($result['error'])){
            $result['error']=false;
        }
        else{
            unset($data['error']);
        }

        if(!isset($result['messages'])){
            $result['messages']='success';
        }
        else{
            unset($data['message']);
        }

        $result['code']=200;

        $result['data']=$data;
        return $result;
    }
}
