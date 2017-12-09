<?php

function log_info_table($type,$param){
	logCreate( json_encode($param),"debug",$type);
}

function logCreate($message,$type="info",$filename='base'){
	$date=date("Ymd");
	$month=date("Ym");
	$full=date("Y-m-d H:i:s");
	$target = 'logs/'.$month."/".$filename."{$date}.php";
	$str="$full\t".strtoupper($type)."\t$message";
	$str.="\n";
	if(!is_dir('logs')){
		mkdir('logs');	
	}
	
	if(!is_dir('logs/'.$month)){
		mkdir('logs/'.$month);	
	}
	
	file_put_contents($target, $str, LOCK_EX);
	
}