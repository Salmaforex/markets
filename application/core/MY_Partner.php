<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MY_Partner
 *
 * @author gunawan
 */
class MY_Partner extends MY_Controller {
    function __CONSTRUCT() {
        parent::__construct();
        logCreate('start controller partner');
        date_default_timezone_set('Asia/Jakarta');
        $this->param['today'] = date('Y-m-d');
        $this->param['folder'] = 'depan/';
        $this->param['active_controller'] = 'partner';

        $this->load->helper('form');
        $this->load->helper('formtable');
        $this->load->helper('language');
        $this->load->helper('api');
        $this->load->helper('db');
        $this->load->model('forex_model', 'forex');
        $this->load->model('country_model', 'country');
        $this->load->model('account_model', 'account');
        $defaultLang = "english";
        $this->lang->load('forex', $defaultLang);

        $this->param['fileCss'] = array(
            'css/style.css',
            'css/bootstrap.css',
            'css/ddaccordion.css'
        );
        $this->param['fileJs'] = array(
            'js/jquery-1.7.min.js',
            'js/ddaccordion.js'
        );

        $this->param['shortlink'] = base_url();
        $this->param['footerJS'] = array(
            'js/bootstrap.min.js',
            'js/formValidation.min.js',
            'js/scripts.js'
        );
        $this->param['description'] = "Trade now with the best and most transparent forex STP broker";

        $this->param['emailAdmin'] = $this->forex->emailAdmin;

        $this->param['session'] = $this->session->all_userdata();
        $this->param['baseFolder'] = 'depan/';
        $this->param['noBG'] = true;
        $this->param['outerJS'] = array();
        $this->folderUpload = 'media/uploads/';

        $this->param['title'] = 'Secure Area';

        //===============BARU=============
        $this->param['fileCss'] = array(
            'css001/style_salmamarkets.css',
            'css001/bootstrap.css',
            'css/ddaccordion.css'
        );
        
        $this->param['fileJs'] = array(
            'js/jquery-1.7.min.js',
            'js/ddaccordion.js'
        );

        $this->param['patnerShowPanel'] = true;
        $this->param['shortlink'] = base_url();
        $this->param['controller_main'] = 'partner';
        $this->param['footerJS'] = array(
            'js/bootstrap.min.js',
            'js/formValidation.min.js',
            'js/scripts_new.js',
                //'js001/scripts.js'
        );
        /*
          if($this->input->post())
          logCreate($this->input->post(),'post');
         */
    }

}
