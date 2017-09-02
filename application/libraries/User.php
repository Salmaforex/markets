<?php 
class User {

    private $CI;

        public function __construct()
        {
            $this->CI =& get_instance();
            $this->CI->load->helper('form');
        }

        public function create_login_form()
        {
            echo 'hello';
            echo $this->CI->form->form_open();
        }

}