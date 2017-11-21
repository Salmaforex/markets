<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once APPPATH . '/libraries/REST_Controller.php';
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/**
 * Description of Account
 *
 * @author gunawan
 */
class Settings extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('api');
        $this->load->database();
        $this->load->helper('basic');
    //    $this->load->library('session');
        $this->load->model('settings_model');
        header('Access-Control-Allow-Origin: *');
        //logCreate('rest user : server'.print_r($_SERVER,1));
    }

    //put your code here
    function currency_post() {
        $input = $post = $this->post();
        $respon['raw'] = array($post);
        $respon['error'] = 0;
        $respon['message'] = FALSE;

        if ($input) {
            $status=TRUE;
            if(isset($input['action'])&&$input['action']=='show_all' ){ 
                $status=FALSE;
            }
            
            $respon['currency'] = $this->settings_model->currency_list($status);
            unset($respon['raw']);
            $this->response($respon, 200);
        } else {
            $respon['error'] = 1;
            $respon['message'] = 'no id';
            $this->response($respon, 202);
        }
    }
    
    function country_post() {
        $input = $post = $this->post();
        $respon['raw'] = array($post);
        $respon['error'] = 0;
        $respon['message'] = FALSE;

        if ($input) {
            $status=TRUE;
            if(isset($input['action'])&&$input['action']=='show_all' ){ 
                $status=FALSE;
            }
            
            $respon['currency'] = $this->settings_model->country_get_all($status);
            unset($respon['raw']);
            $this->response($respon, 200);
        } else {
            $respon['error'] = 1;
            $respon['message'] = 'no id';
            $this->response($respon, 202);
        }
    }

}
