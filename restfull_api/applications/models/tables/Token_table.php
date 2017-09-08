<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Token_table extends CI_Model {
public $table ='token';
private $db_main;
        
    public function __construct()
    {
        $this->db_main = $this->load->database('log', true);
        $this->repair_table();
        $this->clean();

    }

    function repair_table(){
        if(!$this->db_main->table_exists($this->table) ){	
            $db_forge =$this->load->dbforge($this->db_main,true);
        }

        if(!$this->db_main->table_exists($this->table)){
            $fields = array(
            'id'=>array(
                'type' => 'VARCHAR',  
                'constraint' => '200'),
            'modified'=>array( 'type' => 'timestamp'),
            );

            $db_forge->add_field($fields);
            $db_forge->add_key('id', TRUE);
            $db_forge->create_table($this->table ,TRUE);
            $table= $this->db_main->dbprefix($this->table );
            $sql="ALTER TABLE `$table` CHANGE `id` `id` BIGINT(20) NOT NULL AUTO_INCREMENT;";
            $this->db_main->query($sql);
            $sql="ALTER TABLE `$table` ENGINE=MyISAM";
            $this->db_main->query($sql);
            
        }

        $aSql=array();
        $table= $this->db_main->dbprefix($this->table );

        if(!$this->db_main->field_exists('parameter', $this->table)){
                $aSql[]="ALTER TABLE `$table` ADD `parameter` longtext";
        }

        if(!$this->db_main->field_exists('token', $this->table)){
                $aSql[]="ALTER TABLE `$table` ADD `token` varchar(255), ADD UNIQUE (`token`)";
        }

        if(!$this->db_main->field_exists('expire', $this->table)){
                $aSql[]="ALTER TABLE `$table` ADD `expire` datetime";
        }
        
            foreach($aSql as $sql){
                    $this->db_main->query($sql);
                    $str = $this->db_main->last_query();	
                    logConfig('sql:'.$sql ,'logDB','query');
            }
        return true;
    }
    
    private function clean(){
        $where = 'expire < "'.date("Y-m-d H:i:s").'"';
        $this->db_main->where($where);
        $this->db_main->delete($this->table);
        return true;
    }
	
}