<?php ?>
<div class="glossymenu">
	<a class="menuitem" href="<?=base_url('member');?>"><span class="glyphicon glyphicon-home"></span> Home</a>
	<?php
		$showAgentMenu0=isset($userlogin['type'])&&$userlogin['type']=='agent'?true:false;
		$showAgentMenu1=isset($userlogin['patner'])&&$userlogin['patner']!=0?true:false;
		$showAgentMenu=$showAgentMenu0||$showAgentMenu1?true:false;
//			if(isset($userlogin['document']) &&isset($userlogin['document']['status']) && $userlogin['document']['status']==1){
			if($showAgentMenu){
	?>
	<a class="menuitem submenuheader" href="#" ><span class="glyphicon glyphicon-retweet"></span> Agent</a>
	<!--TRANSAKSI-->
	<div class="submenu">
		<ul>
			<li><a href="<?=base_url('member/listApi/partner');?>"> <img class="ui--icon" src="<?=base_url();?>media/img/partners.png" alt="" data-at2x="size:14px" data-retina-auto="1" style="margin-right: 5px;">Partner</a></li>
			<!--li><a href="<?=base_url('member/listApi/patner_revenue');?>"> <img class="ui--icon" src="<?=base_url();?>media/img/partners.png" alt="" data-at2x="size:14px" data-retina-auto="1" style="margin-right: 5px;">Patner Revenue</a></li-->
		</ul>
	</div>
	<?php
			}
	?>
	<a class="menuitem submenuheader" href="#" ><span class="glyphicon glyphicon-retweet"></span> Transactions</a>
	<!--TRANSAKSI-->
	<div class="submenu">
		<ul>
			<!--li><a href="<?=base_url('member/revenue');?>"> <img class="ui--icon" src="<?=base_url();?>media/img/partners.png" alt="" data-at2x="size:14px" data-retina-auto="1" style="margin-right: 5px;">Revenue</a></li-->
			<li><a href="<?=base_url('deposit-form');?>"> <img class="ui--icon" src="<?=base_url();?>media/img/partners.png" alt="" data-at2x="size:14px" data-retina-auto="1" style="margin-right: 5px;">Deposit</a></li>
			<li><a href="<?=base_url('withdraw-form');?>"> <img class="ui--icon" src="<?=base_url();?>media/img/partners.png" alt="" data-at2x="size:14px" data-retina-auto="1" style="margin-right: 5px;">Withdrawal</a></li>
			<li><a href="<?=base_url('history');?>"> <img class="ui--icon" src="<?=base_url();?>media/img/partners.png" alt="" data-at2x="size:14px" data-retina-auto="1" style="margin-right: 5px;">History</a></li>
		</ul>
	</div>

	<a class="menuitem submenuheader" href="#" ><span class="glyphicon glyphicon-user"></span>  PROFILE</a>
	<div class="submenu">
		<ul>
			<li><a href="<?=base_url('member/detail');?>"> <img class="ui--icon" src="<?=base_url();?>media/img/open-account.png" alt="" data-at2x="size:14px" data-retina-auto="1" style="margin-right: 5px;" />Detail</a></li>
			<li><a href="<?=base_url('member/edit');?>"> <img class="ui--icon" src="<?=base_url();?>media/img/open-account.png" alt="" data-at2x="size:14px" data-retina-auto="1" style="margin-right: 5px;" />Edit Detail</a></li>
			<li><a href="<?=base_url('member/uploads');?>"> <img class="ui--icon" src="<?=base_url();?>media/img/open-account.png" alt="" data-at2x="size:14px" data-retina-auto="1" style="margin-right: 5px;" />Upload Document</a></li>
			<li><a href="<?=base_url('member/editpassword');?>"> <img class="ui--icon" src="<?=base_url();?>media/img/open-account.png" alt="" data-at2x="size:14px" data-retina-auto="1" style="margin-right: 5px;" />Edit Password</a></li>
		</ul>
	</div>
	
	<!--a class="menuitem" href="<?=base_url('member/logout');?>" ><span class="glyphicon glyphicon-log-out"></span> Logout</a-->
</div>