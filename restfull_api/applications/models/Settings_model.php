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
            'currency'=>'currency',
            'batch_email'=>'zemail'
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
    
    function country_get_all(){
         $table = $this->tables['country'];
         $this->db->select( 'country_id id, country_code code,country_name name');
         return $this->db->get($table)->result_array();
    }
    //=============MATA UANG
    function currency_list($approve_only = true) {
        $sql = "select * from `{$this->tables['currency']}` ";
        if ($approve_only) {
            $sql.="where approved=1";
        }

        return dbFetch($sql);
    }

    function select_currency() {
        $dt = $this->currency_list(false);
        $res = array();
        foreach ($dt as $row) {
            $res[$row['code']] = $row['name'] . ' (' . $row['symbol'] . ')';
        }

        return $res;
    }

    function currency_by_code($code) {
        $sql = "select * from `{$this->tables['currency']}` where code like '{$code}'";
        return dbFetchOne($sql);
    }

    function currency_new($data) {
        $data['deleted'] = 0;
        $data['created'] = date("Y-m-d H:i:s");
        $data['approved'] = 0;
        return dbInsert($this->tables['currency'], $data);
    }

    function currency_approve($code) {
        $sql = "update `{$this->tables['currency']}` set approved='1' where code='$code'";
        dbQuery($sql);
        return true;
    }

    function currency_disable($code) {
        $sql = "update `{$this->tables['currency']}` set approved='0' where code='$code'";
        dbQuery($sql);
        return true;
    }
//=================Rate	
    function rate_update($raw) {
        $data = array(
            'types' => $raw['types'],
            'price' => $raw['rate'],
            'currency' => $raw['currency']
        );
        if ($raw['types'] == '' || $raw['rate'] == '')
            return false;
        $rate0 = $this->rateNow($raw['types'], $raw['currency']);

        dbInsert($this->tablePrice, $data);

        return true;
        //$this->db->insert($this->tableAPI,$data);
        //dbInsert($this->tableAPI,$data);
    }

    function rate_now($types = '', $curr = 'IDR') {
//==========Menambah mujur_price
        $types = addslashes($types);
        $sql = 'select p.id, p.price `value`, c.* from mujur_price p left join `' . $this->tableCurrency . '` c
		on p.currency=c.code
		where p.types like "' . $types . '" and p.currency="' . $curr . '"
		order by p.id desc limit 1';
        $row = dbFetchOne($sql);

        return $row;
    }
    
    //===================EMAIL
    function email_get_all($filter, $limit=100, $start=0){
        $res = array();
         $table = $this->tables['batch_email'];
         $this->db->reset_query();
        if(isset($filter['where_simple'])){
            $this->db->where($filter['where_simple'] );
        }
        
        
        if(isset($filter['id'])){
            $this->db->where('id',$filter['id'] );
        }

        $this->db->limit($limit, $start);
        //  $this->db->table($this->table );
        $sql = $this->db->get_compiled_select($table. "", FALSE);


        //$res[]=array(  $sql,json_encode($filter),$limit,$start);
        $res = dbFetch($sql);
        $this->db->reset_query();
        return $res;
    }
    
    function email_get_active(){
        $filter['where_simple']= array('send_status'=>0);
        return $this->email_get_all($filter); 
    }
    
    function email_get_id($id){
        $filter=array('id'=>$id);
        $row= $this->email_get_all($filter);
        
        return isset($row[0])?$row[0]:$row;
    }
    
    function email_send($id ){
        $table = $this->tables['batch_email'];
        $sql = "update `{$table}` set `send_status`='1' where id='$id'";
        return dbQuery($sql);
         
    }

}