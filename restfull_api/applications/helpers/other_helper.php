<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

defined('BASEPATH') OR exit('No direct script access allowed');
if ( ! function_exists('callback_submit')){
	function callback_submit(){
    $CI =& get_instance();
    $notif=$CI->session->flashdata('notif');
    if($notif){
/*	echo '
	    <div class="note '.($notif['status'] ? 'note-success' : 'note-danger').' note-shadow">
		    <button class="close" data-close="alert"></button>
		    <p>'.$notif['msg'].'</p>
	    </div>
	';
*/
	echo '<div style="margin:20px 40px" class="alert alert-block '.($notif['status'] ? 'alert-success' : 'alert-danger').' fade in">
          <button data-dismiss="alert" class="close" type="button"> × </button>
          <h4 class="alert-heading"><i class="fa fa-times"></i>'.($notif['status'] ? 'Information' : 'Notice').'!</h4>
          <p>'.$notif['msg'].'</p>
        </div>';
	//echo_r($notif);
    }
	}
} else{}


if(!function_exists('email_problem')){
	function email_problem($title='', $msg='', $debug=array()){
            $emails =ciConfig('email_for_report');
            $title ='[WARNING] '.$title;
            $headers = "From: noreply@salmaforex.com\r\n";
            $headers .= "Reply-To: noreply@salmaforex.com\r\n"; 
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

            $message="Date ".date("Y-m-d H:i:s")."<br/>\nProblem occurs:<br/>\n".$msg;
            $message.="<hr/>Detail:\n<pre>".print_r($debug,1)."</pre>";

            logCreate('email problem title:'.$title);
            foreach($emails as $email){
                    batchEmail( $email, $title, $message , $headers );
            }

            log_info_table('problem',array($title,$msg));
            logCreate('email problem title:'.$title.' | '.strip_tags($msg).' |debug:'.json_encode($debug));
	}
	
}

if(!function_exists('log_info_table')){
	function log_info_table($type, $array){
            $CI =& get_instance();
            $input = array();//'tmp0'=>$type);
            foreach( $array as $n0=>$val){
                    $n= $n0+1;
                    $val_input=is_string($val)?$val:json_encode($val);
                    if(strlen($val_input)>250){
                            $val_input=substr($val_input,0,250);
                    }
                    $input['tmp'.$n]=$val_input;
            }

            $input['param']=json_encode($array);
            
            //log_info_table_make($type);

            $tablename='y_'.trim($type)."s".date("Y");
            logCreate('log info table:'.$tablename);
            if(!$CI->db->table_exists($tablename)){
                logCreate('log info not found:'.$tablename);
                $fields = array(
                          'id'=>array( 
                                'type' => 'BIGINT','auto_increment' => TRUE),

                          'param'=>array( 'type' => 'longtext'),				   
                          'modified'=>array( 'type' => 'timestamp'),
                        );
                $CI->dbforge->add_field($fields);
                $CI->dbforge->add_key('id', TRUE);
                $CI->dbforge->create_table($tablename,TRUE);
                $str = $CI->db->last_query();	

                logConfig("create table:$str");
                $sql="ALTER TABLE `$tablename` ENGINE=MyISAM";

//===========NAMBAH BEBERAPA tmp input============
                for($i=1;$i<=30;$i++){
                        $sql="ALTER TABLE `{$tablename}` ADD `tmp{$i}` varchar(255) NULL;";
                        dbQuery($sql);
                }

                logCreate('log info create:'.$tablename);
//================================================
            }

            $sql = dbInsert($tablename, $input);
            logCreate('log insert:'.$tablename);
	}
        
    function log_info_table_make($type){
          $CI =& get_instance();
        $tablename='y_'.trim($type)."s".date("Ym");
        for($i=1;$i<=12;$i++){
            $bln=  sprintf("%02s",$i);
            $tablename = 'y_'.trim($type)."s".date('Y').$bln;             
            if(!$CI->db->table_exists($tablename) ){
                logCreate('log_info_table_make not found:'.$tablename);
                $fields = array(
                          'id'=>array( 
                                'type' => 'BIGINT','auto_increment' => TRUE),
                          'param'=>array( 'type' => 'longtext'),				   
                          'modified'=>array( 'type' => 'timestamp'),
                        );
                $CI->dbforge->add_field($fields);
                $CI->dbforge->add_key('id', TRUE);
                $CI->dbforge->create_table($tablename,TRUE);
                $str = $CI->db->last_query();	

                logConfig("create table:$str");
                $sql="ALTER TABLE `$tablename` ENGINE=MyISAM";

//===========NAMBAH BEBERAPA tmp input============
                for($i=1;$i<=30;$i++){
                        $sql="ALTER TABLE `{$tablename}` ADD `tmp{$i}` varchar(255) NULL;";
                        dbQuery($sql);
                }

                logCreate('log_info_table_make create:'.$tablename);
//================================================
            }
        }
    }
}
