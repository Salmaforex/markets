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
class Email extends REST_Controller {

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
    function batch_get() {
        $respon='nothing here';
        $this->response($respon, 200);
    }
    
    function batch_post() {
        $input = $post = $this->post();
        $respon['raw'] = array( );
        $respon['err_code'] = FALSE; //wajib ada
        $respon['message'] = NULL; //wajib ada

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // More headers
        $headers .= 'From: <' . $post['from'] . '>' . "\r\n";
        //$headers .= 'Cc: myboss@example.com' . "\r\n";

        if ($input) {
            $data = array(
                'subject' => $post['title'],
                'send_to' => $post['to'],
                'headers' => $headers,
                'send_status' => 0,
                'send_from' => $post['from'],
                'deleted' => 0,
                'created' => date("Y-m-d H:i:s")
            );
            $data['messages'] = $post['message'];
            $data['send_type'] = isset($post['type']) ? $post['type'] : 'Unknown';

            $table = $this->settings_model->tables['batch_email'];
            $respon['raw'][]=$data;
            $respon['raw'][]=$table;
            
            $respon['raw'][] = dbInsert($table, $data);
            //unset($respon['raw']);
            $respon['message'] = 'success';
            $this->response($respon, 200);
        } else {
            $respon['error'] = 1;
            $respon['message'] = 'no id';
            $this->response($respon, 202);
        }
    }

    function list_all_post() {
        $input = $post = $this->post();
        $respon['raw'] = array($post);
        $respon['err_code'] = FALSE; //wajib ada
        $respon['message'] = NULL; //wajib ada

        if (isset($post['active'])) {
            $respon['email'] = $this->settings_model->email_get_active();
        } else {
            $respon['email'] = $this->settings_model->email_get_all(array(), 100);
        }
        unset($respon['raw']);
        $this->response($respon, 200);
    }

    function send_post() {
        $input = $post = $this->post();
        $respon['raw'] = array($post);
        $respon['err_code'] = FALSE; //wajib ada
        $respon['message'] = NULL; //wajib ada
        $id = $input['id'];

        $this->settings_model->email_send($id);
        $respon['email'] = $this->settings_model->email_get_id($id);

        //unset($respon['raw']);
        $this->response($respon, 200);
    }

}
