<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * 
public $table='mujur_users';
public $table_erase='mujur_users_erase';
public $tableDocument='mujur_usersdocument';
public $tableDetail='mujur_usersdetail';
public $tabletype='mujur_userstype';
public $table_erase_user='';
 */
defined('BASEPATH') OR exit('No direct script access allowed');
if (   function_exists('logFile')){ logFile('model','users_model.php','model'); };

class Users_model extends CI_Model {
public $tables;
    public function __construct()
    {
        $this->load->database();
        $tables=array(
            'main'=>'users',
        //    'erase'=>'users_erase',
            'detail'=>'users_detail'
            
        );
        
        foreach($tables as $name=>$filename){
            $table_name = $filename."_table";
            $this->load->model($table_name);
            $this->tables[$name]=$this->$table_name->table; 
        }
        
        $this->load->model('settings_model');
    }
    
    function exist_email($email){
        $table = $this->tables['main'];
        $result = $this->db->select("count(*) c")->get_where($table, array('u_email' => $email))
                ->row_array();
        return $result['c']==1?true:false;
    }
    
    function check_login($username,$password){
        $table = $this->tables['main'];
        $data=$this->gets($username);
        if($data==false) return false;
        $tmp=explode("|", $data['u_password'] );
        logCreate(json_encode($tmp));
        $keys=isset($tmp[1])?$tmp[1]:null;
        $pass=sha1("{$password}|{$keys}")."|".$keys;
        $sql="select count(*) c from {$table} 
        where u_email like '{$username}'
        and u_password like '{$pass}'
        and u_status=1";

        $res=dbFetchOne($sql,1);
        return $res['c']==1?true:false;
    }
    
    function gets($id,$field='u_email'){
         $table = $this->tables['main'];
        $result = $this->db->get_where($table, array($field => $id))
                ->row_array();
        return $result ;
    }
    

    function getDetail($id,$field='ud_email',$simple=false){
        $table= $this->tables['detail'];
            $sql="select ud_email, ud_detail from {$table} where `$field`='$id'";
            $res= dbFetchOne($sql);
            $respon=array();
            $respon=json_decode($res['ud_detail'],true);
            $email=$res['ud_email'];//unset($res['u_detail']);
            if($simple) return $respon;
            /*
            $sql="select udoc_status status from {$this->tableDocument} where `udoc_email` like '$email'";
            if($email=='' && $field=='ud_email')
                    $email=$id;
            $this->addNullDocument($email);
            $res= $this->document($email);//dbFetchOne($sql);
            $respon['document']=$res;
            */
            $respon['users']=$user_main=$this->gets($email);
            $respon['email']=$email;
            if($email=='') return $respon;

            $respon['typeMember']=isset($user_main['type_user'])?$user_main['type_user']:null;
            $respon['statusMember']=$user_main['u_status']==1?'ACTIVE':'NOT ACTIVE';
    //==========CITIZEN ?
            $citizen=isset($respon['citizen'])?$respon['citizen']:false;
            if($citizen){
            $respon['country']=$this->settings_model->country_get($citizen);
            }
            
            return $respon;
    }

}