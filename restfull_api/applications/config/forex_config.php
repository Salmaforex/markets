<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*===================ADVANCE FOREX================*/
$default='http://apilive.salmaforex.com/api/';
//$default='http://54.64.85.13/api/';
$urls=array(
'default'=>$default,
        'register'=>$default.'register',
        'update'=>$default.'update-account',
        'updatebalance'=>$default.'update-balance',
        'updatecredit'=>$default.'update-credit',
        'get_account'=>$default.'get-account',
        'get_margin'=>$default.'get-margin',
        'sum_trading'=>$default.'account-summary-trading'
);
/*
http://apilive.salmaforex.com/api/get-margin
http://54.64.85.13/api/register
http://54.64.85.13/api/update-account
http://54.64.85.13/api/update-balance
http://54.64.85.13/api/update-credit
http://54.64.85.13/api/get-account
*/	
$config['privatekey']='Ap!Live2017';//'1';
$config['apiForex_url']=$urls;