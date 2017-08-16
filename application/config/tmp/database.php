<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$active_group = 'default';
$query_builder = TRUE;

if(defined('LOCAL')){
	$db['default'] = array(
		'dsn'	=> '',
		'hostname' => 'localhost',
		'username' => 'mujur_forex',
		'password' => 'v5aKFPRKDFxhV6A4',
		'database' => 'mujur_forex160710',
		'dbdriver' => 'mysqli',
		'dbprefix' => '',
		'pconnect' => FALSE,
		'db_debug' => TRUE,
		'cache_on' => FALSE,
		'cachedir' => '',
		'char_set' => 'utf8',
		'dbcollat' => 'utf8_general_ci',
		'swap_pre' => '',
		'encrypt' => FALSE,
		'compress' => FALSE,
		'stricton' => FALSE,
		'failover' => array(),
		'save_queries' => TRUE
	);
}else{}

if(defined('_DEV_')){
	$db['default'] = array(
		'dsn'	=> '',
		'hostname' => 'mysql.idhostinger.com',
		'username' => 'u429780871_dev',
		'password' => 'n4cC5l0oA4',
		'database' => 'u429780871_dev',
		'dbdriver' => 'mysqli',
		'dbprefix' => '',
		'pconnect' => FALSE,
		'db_debug' => TRUE,
		'cache_on' => FALSE,
		'cachedir' => '',
		'char_set' => 'utf8',
		'dbcollat' => 'utf8_general_ci',
		'swap_pre' => '',
		'encrypt' => FALSE,
		'compress' => FALSE,
		'stricton' => FALSE,
		'failover' => array(),
		'save_queries' => TRUE
	);
}else{}
//Database telah dibuat. Password untuk database u429780871_dev diubah menjadi n4cC5l0oA4
if(!isset($db['default'])){
	$db['default'] = array(
		'dsn'	=> '',
		'hostname' => '93.188.164.21',
		'username' => 'mujur_salma',
		'password' => 'mujur!salma227',
		'database' => 'mujur_salma',
		'dbdriver' => 'mysqli',
		'dbprefix' => '',
		'pconnect' => FALSE,
		'db_debug' => FALSE,
		'cache_on' => FALSE,
		'cachedir' => '',
		'char_set' => 'utf8',
		'dbcollat' => 'utf8_general_ci',
		'swap_pre' => '',
		'encrypt' => FALSE,
		'compress' => FALSE,
		'stricton' => FALSE,
		'failover' => array(),
		'save_queries' => TRUE
	);
/*
		'hostname' => 'mysql.idhostinger.com',
		'username' => 'u429780871_forex',
		'password' => '|V4CMBo6mj',
		'database' => 'u429780871_forex',
//===========================
		'hostname' => '31.220.56.227',
		'username' => 'mujur_salma',
		'password' => 'mujur!salma227',
		'database' => 'mujur_salma',
*/		
}

