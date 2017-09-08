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

class Settings_model extends CI_Model {
public $tables;
    public function __construct()
    {
        $this->load->database();
        $tables=array(
            'country'=>'country',
        //    'erase'=>'users_erase',
        //    'detail'=>'users_detail'
            
        );
        
        foreach($tables as $name=>$filename){
            $table_name = $filename."_table";
            $this->load->model('tables/'.$table_name);
            $this->tables[$name]=$this->$table_name->table; 
        }
    }
    

    function country_get($id,$field='country_id'){
         $table = $this->tables['country'];
        $result = $this->db->get_where($table, array($field => $id))
                ->row_array();
        return $result ;
    }


}