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
//Database telah dibuat. Password untuk database u429780871_dev diubah menjadi n4cC5l0oA4
if(!isset($db['default'])){
	$db['default'] = array(
		'dsn'	=> '',
		'hostname' => 'mysql.idhostinger.com',
		'username' => 'u429780871_forex',
		'password' => '|V4CMBo6mj',
		'database' => 'u429780871_forex',
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
	
}
/*


*/

/*DATABASE LOG disarankan di local */
$db['log']['hostname'] = 'localhost';
$db['log']['username'] = 'mujur_forex';
$db['log']['password'] = 'v5aKFPRKDFxhV6A4';
$db['log']['database'] = 'mujur_forex';
$db['log']['dbdriver'] = 'mysqli';
$db['log']['dbprefix'] = 'zl_';
$db['log']['pconnect'] = FALSE;
$db['log']['db_debug'] = TRUE;
$db['log']['cache_on'] = FALSE;
$db['log']['cachedir'] = '';
$db['log']['char_set'] = 'utf8';
$db['log']['dbcollat'] = 'utf8_general_ci';
$db['log']['swap_pre'] = '';
$db['log']['autoinit'] = TRUE;
$db['log']['stricton'] = FALSE;