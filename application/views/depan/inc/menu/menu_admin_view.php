<?php 
?>
<!-- NEW MENU START-->
<div class="glossymenu">
	<a class="menuitem" href="<?=base_url('member');?>"><span class="glyphicon glyphicon-home"></span> Home</a>
	<a class="menuitem submenuheader" href="#" ><span class="glyphicon glyphicon-user"></span> PROFILE</a>
	<div class="submenu">
		<ul>
			<li><a href="<?=base_url('member/detail');?>"> <img class="ui--icon" src="<?=base_url();?>media/img/open-account.png" alt="" data-at2x="size:14px" data-retina-auto="1" style="margin-right: 5px;" />Detail</a></li>
			<li><a href="<?=base_url('member/edit');?>"> <img class="ui--icon" src="<?=base_url();?>media/img/open-account.png" alt="" data-at2x="size:14px" data-retina-auto="1" style="margin-right: 5px;" />Edit Detail</a></li>
			<li><a href="<?=base_url('member/uploads');?>"> <img class="ui--icon" src="<?=base_url();?>media/img/open-account.png" alt="" data-at2x="size:14px" data-retina-auto="1" style="margin-right: 5px;" />Upload Document</a></li>
			<li><a href="<?=base_url('member/editpassword');?>"> <img class="ui--icon" src="<?=base_url();?>media/img/open-account.png" alt="" data-at2x="size:14px" data-retina-auto="1" style="margin-right: 5px;" />Edit Password</a></li>
		</ul>
	</div>
	<a class="menuitem submenuheader" href="#" ><span class="glyphicon glyphicon-user"></span> ADMIN TOOLS</a>
	<div class="submenu">
		<ul>
			<li><a href="<?=base_url('member/listApi/api');?>"> <img class="ui--icon" src="<?=base_url();?>media/img/open-account.png" alt="" data-at2x="size:14px" data-retina-auto="1" style="margin-right: 5px;"> API</a></li>
            <li><a href="<?=!isset($detail['id'])?base_url("member/logout"):base_url("member/listApi/deposit");?>"> <img class="ui--icon" src="<?=base_url();?>media/img/open-account.png" alt="" data-at2x="size:14px" data-retina-auto="1" style="margin-right: 5px;"><?php 
					echo isset($detail['id'])?'DAFTAR DEPOSIT':'unknown';?>
		
		</a></li>
			<li><a href="<?=!isset($detail['id'])?base_url("member/logout"):base_url("member/listApi/widtdrawal");?>"> <img class="ui--icon" src="<?=base_url();?>media/img/open-account.png" alt="" data-at2x="size:14px" data-retina-auto="1" style="margin-right: 5px;"><?php 
					echo isset($detail['id'])?'DAFTAR WIDTDRAWAL':'unknown';?>
											</a></li>
			<li><a href="<?=!isset($detail['id'])?base_url("member/logout"):base_url("member/tarif");?>"> <img class="ui--icon" src="<?=base_url();?>media/img/partners.png" alt="" data-at2x="size:14px" data-retina-auto="1" style="margin-right: 5px;"><?php 
					echo isset($detail['id'])?'Tarif':'unknown';?>
											</a>
								</li>
			<li><a href="<?=!isset($detail['id'])?base_url("member/logout"):base_url("member/listApi/user");?>"> <img class="ui--icon" src="<?=base_url();?>media/img/contact.png" alt="" data-at2x="size:14px" data-retina-auto="1" style="margin-right: 5px;">
			List User
			</a></li>
			<li><a href="<?=!isset($detail['id'])?base_url("member/logout"):base_url("member/listApi/approval");?>"> <img class="ui--icon" src="<?=base_url();?>media/img/contact.png" alt="" data-at2x="size:14px" data-retina-auto="1" style="margin-right: 5px;">
			List Approval User
			</a></li>
			<li><a href="<?=!isset($detail['id'])?base_url("member/logout"):base_url("member/listApi/agent");?>"> <img class="ui--icon" src="<?=base_url();?>media/img/contact.png" alt="" data-at2x="size:14px" data-retina-auto="1" style="margin-right: 5px;">
			List Agent
			</a></li>
		</ul>
	</div>

</div>