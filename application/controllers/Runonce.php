<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//require_once(APPPATH.'/libraries/SmtpApi.php');
class Runonce extends CI_Controller {


    private $db_main;

    function index() {
        echo "start";
        $this->send_msg_to_agent();
    }
    
    function send_msg_to_agent(){
        $this->load->model('account_model');
        $agent = $this->account_model->all_agent(' email ');
        $agent_email=array();
        foreach($agent as $row){
            $email=  strtolower($row['email']);
            
            if($email!='')
                $agent_email[$email]=1;
        }
        ksort($agent_email);
        print_r($agent_email);
        foreach( array_keys($agent_email) as $email){
            $user = $this->users_model->getDetail($email);
            //print_r($user );
            $phone = $user['phone'];
            $account = $this->account_model->get_by_email($email);
            //print_r($account);
            $data=array(
                'username'=>$email,
                'account'=>$account,
                'phone'=>$phone
                
            );
            $str=$this->load->view('email/email_info_agent',$data,true);
            //die($str);
            //echo $str;
            echo "\n$email\ttotal:".count($account);
            if( count($account)>2)die($str);
            batchEmail($email,'Account List for Agent',$str);
            
        }
        
    }
    
    function list_agent(){
        $file = "tmp/account.csv";
        $row = 1;$agent=array();
        if (($handle = fopen($file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 3000, ";")) !== FALSE) {
                $num = count($data);
                //echo "<p> $num fields in line $row: <br /></p>\n";
                $row++;
               /*  
                for ($c=0; $c < $num; $c++) {
                    echo $c."\t->\t".$data[$c] . "\n";
                }
               */
                $pos=15;
                    $key=trim($data[15]);
                    if($key != (int)$key){
                        $key=trim($data[16]);
                        $pos=16;
                    }
                    
                    if($key=='1 : 1000'){
                        $pos++;
                        $key=trim($data[$pos]);
                        //echo $pos;die($key);
                    }
                    
                    if($key=='1 : 200'){
                        $pos++;
                        $key=trim($data[$pos]);
                        //echo $pos;die($key);
                    }
                    
                if($key!=0){
                    //echo "\n$agent\t{$data[0]}\t".$data[9];
                    //$agent++;
                    
                    
                    if($key != (int)$key){
                         for ($c=0; $c < $num; $c++) {
                            echo $c."\t->\t".$data[$c] . "\n";
                        }
                        die();
                    }
                    $agent[ $key ]=isset($agent[ $key ])?$agent[ $key ]:0;
                    $agent[ $key ]++;
                    
                }
                else{
               //    echo "\n$row not agent ".$data[15];
                }
                //if($row>53)exit;
                //echo "\n\n";
            }
            fclose($handle);
        }
        else{
            echo "error?";
        }
        ksort($agent);
        echo "\ntotal:".count($agent)."\n".print_r($agent,1)."\n";
        foreach($agent as $kode=>$total){
            $str="total dibawah agent:".$total;
            $sql="UPDATE `mujur_account` SET `type` = 'AGENT',`detail`='$str' where accountid='$kode';";
            echo "\n".$sql;
        }
        
    }

    //put your code here
    function __CONSTRUCT() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
    }

}
