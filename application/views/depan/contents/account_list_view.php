<?php
//echo_r($userlogin);die();
$name=isset($userlogin['name'])&&$userlogin['name']!=''?$userlogin['name']:'User Forex';
//." ".$userlogin['lastname'];
//$userlogin['detail']['firstname']." ".$userlogin['detail']['lastname'];
//$accountid=isset($accounts[0]['accountid'])?$accounts[0]['accountid']:'-';
$account_detail = $this->account->detail($accountid,'accountid') ;
$balance=isset($account_detail['balance'])?$account_detail['balance'] :array();
$summary=isset($account_detail['summary'])?$account_detail['summary'] :array();
?>

  <div class="container">
    <div class="row">
	<?php
	$this->load->view('depan/inc/left_view');
	?>
      <div class="main col-md-9">
	<?php
	$this->load->view('depan/inc/partner_top_view');
	?>
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
            <p>Since you have existing live accounts please use this form to create your additional live account.
			</p>

			</p>
          </div>
        </div>

		<?php $this->load->view('depan/inc/account_list'); ?>
      </div>
    </div>
  </div>
<?php
if(defined('LOCAL')){
/*
            [TotalProfit] => -3.51
            [TotalTransactions] => 24
            [TotalOpennedTransactions] => 22
            [TotalFloatingTransactions] => 0
            [TotalClosedTransactions] => 22
            [TotalOpennedVolumeTransaction] => 23
            [TotalFloatingVolumeTransaction] => 0
            [TotalClosedVolumeTransaction] => 23
            [TotalVolume] => 22
            [TotalCommission] => 0
            [TotalAgentCommission] => 0
            [TotalWithdrawal] => 0
            [TotalDeposit] => 100
*/
	echo_r($account_detail);
	echo_r($userlogin);
}