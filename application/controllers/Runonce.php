<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//require_once(APPPATH.'/libraries/SmtpApi.php');
class Runonce extends CI_Controller {


    private $db_main;

    function index() {
        echo "start";
        $this->list_agent();
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
