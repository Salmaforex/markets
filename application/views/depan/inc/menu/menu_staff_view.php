<?php
		$showAgentMenu0=isset($userlogin['type'])&&$userlogin['type']=='agent'?true:false;
		$showAgentMenu1=isset($userlogin['patner'])&&$userlogin['patner']!=0?true:false;
		$showAgentMenu=$showAgentMenu0||$showAgentMenu1?true:false;
		
?>
<div class="panel panel-mainmenu no-overflow">
	<div class="panel-heading no-padding partition-blue"> <a class="panel-link-title" href="<?=site_url('staff');?>#"><span class="fa fa-home"></span> <span class="text">Home</span></a> </div>
   <div class="panel-body no-padding">
     <div class="mainmenu drop-nav">
       <ul>
<?php if($showAgentMenu){?>	   
  <li> <a href="#"><span class="fa fa-user"></span><span class="text">Agent</span></a> <span class="toggle fa fa-chevron-down"></span>
    <ul>
      <li><a href="<?=site_url('staff/listApi/partner');?>#"><span class="fa fa-triangle-right"></span> Partners</a></li>
      <li><a href="#"><span class="fa fa-triangle-right"></span> On progress</a></li>
    </ul>
  </li>
<?php }?>
  <li> <a href="#"><span class="fa fa-user"></span><span class="text">Profile</span></a> <span class="toggle fa fa-chevron-down"></span>
    <ul>
      <li><a href="<?=site_url('staff/detail');?>#"><span class="fa fa-triangle-right"></span> Detail</a></li>
      <li><a href="<?=site_url('staff/edit');?>#"><span class="fa fa-triangle-right"></span> Edit</a></li>
      <li><a href="<?=site_url('staff/uploads');?>#"><span class="fa fa-triangle-right"></span> Upload Document</a></li>
<?php {?>
      <li><a href="<?=site_url('staff/editpassword');?>#"><span class="fa fa-triangle-right"></span> Edit Password</a></li>
<?php } ?>
    </ul>
  </li>
<?php
/*
  <li> <a href="#"><i class="fa fa-server" aria-hidden="true"></i> <span class="text">Admin Tools</span></a> <span class="toggle fa fa-chevron-down"></span>
    <ul>
      <li><a href="<?=site_url('staff/listApi/api');?>#"><span class="fa fa-triangle-right"></span>API</a></li>
      <li><a href="<?=!isset($userlogin)?site_url("staff/logout"):site_url("staff/listApi/deposit");?>#"><span class="fa fa-triangle-right"></span>Deposit </a></li>
      <li><a href="<?=site_url('staff/tarif');?>#"><span class="fa fa-triangle-right"></span>Tarif</a></li>
      <li><a href="<?=!isset($userlogin)?site_url("staff/logout"):site_url("staff/listApi/widtdrawal");?>#"><span class="fa fa-triangle-right"></span>Widtdrawal </a></li>
    </ul>
  </li>
<?php
*/
?>
  <li> <a href="#"><i class="fa fa-shield" aria-hidden="true"></i> <span class="text">Admin User </span></a> <span class="toggle fa fa-chevron-down"></span>
    <ul>
      <li><a href="<?=site_url('staff/listApi/account');?>#"><span class="fa fa-triangle-right"></span>Account MT4 </a></li> 
      <li><a href="<?=site_url('staff/listApi/approval');?>#"><span class="fa fa-triangle-right"></span>Approval </a></li>
      <li><a href="<?=site_url('staff/listApi/user');?>#"><span class="fa fa-triangle-right"></span>Detail User</a></li>
<?php {?>
      <li><a href="<?=site_url('staff/listApi/agent');?>#"><span class="fa fa-triangle-right"></span>Agent </a></li>
      <li><a href="<?=site_url('staff/update_agent');?>#"><span class="fa fa-triangle-right"></span>member convert to agent  </a></li>
<?php } ?>
    </ul>
  </li>
  
  </ul>
     </div>
   </div>
	<div class="panel-heading no-padding partition-blue"> <a class="panel-link-title" href="<?=site_url('staff/complaint');?>#"><span class="fa fa-user"></span> <span class="text">Komplain</span></a> </div>
	<div class="panel-heading no-padding partition-blue"> <a class="panel-link-title" href="<?=site_url('staff/billing');?>#"><span class="fa fa-user"></span> <span class="text">Billing</span></a> </div>
	<div class="panel-footer partition-blue text-right"><a href="<?=site_url('staff/logout');?>#" class="btn btn-transparent-white btn-xs">Logout <i class="fa fa-power-off" aria-hidden="true"></i> </a></div>
</div>
