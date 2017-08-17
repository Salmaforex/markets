<?php
//$name=$userlogin['detail']['firstname']." ".$userlogin['detail']['lastname']; 
?>
  <div class="container">
    <div class="row">
	<?php
	$this->load->view('depan/inc/left_view');
	?>
      <div class="main col-md-9">
        <div class="row">
          <div class="col-md-4">
	<?php
	$this->load->view('depan/inc/account_balance_view');
	?>

          </div>
          <div class="col-md-4 col-xs-6">
            <ul class="list-group text-dark panel-shadow">
              <a href="<?=base_url('deposit-form');?>#" class="list-group-item active partition-blue"> Add Deposit <i class="fa fa-15x fa-arrow-circle-up pull-right"></i> </a>
              <li class="list-group-item "> <a href="#" class="block text-center"><img style="width: 89px;" 
			  src="<?=base_url('media');?>/images/deposit.png"></a> </li>
            </ul>
          </div>
          <div class="col-md-4 col-xs-6">
            <ul class="list-group text-dark panel-shadow">
              <a href="<?=base_url('withdraw-form');?>#" class="list-group-item active partition-blue"> Make Withdraw <i class="fa fa-15x fa-arrow-circle-down pull-right"></i> </a>
              <li class="list-group-item "> <a href="#" class="block text-center"><img style="width: 89px;" 
			  src="<?=base_url('media');?>/images/withdraw.png"></a> </li>
            </ul>
          </div>
        </div>
        <div class="panel panel-white">
          <div class="panel-heading border-light">
            <h3><strong>Welcome to secure area</strong></h3>
            <p>Since you have existing live accounts please use this form to create your additional live account.</p>
          </div>
        </div>
        <div class="panel panel-white">
          <div class="panel-heading partition-blue"> <span class="text-bold"> Summary</span> </div>
          <div class="panel-body no-padding">
            <div class="row no-margin">
		  <!--start here-->
				<div class="frame-form-basic">
				<h3 class="orange nomargin"><strong>Welcome to the Secure Area of SalmaForex</strong></h3>
                <p>Dear <?=isset($userlogin['detail']['firstname'])?$userlogin['detail']['firstname']:'';?>&nbsp;<?=isset($userlogin['detail']['lastname'])?$userlogin['detail']['lastname']:'';?>,<br/>
				Your are now logged-in the Secure Area. Here you can view all the Information from your accounts. You can also Update Your Profile before deposit and withdrawn and many more. </p>
                <div class="vspace-30"></div>
	<?php $detail1=$detail['detail'];
//	print_r($detail);
	$docUser=$this->account->document($detail['id']);
	?>
			<a href='<?=base_url('member/edit');?>' class='btn btn-default'>Edit Detail</a>
			<a href='<?=base_url('member/editpassword');?>' class='btn btn-default'>Edit Password</a>
			
			<table class='table-striped table' border="0">
			<?php 
			
			if($docUser['status']!=1){ 
				$status = 'Waiting';  
				echo  bsInput( 'Status','firstname',$status, lang('forex_inputsuggestion'), 1);
			} 
			?>
			<?=bsInput( lang('forex_firstname'),'firstname',
			isset($detail1['firstname'])?$detail1['firstname']:'', lang('forex_inputsuggestion'), 1);?>
			<?=bsInput( lang('forex_lastname'),'lastname',$detail1['lastname'], lang('forex_inputsuggestion'),1 );?> 
				<?=bsInput( lang('forex_address'),'address',$detail1['address'], lang('forex_inputsuggestion2'),1 );?>
				<?=bsInput( lang('forex_phone'),'phone',$detail1['phone'], lang('forex_inputsuggestion2'),1 );?>
				
				<?=bsInput( lang('forex_bank'),'bank',isset($detail1['bank'])?$detail1['bank']:'', lang('forex_inputsuggestion2'),1  );?>
				<?=bsInput( lang('forex_bank_norek'),'bank_norek',isset($detail1['bank_norek'])?$detail1['bank_norek']:'', lang('forex_inputsuggestion2'),1  );?>
			
				<?=bsInput( lang('forex_state'),'state',$detail1['state'], lang('forex_inputsuggestion2'),1 );?>
				 
				<?=bsInput( lang('forex_city'),'city',$detail1['city'], lang('forex_inputsuggestion2'),1 );?>
				<?=bsInput( lang('forex_zipcode'),'zipcode', $detail1['zipcode'], lang('forex_inputsuggestion'),1 );?>
				<?=bsInput( lang('forex_country'),'citizen', isset($detail1['citizen'])?$detail1['citizen']:'Indonesia', lang('forex_inputsuggestion'),1 );?>
				
			</table>
	<?php 
		if($detail['accounttype']!='MEMBER'){?>
			Link Affiliation:<br/>
			<?=anchor_popup($urlAffiliation,$urlAffiliation);
		}else{}	
			?>
				</div>
			</div>
          </div>
        </div>
</div></div></div>