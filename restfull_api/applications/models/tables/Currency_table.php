<?php
/*?>*/
/*==2017-Jun-29 22:59:13==*/
/*== Currency_table =======*/ 
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Currency_table extends CI_Model {
    private $db_main;
	public $table='mujur_currency';
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
	            'type' => 'VARCHAR',  
	            'constraint' => '200'),
                    'modified'=>array( 'type' => 'timestamp'),
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
            $aSql=array();
			$table= $this->db_main->dbprefix($this->table );

            if(!$this->db_main->field_exists('created', $this->table)){
                $aSql[]="ALTER TABLE `$table` ADD `created` datetime default '2017-07-01';";
            }
            if(!$this->db_main->field_exists('deleted', $this->table)){
                $aSql[]="ALTER TABLE `$table` ADD `deleted` tinyint;";
            }
            if(!$this->db_main->field_exists('code', $this->table)){
                $aSql[]="ALTER TABLE `$table` ADD `code` varchar(10) default 'IDR';";
                $aSql[]="ALTER TABLE `$table`  ADD UNIQUE `code` (`code`)";
            }
            if(!$this->db_main->field_exists('name', $this->table)){
                $aSql[]="ALTER TABLE `$table` ADD `name` varchar(255) default 'Rupiah';";
                $aSql[]="ALTER TABLE `$table`  ADD KEY `name` (`name`)";
            }
            if(!$this->db_main->field_exists('symbol', $this->table)){
                $aSql[]="ALTER TABLE `$table` ADD `symbol` varchar(255) default 'Rp';";
                $aSql[]="ALTER TABLE `$table`  ADD KEY `symbol` (`symbol`)";
            }
			
            if(!$this->db_main->field_exists('detail', $this->table)){
            	$aSql[]="ALTER TABLE `$table` ADD `detail` longtext ,ADD FULLTEXT KEY `detail` (`detail`);";
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
	
	function get_by_userid($user_id){ //=========example==============//
		$query = $this->db_main->get_where($this->table, array('user_id' => $user_id));
		return $query->result_array();
	}

	function get_id($id){
		$query = $this->db_main->get_where($this->table, array('id' => $id));
		return $query->row_array();
	}
	
	function update_data($data, $where){
		$this->db_main->where($where[0], $where[1]);
		$this->db_main->update($this->table , $data);
	}
        
    function tablename($table_name='table'){
        return  $this->db_main->dbprefix($this->$table_name);
    }
}