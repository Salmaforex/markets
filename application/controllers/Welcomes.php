<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
//require_once(APPPATH.'/libraries/SmtpApi.php');
class Welcomes extends CI_Controller {
    function index(){
        redirect('welcome');
        echo 'hello';
    }
    
    
}