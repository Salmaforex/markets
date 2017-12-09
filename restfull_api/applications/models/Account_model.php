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

class Account_model extends CI_Model {
public $tables;
    public function __construct()
    {
        $this->load->database();
        $tables=array(
            'main'=>'account',
        //    'erase'=>'users_erase',
        //    'detail'=>'users_detail'
            
        );
        
        foreach($tables as $name=>$filename){
            $table_name = $filename."_table";
            $this->load->model('tables/'.$table_name);
            $this->tables[$name]=$this->$table_name->table; 
        }
    }
    

    function gets($id,$field='u_email'){
         $table = $this->tables['main'];
        $result = $this->db->get_where($table, array($field => $id))
                ->row_array();
        return $result ;
    }

    function get_all($filter=array(),$limit=100,$start=0){
         $table = $this->tables['main'];
        if(isset($filter['count_all'])){
            $this->db->select("count(*) as total");
            $result = $this->db->get($table)
                ->row_array()['total'];
        }
        else{
            $this->db->limit($limit,$start);
            $result = $this->db->get($table)
                ->result_array();
        }
        
        
        
        return $result ;
    }

}