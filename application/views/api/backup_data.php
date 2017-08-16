<?php 
ob_start();
echo "start\n";
$succes=false;
$aSql=array();
$late2days=date("Y-m-d H:i:s", strtotime("-15 days"));
$aSql[]="create table IF NOT EXISTS {$tableTarget} like `mujur_api`;";
//insert into z_api SELECT z1.* FROM `z_api` z2 right join mujur_api z1 on z1.id=z2.id where z2.id is null ;
$aSql[]="insert into  {$tableTarget} select z1.* from {$tableTarget} z2 right join mujur_api z1 on z1.id=z2.id where z2.id is null ;";
$aSql[]="delete from `mujur_api` where created < '{$late2days}';";
$aSql[]="optimize table `mujur_api`";
echo implode("\n",$aSql);
foreach($aSql as $sql){
	dbQuery($sql,true);
	echo "\nrun $sql";
}
$content=ob_get_contents();
ob_end_clean();

$result=array(
	'body'=>isset($content)?$content:'', 
	'data'=>isset($data)?$data:array(), 
);
$succes=true;
//==========IF OK
if($succes===true)
	$result['succes']=true;

echo json_encode($result);