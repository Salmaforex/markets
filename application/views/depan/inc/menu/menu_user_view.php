<?php
		$showAgentMenu0=isset($userlogin['type'])&&$userlogin['type']=='agent'?true:false;
		$showAgentMenu1=isset($userlogin['patner'])&&$userlogin['patner']!=0?true:false;
		$showAgentMenu=$showAgentMenu0||$showAgentMenu1?true:false;
$listAcc=array();
if(isset($accounts)){
	$debug=array();
	foreach($accounts as $row){
		$debug[]=print_r($row,1);
		$type=strtoupper($row['type']);
		if(isset($row['accountid'])){
			$listAcc[]=array($row['accountid'], $row['agent'],$type);
		}
	}
}
else{
	$accounts=array();
}?> <div class="panel panel-mainmenu no-overflow">
   <div class="panel-heading no-padding partition-blue"> <a class="panel-link-title" href="<?=site_url( 'member');?>#"><span class="fa fa-home"></span> <span class="text">Home</span></a> </div>
   <div class="panel-body no-padding">
     <div class="mainmenu drop-nav">
       <ul>
<?php if($showAgentMenu){?>	   
  <li> <a href="#"><span class="fa fa-user"></span><span class="text">Agent</span></a> <span class="toggle fa fa-chevron-down"></span>
    <ul>
      <li><a href="<?=site_url( 'member/listApi/partner');?>#"><span class="fa fa-triangle-right"></span> Partners</a></li>
      <li><a href="#"><span class="fa fa-triangle-right"></span> On progress</a></li>
    </ul>
  </li>
<?php }?>
  <li> <a href="#"><span class="fa fa-user"></span><span class="text">Profile</span></a> <span class="toggle fa fa-chevron-down"></span>
    <ul>
      <li><a href="<?=site_url( 'member/detail');?>#"><span class="fa fa-triangle-right"></span> Detail</a></li>
      <li><a href="<?=site_url( 'member/edit');?>#"><span class="fa fa-triangle-right"></span> Edit</a></li>
      <li><a href="<?=site_url( 'member/uploads');?>#"><span class="fa fa-triangle-right"></span> Upload Document</a></li>
      <!--li><a href="<?=site_url( 'member/editpassword');?>#"><span class="fa fa-triangle-right"></span> Edit Password</a></li-->
    </ul>
  </li>
  <li> <a href="#"><span class="fa fa-user"></span><span class="text">Account (<?=count($accounts);?>)</span></a> <span class="toggle fa fa-chevron-down"></span>
    <ul>
	<li><a href="<?=site_url( 'member/register/account');?>#"><span class="fa fa-triangle-right"></span> Create New Account </a></li>
	<!--li><a href="<?=site_url( 'member/register/agent');?>#"><span class="fa fa-triangle-right"></span> Create New Agent </a></li-->
<?php
$txt='';
foreach($listAcc as $row){
	$url=site_url('member/account_list/'.$row[0]);
	$type=$row[2];
	if($row[1]!='') $url.="?agent=".$row[1];
					//echo_r($row1);
	$txt.='<li><a href="'.$url.'">'.
			$row[0].
		' ('.$type.') </a></li>';
}
$url=site_url('member/account_list/' );
//$txt.='<li><a href="'.$url.'">Account List</a></li>';
echo $txt;
?>
    </ul>
  </li>
  <li> <a href="#"><i class="fa fa-briefcase" aria-hidden="true"></i> <span class="text">Deposit</span></a> <span class="toggle fa fa-chevron-down"></span>
    <ul>
      <li><a href="<?=site_url( 'deposit-form');?>#"><span class="fa fa-triangle-right"></span> Form</a></li>
     <li><a href="<?=site_url( 'history');?>#"><span class="fa fa-triangle-right"></span> History </a></li>
    </ul>
  </li>
  <li> <a href="#"><i class="fa fa-exchange" aria-hidden="true"></i> <span class="text">Withdrawal</span></a> <span class="toggle fa fa-chevron-down"></span>
    <ul>
      <li><a href="<?=site_url( 'withdraw-form');?>#"><span class="fa fa-triangle-right"></span> Form</a></li>
      <li><a href="<?=site_url( 'history');?>#"><span class="fa fa-triangle-right"></span> History </a></li>
    </ul>
  </li>
  <li> <a href="#"><i class="fa fa-server" aria-hidden="true"></i> <span class="text">Platform</span></a> <span class="toggle fa fa-chevron-down"></span>
    <ul>
      <li><a href="https://www.salmamarkets.com/metatrader-4-for-windows/"><span class="fa fa-triangle-right"></span>MT4 (PC)</a></li>
      <li><a href="https://www.salmamarkets.com/metatrader-4-for-android/"><span class="fa fa-triangle-right"></span>MT4 (Android)</a></li>
      <li><a href="https://www.salmamarkets.com/metatrader-4-for-iphone/"><span class="fa fa-triangle-right"></span>MT4 (Iphone)</a></li>
      <li><a href="https://www.salmamarkets.com/metatrader-4-for-ipad/"><span class="fa fa-triangle-right"></span>MT4 (Ipad)</a></li>
      <!--li><a href="#"><span class="fa fa-triangle-right"></span>Other</a></li-->
    </ul>
  </li>
  <li> <a href="#"><i class="fa fa-shield" aria-hidden="true"></i> <span class="text">Security</span></a> <span class="toggle fa fa-chevron-down"></span>
    <ul>
      <li><a href="<?=site_url( 'member/editpassword');?>#"><span class="fa fa-triangle-right"></span>Edit Cabinet Password</a></li>
      <li><a href="<?=site_url( 'member/edit_master_password');?>#"><span class="fa fa-triangle-right"></span>Edit Master Code</a></li>
      <!--li><a href="<?=site_url( 'history');?>#"><span class="fa fa-triangle-right"></span>History </a></li-->
    </ul>
  </li>
  <li> <a href="https://www.salmamarkets.com/contact-us/"><i class="fa fa-life-ring" aria-hidden="true"></i> <span class="text">Support</span></a> <span class="toggle fa fa-chevron-down"></span>
    <ul>
      <li><a href="https://www.salmamarkets.com/contact-us/#"><span class="fa fa-triangle-right"></span>Salma Markets</a></li>

    </ul>
  </li>
       </ul>
     </div>
   </div>
   <div class="panel-footer partition-blue text-right"><a href="<?=site_url( 'member/logout');?>#" class="btn btn-transparent-white btn-xs">Logout <i class="fa fa-power-off" aria-hidden="true"></i> </a></div>
		<div style="background:white no-repeat;background-size:99%" class="div_support_pic"  >
		<img src='<?=site_url( 'media/images/sms_center.png');?>' width='260'/>
		</div>
   </div>
