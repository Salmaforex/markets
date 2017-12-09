<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Api_model extends CI_Model {

    public $table = 'z_temp_api';

    function random($num = 1) {
        $sql = "select password from `{$this->table}` order by rand() limit {$num}";
        return dbFetch($sql);
    }

    public function __construct() {
        $this->load->database();
        $this->load->dbforge();
        $this->recover();
    }

    function clean() {
        $date = date("Y-m-d H:i:s", strtotime("-3 minutes"));
        $sql = 'delete from `' . $this->table . '` where `created` <\'' . $date . "'";
        dbQuery($sql);
        $sql = 'OPTIMIZE TABLE `' . $this->table . '` ';
        //dbQuery($sql);
    }

    function new_data($raw) {
        if (!isset($raw['params'])) {
            return false;
        }
        if ($raw['params'] == null) {
            return false;
        }
        if (!isset($raw['result'])) {
            $raw['result'] = '';
        }
        //	$data['id']=dbId('api');
        $data['params'] = trim(json_encode($raw['params']));
        $data['result'] = is_array($raw['result'])||  is_object($raw['result']) ? json_encode($raw['result']) : trim($raw['result']);
        $data['created'] = date("Y-m-d H:i:s");
        //	$data['api']=trim($raw['api']);
        $data['functions'] = trim($raw['functions']);
        $data['member_login'] = trim($raw['member_login']);

        if (strlen($data['result']) > 100) {
            $data['result'] = substr($data['result'], 0, 100);
        }

        //dbInsert($this->table, $data);
        return $data;
    }

    function find_result($function, $params, $member_login) {
        //	return false;
        $params = trim(json_encode($params));
        //	$api	=trim($api );
        $functions = trim($function);
        $member_login = trim($member_login);
        $this->db->where('params', $params);
        //	$this->db->where('api',$api);
        $this->db->where('functions', $functions);
        $this->db->where('member_login', $member_login);
        //============
        $row = $this->db->get($this->table)->row_array();
        $result = isset($row['result']) ? $row['result'] : false;
        $id = isset($row['id']) ? $row['id'] : false;
        if ($id) {
            $n = $row['count_execute'] + 1;
            $table = $this->db->dbprefix($this->table);
            $sql = "update `$table` set `count_execute`=$n where `id`='$id'";
            dbQuery($sql);
        }
        $json = @json_decode($result, 1);
        $sql = $this->db->last_query();
        logConfig('sql:' . $sql . '|affected:' . $this->db->affected_rows(), 'logDB', 'query');
        return is_array($json) ? $json : $result;
    }

    function recover() {
        if (!$this->db->table_exists($this->table)) {
            $fields = array(
                'id' => array(
                    'type' => 'BIGINT', 'auto_increment' => TRUE),
                'params' => array(
                    'type' => 'text'),
                'modified' => array('type' => 'timestamp')
            );
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table($this->table, TRUE);
            $str = $this->db->last_query();
            logConfig("create table:$str");
            $this->db->reset_query();
            $table = $this->db->dbprefix($this->table);
            $sql = "ALTER TABLE `$table` CHANGE `id` `id` BIGINT(20) NOT NULL AUTO_INCREMENT;";
            $this->db->query($sql);
            $sql = "ALTER TABLE `$table` ENGINE=MyISAM";
            $this->db->query($sql);
        } else {
            
        }
        $aSql = array();

        if (!$this->db->field_exists('count_execute', $this->table)) {
            $table = $this->db->dbprefix($this->table);
            $aSql[] = " ALTER TABLE `$table` ADD `count_execute` INT NOT NULL DEFAULT '1' COMMENT 'berapa kali digunakan' ;";
        }
        if (!$this->db->field_exists('created', $this->table)) {
            $table = $this->db->dbprefix($this->table);
            $aSql[] = "ALTER TABLE `$table` ADD `created` datetime NULL;";
            $aSql[] = "ALTER TABLE `$table`  ADD KEY `api` (`api`)";
        }
        /* 	
          if(!$this->db->field_exists('api', $this->table)){
          $table= $this->db->dbprefix($this->table );
          $aSql[]="ALTER TABLE `$table` ADD `api` varchar(50) NULL;";
          $aSql[]="ALTER TABLE `$table`  ADD KEY `api` (`api`)";
          }
         */
        if (!$this->db->field_exists('functions', $this->table)) {
            $table = $this->db->dbprefix($this->table);
            $aSql[] = "ALTER TABLE `$table` ADD `functions` varchar(100) NULL;";
            $aSql[] = "ALTER TABLE `$table`  ADD KEY `functions` (`functions`)";
        }
        if (!$this->db->field_exists('member_login', $this->table)) {
            $table = $this->db->dbprefix($this->table);
            $aSql[] = "ALTER TABLE `$table` ADD `member_login` varchar(200) NULL;";
            $aSql[] = "ALTER TABLE `$table`  ADD KEY `member_login` (`member_login`)";
        }
        if (!$this->db->field_exists('result', $this->table)) {
            $table = $this->db->dbprefix($this->table);
            $aSql[] = "ALTER TABLE `$table` ADD `result` LONGBLOB NULL";
        }
        foreach ($aSql as $sql) {
            $this->db->query($sql);
            $str = $this->db->last_query();
            logCreate("create field:$str |table:$table");
        }
    }

}
