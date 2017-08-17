<?php
//echo_r($userlogin);die();
$name=isset($userlogin['name'])&&$userlogin['name']!=''?$userlogin['name']:'User Forex';
//$name=isset($userlogin['firstname'])?$userlogin['firstname']:'';
//." ".$userlogin['lastname'];
//$userlogin['detail']['firstname']." ".$userlogin['detail']['lastname'];
$account_utama=isset($accounts[0]['accountid'])?$accounts[0]['accountid']."(Utama)":'';
$accountid=!isset($userlogin['accountid'])?$account_utama:$userlogin['accountid'];
$balance=isset($userlogin['balance'])?$userlogin['balance']:array();
$summary=isset($userlogin['summary'])?$userlogin['summary']:array();
?>

  <div class="container">
    <div class="row">
	<?php
	$this->load->view('depan/inc/left_view');
	?>
	<!--2-->
      <div class="main col-md-9">

        <div class="row">
          <div class="col-md-4">
	<?php
	$this->load->view('depan/inc/account_balance_view');
	?>

          </div>
          <div class="col-md-4 col-xs-6">
            <ul class="list-group text-dark panel-shadow">
              <a href="<?=site_url('deposit-form');?>#" class="list-group-item active partition-blue"> Add Deposit <i class="fa fa-15x fa-arrow-circle-up pull-right"></i> </a>
              <li class="list-group-item "> <a href="<?=site_url('deposit-form');?>#" class="block text-center"><img style="width: 89px;" 
			  src="<?=base_url('media');?>/images/deposit.png"></a> </li>
            </ul>
          </div>
          <div class="col-md-4 col-xs-6">
            <ul class="list-group text-dark panel-shadow">
              <a href="<?=site_url('withdraw-form');?>#" class="list-group-item active partition-blue"> Make Withdraw <i class="fa fa-15x fa-arrow-circle-down pull-right"></i> </a>
              <li class="list-group-item "> <a href="<?=site_url('withdraw-form');?>#" class="block text-center"><img style="width: 89px;" 
			  src="<?=base_url('media');?>/images/withdraw.png"></a> </li>
            </ul>
          </div>
        </div>
        <div class="panel panel-white">
          <div class="panel-heading border-light">
            <h3><strong>Welcome to secure area</strong></h3>
            <p>This is your dashboard . For account detail, <a href='<?=site_url('member/detail');?>'>click here </a>to view account list</p>
			<p>
			For create deposit and withdraw, please update your <a href='<?=site_url('member/edit');?>'>detail Here</a>
			</p>
			<p>
			Choose your forex account on <a href='<?=site_url('member/detail');?>'>Detail . click here </a>. Choose the account and click deposit or withdraw button. Or click detail to see your account detail.
			</p>
          </div>
        </div>
		<div class="main col-md-12">
	<?php
	$this->load->view('depan/inc/partner_top_view');
	?>
<?php $this->load->view('depan/inc/account_list'); ?>
		</div>
	   </div>
    </div>
  </div>
<?php
if(defined('LOCAL')){
	echo_r($accounts);
	echo_r($userlogin);
}