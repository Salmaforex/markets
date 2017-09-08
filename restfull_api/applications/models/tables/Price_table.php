<?php
/*?>*/
/*==2017-Jun-29 22:59:13==*/
/*== Currency_table =======*/ 
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Price_table extends CI_Model {
    private $db_main;
	public $table='mujur_price';
	function __construct() {
        // Call the Model constructor
        parent::__construct();
        $CI = & get_instance();
		$this->db_main = $CI->load->database('default', true);
        $this->repair_table();
    }

	function new_data($data){
            $data['created']=date('Y-m-d H:i:s');
    //  $data['id']=db_id();
            $data['deleted']=0;
            $this->db_main->insert($this->table, $data);
            $this->reload_data( $data['id']); 
	}

	function reload_data($id){
		return true;
	}

	function repair_table(){
		if(!$this->db_main->table_exists($this->table) ){	
			$db_forge =$this->load->dbforge($this->db_main,true);
		}
		
	    if(!$this->db_main->table_exists($this->table)){
			$fields = array(
	        'id'=>array(
	            'types' => 'VARCHAR',  
	            'constraint' => '200'),
                    'created'=>array( 'type' => 'timestamp'),
                );

			$db_forge->add_field($fields);
			$db_forge->add_key('id', TRUE);
			$db_forge->create_table($this->table ,TRUE);
		//	$str = $this->db_main->last_query();
		//	logConfig("create table:$str");
			$table= $this->db_main->dbprefix($this->table );
			$sql="ALTER TABLE `$table` CHANGE `id` `id` BIGINT(20) NOT NULL AUTO_INCREMENT;";
			$this->db_main->query($sql);
			$sql="ALTER TABLE `$table` ENGINE=MyISAM";
			$this->db_main->query($sql);
	    }
/*
			if(!$this->db->table_exists($this->tablePrice)){
				$fields = array(
				  'id'=>array( 
					'type' => 'BIGINT','auto_increment' => TRUE), 		   
				  'types'=>array( 
					'type' => 'VARCHAR',  
					'constraint' => '200'),
				  'price'=>array( 'type' => 'integer'),				   
				  'created'=>array( 'type' => 'timestamp'),
				);
				$this->dbforge->add_field($fields);
				$this->dbforge->add_key('id', TRUE);
				$this->dbforge->create_table($this->tablePrice,TRUE);
				$str = $this->db->last_query();			 
				logConfig("create table:$str");
				$this->db->reset_query();	
				$this->db->insert('mujur_price', 
					array('types'=>'deposit', 'price'=>14000));
				$this->db->insert('mujur_price', 
					array('types'=>'widtdrawal', 'price'=>13500));
			}
*/
            $aSql=array();
			$table= $this->db_main->dbprefix($this->table );

            if(!$this->db_main->field_exists('price', $this->table)){
                $aSql[]="ALTER TABLE `$table` ADD `price` integer default 0;";
            }
            if(!$this->db_main->field_exists('deleted', $this->table)){
                $aSql[]="ALTER TABLE `$table` ADD `deleted` tinyint;";
            }

            if(!$this->db_main->field_exists('currency', $this->table)){
                $aSql[]="ALTER TABLE `$table` ADD `currency` varchar(255) default 'IDR';";
                $aSql[]="ALTER TABLE `$table`  ADD KEY `currency` (`currency`)";
            }

            if(!$this->db_main->field_exists('approved', $this->table)){
            	$aSql[]="ALTER TABLE `$table` ADD `approved` tinyint NULL;";
            	$aSql[]="ALTER TABLE `$table`  ADD KEY `approved` (`approved`)";
            }
//===============LOG===============
        if(!$this->db_main->field_exists('last_update', $this->table)){
            $aSql[]="ALTER TABLE `$table` ADD `last_update` longtext;";
            $aSql[]="ALTER TABLE `$table` ADD `log_last_update` longtext;";
			$aSql[]="insert into `$table` (created) value(NOW())";
        }
//ALTER TABLE `kn_price` ADD UNIQUE( `expert_category`, `expert_skills`, `user_id`);
		foreach($aSql as $sql){
			$this->db_main->query($sql);
			$str = $this->db_main->last_query();	
			logConfig("create field:$str |table:$table");
		}
		return true;
	}
     
    function tablename($table_name='table'){
        return  $this->db_main->dbprefix($this->$table_name);
    }
}