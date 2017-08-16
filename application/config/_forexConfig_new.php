<?php
$config['app_code']=array(
	'9912310',
);
// xxx

$config['emailAdmin']=array('admin@dev.salmaforex.com','admin@secure.salmaforex.com');
$config['email_from']=array('name'=>'no reply','email'=>'noreply@salmaforex.com');
if(defined('LOCAL')){
	$config['urlForex']=array( 
		'default'=>			'http://advance.forex/forex/fake',
		'activation'=>		'http://advance.forex/forex/fake/activation',
		'update'=>			'http://advance.forex/forex/fake/update',
		'register'=>		'http://advance.forex/forex/fake',
		'updateBalance'=>	'http://advance.forex/forex/fake/updateBalance'		
		
	);
	
	$config['api_url']='http://advance.forex/forex/api';
	$config['forexKey']='unknown99';
}
else{ 
	$config['urlForex']=array( 
		'default'=>			'https://www.natureforex.com/rest-api/salma' ,
		'register'=>		'https://www.natureforex.com/rest-api/salma/register',
		'update'=>			'https://www.natureforex.com/rest-api/salma/update-account' ,
		'updateBalance'=>	'https://www.natureforex.com/rest-api/salma/update-balance'
	);
	
	$config['api_url']='http://secure.salmaforex.com/forex/api';
	$config['forexKey']='SalmaFX123!@#';
	
}

//if dev
if(defined('_DEV_')){
	$config['api_url']='http://dev.salmaforex.com/forex/api';
	$config['urlForex']['updateBalance']=
	  'http://dev.salmaforex.com/forex/fake/updateBalance';
}else{} 

$config['urlForex']['local']='http://advance.forex/index.php/forex/data';

$config['forexBank']=array(
	array('name'=>'BCA', 'number'=>'-','person'=>'-'),
	array('name'=>'BRI', 'number'=>'-','person'=>'-'),
	array('name'=>'MANDIRI', 'number'=>'-','person'=>'-'),
	array('name'=>'BNI', 'number'=>'-','person'=>'-'),

);
/*
BCA : 2812226160 a.n PT. Salma Widyatama Mandiri 
BRI : 2202.01.000120.561 a.n Yadi Supriyadi
MANDIRI : 1300023231999 a.n PT. Salma Widyatama Mandiri
BNI : 3012123020 a.n PT. Salma Widyatama Mandiri
*/
$config['sendpulse_pubkey']='
-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDInV8khen8MIFHeqyoXH5ivWqA
sVqLU8tPtHcUawyPaL6QlD4MJjlxytRZIMMrpRQ/uPuR/c3o61STsBEuJ/zeqPvI
mH1yKT5XOjfcRtedWe0MEzJOmSOPaqX394yEV5p9vwE0IvOpaT6g27TPo0j9BHXP
eQcXNZVwGrNVyTrJSwIDAQAB
-----END PUBLIC KEY-----';
$config['smtp']=array(
'host'=>'smtp-pulse.com',
'port'=>465,
'username'=>'gundambison@gmail.com',
'password'=>'njstmAF65K'

);