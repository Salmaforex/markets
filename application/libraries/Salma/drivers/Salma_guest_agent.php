<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Salma_guest_home
 *
 * @author gunawan
 */
class Salma_guest_agent extends CI_Driver {
private $urls,$privatekey;
public $CI;
    function __CONSTRUCT(){
            $CI =& get_instance();
            $CI->load->helper('api');
            //$CI->config->load('forexConfig_new', TRUE);
            $CI->load->helper('formtable');
            $CI->load->helper('form');
            $CI->load->library('session');
    }
    
    function register($params){
        $agents = $params[0];
        $raw = $params[1];
        $return = array();
        $CI =& get_instance();
        
        $return['fullregis']=true;
        $return['statAccount']='agent';
        $return['fullregis']=true;
        $return['statAccount']='agent';

        if($CI->session->flashdata('register')){
            $return['register']=$CI->session->flashdata('register');
            logCreate('session register valid','info');
        }

        
        if($raw!=NULL){
        //    echo_r($params);die;
            $ar=explode("-",$raw);
            logCreate("agent ref:$raw id:{$ar[0]}","info");
            $num=trim($ar[0]);
            $CI->session->set_flashdata('agent', $num);
            logCreate('parameter agent:'.$num,'info');
            redirect(site_url('welcome')."?reg=agent",1);
            exit();
        }
        else{
            $num=$info=$CI->session->flashdata('agent');
            $return['agent']=$num!=''?$num:'';
        }

        //$return['showAgent']=true;
        $return['title']='Open Live Account'; 
        if(!isset($return['formTitle'])) 
            $return['formTitle']=$return['title'];
        
        $return['content']=array(
                //'modal',
                'welcome', 
        );
        
        return $return;
    }
    //put your code here
    function executed($params){
        $agents = $params[0];
        $raw = $params[1];
        $return = array();
        $CI =& get_instance();

        $return['statAccount']='member';
        if($agents!==false){
                $agent = explode("_",$agents);
                $return['fullregis']=true;
                $return['agent_code']=$agent[0];
        }

        if($CI->session->flashdata('register')){
            $return['register']=$CI->session->flashdata('register');
            logCreate('session register valid','info');
        }

        if(!isset($agent[0])){
            $agent_num='';
        }
        else{
            $agent_num=$agent[0];
        }

        if(isset($return['register'])){
            $return['register']['agent']=$return['agent']=$agent_num;
        }

        if($raw!='0'){
            $ar=explode("-",$raw);
            logCreate("agent ref:$raw id:{$ar[0]}","info");
            $num=trim($ar[0]);
            $CI->session->set_flashdata('agent', $num);
            logCreate('parameter agent:'.$num,'info');
            redirect(base_url('welcome'),1);
            exit();
        }
        else{
            $num=$info=$CI->session->flashdata('agent');
            $return['agent']=$num!=''?$num:'';
        }

        if($return['statAccount']=='agent'){
            //$return['showAgent']=true;
        }
        else{
            $return['showAgent']=true;
        }

        $return['title']='Open Live Account';//-- 
        if(!isset($return['formTitle'])) 
            $return['formTitle']=$return['title'];

        $return['content']=array(
                'welcome', 
        );
        
        return $return;
    }
}
