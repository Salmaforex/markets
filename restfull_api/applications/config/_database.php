<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$active_group = 'default';
$query_builder = TRUE;

$db['default']['hostname'] = 'localhost';
$db['default']['username'] = 'mujur_forex';
$db['default']['password'] = 'v5aKFPRKDFxhV6A4';
$db['default']['database'] = 'mujur_forex';
$db['default']['dbdriver'] = 'mysqli';
$db['default']['dbprefix'] = ''; //NO PREFIX
$db['default']['pconnect'] = FALSE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;
   //===============//
/*DATABASE FOREX */
$db['log']['hostname'] = 'localhost';
$db['log']['username'] = 'root2';
$db['log']['password'] = '';
$db['log']['database'] = 'mujur_logs';
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
