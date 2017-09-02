<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Repair extends MY_Controller {
	function index(){
		$max=40;
		$table=$this->account->tableAccount;
		$n= (int)$this->input->get('p');
		$start=$n*$max;
		echo $table;
		$sql="select count(*) c from `{$table}`";
		$row=dbFetchOne($sql);
		$total=$row['c'];
		echo ' total:'.$total;
		$sql="select * from `{$table}` order by id asc limit $start, $max";
		$q=dbFetch($sql);
		$total2=count($q);
		if($total2==0) die('stop');
		$aSql=array();
		foreach($q as $row){
			$aSql[]="update `$table` set email='".strtolower(addslashes(trim($row['email'])))."' where id=$row[id]";
		}
		foreach($aSql as $sql){
			dbQuery($sql);
			echo '<br/>'.$sql;
		}
		$url=site_url('repair')."?p=".++$n;
		 echo '<script>window.location.href = "' . $url . '";</script>';
	//	echo '<pre>'.print_r($aSql,1).'</pre>';
//		echo '<br/>'.$sql;
//		echo '<br/> total:'.print_r(array_keys($row),1);
	}
	function images($name){
		$img=file_get_contents('media/'.$name);
		echo $img;
	}
	function dirs_img($name1,$name2,$name3){
		$img=file_get_contents('media/'.$name2.'/'.$name3);
		echo $img;
	}
function __CONSTRUCT(){
	parent::__construct(); 		
		date_default_timezone_set('Asia/Jakarta');
		$this->param['today']=date('Y-m-d');
		$this->param['folder']='depan/';
		$this->load->helper('form');
		$this->load->helper('formtable');
		$this->load->helper('language');
		$this->load->helper('api');
		$this->load->helper('db');
		$this->load->model('forex_model','forex');
		$this->load->model('country_model','country');
		$this->load->model('account_model','account');
}
}