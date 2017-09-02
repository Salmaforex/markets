<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*==2017-Jun-29 22:59:13==*/
/*== Users_table =======*/
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Users_detail_table extends CI_Model
{
    private $db_main;
    public $table = 'mujur_usersdetail';

    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $CI = & get_instance();
        $this->db_main = $CI->load->database('default', true);
        
        $this->repair_table();
        logCreate('model| Users_table');
    }
    
    public function repair_table()
    {
        if(!$this->db_main->table_exists($this->table)) {
            $db_forge =$this->load->dbforge($this->db_main,true);
        }
         $aSql = array();

        if(!$this->db_main->table_exists($this->table)) {
                    $fields = array(
            'id'=>array(
                'type' => 'VARCHAR',
                'constraint' => '200'),
                'deleted'=>array( 'type' => 'integer'),
                'created'=>array( 'type' => 'datetime'),
                'u_modified'=>array( 'type' => 'timestamp'),
            );

                    $db_forge->add_field($fields);
                    $db_forge->add_key('id', TRUE);
                    $db_forge->create_table($this->table ,TRUE);
            //	$str = $this->db_main->last_query();
            //	logCreate("create table:$str");
                    $table= $this->db_main->dbprefix($this->table );
                    $sql="ALTER TABLE `$table` CHANGE `id` `u_id` BIGINT(20) NOT NULL AUTO_INCREMENT;";
                    $this->db_main->query($sql);
                    $sql="ALTER TABLE `$table` ENGINE=MyISAM";
                    $this->db_main->query($sql);
        }
        
        ///================
        $aSql=array();
        $table= $this->db_main->dbprefix($this->table );
        
        if(!$this->db_main->field_exists('deleted', $this->table)){
                $aSql[]="ALTER TABLE `$table` ADD `deleted` tinyint default 0;";
        }
        
        foreach($aSql as $sql){
            $this->db_main->query($sql);
            $str=$sql = $this->db_main->last_query();
            logCreate("create field:$str |table:$table");
        }
        return true;
    }
}