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
class Salma_login_member extends CI_Driver {
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
    
    //put your code here
    function executed($params){
        $return = array();
        $CI =& get_instance();

        $return['title']='Login Secure Area';
        $return['show_open_live']=true;
        $return['content']=array(
            'modal',
            'login', 
        );
        
        return $return;
    }
}
