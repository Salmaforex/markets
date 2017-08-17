<?php
//echo_r($userlogin);die();
$name=isset($userlogin['name'])&&$userlogin['name']!=''?$userlogin['name']:'User Forex';
//." ".$userlogin['lastname'];
//$userlogin['detail']['firstname']." ".$userlogin['detail']['lastname'];
$accountid=isset($accounts[0]['accountid'])?$accounts[0]['accountid']:'-';
$profile_pic=$userlogin['document']['profile_pic']!=''?site_url('member/show_profile/'.$userlogin['document']['id'].'/100'):false;
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
              <li class="list-group-item "> <a href="<?=site_url('deposit-form');?>#" class="block text-center"><img style="width: 89px;" 
			  src="<?=base_url('media');?>/images/deposit.png"></a> </li>
            </ul>
          </div>
          <div class="col-md-4 col-xs-6">
            <ul class="list-group text-dark panel-shadow">
              <a href="<?=base_url('withdraw-form');?>#" class="list-group-item active partition-blue"> Make Withdraw <i class="fa fa-15x fa-arrow-circle-down pull-right"></i> </a>
              <li class="list-group-item "> <a href="<?=base_url('withdraw-form');?>#" class="block text-center"><img style="width: 89px;" 
			  src="<?=base_url('media');?>/images/withdraw.png"></a> </li>
            </ul>
          </div>
        </div>
        <!--div class="panel panel-white">
          <div class="panel-heading border-light">
            <h3><strong>Welcome to secure area</strong></h3>
            <p>Since you have existing live accounts please use this form to create your additional live account.
			</p>
			<p>Notice<ol>
			//<li>Deposit and Widthdrawal Button in progress
			<li>Open New Account only for member
			<li>Open New Account for account , please create one account and request to us for agent
			</ol>
			</p>
          </div>
        </div-->
		<?php 
/*		
		?>
		
        <div class="panel panel-white">
          <div class="panel-heading partition-blue"> <span class="text-bold"> User Detail </span> </div>
          <div class="panel-body no-padding">
            <div class="row no-margin">

             <div class="col-md-6 no-padding">
                <table class="table no-margin" id="">
                  <tbody>
                    <tr class="active">
                      <td>Account (utama)</td>
                      <td class="text-right">
					  <?=$accountid;?>
					  </td>
                    </tr>
                    <tr>
                      <td>Name:</td>
                      <td class="text-right">
						<?=$name;?>
					</td>
                    </tr>
                    <tr class="active">
                      <td>Master Password</td>
                      <td class="text-right"><?=isset($userlogin['users']['u_mastercode'])?$userlogin['users']['u_mastercode']:'-';?></td>
                    </tr>
                    <tr>
                      <td>Account Type</td>
                      <td class="text-right">
					  <?=isset($userlogin['accounttype'])?$userlogin['accounttype']:'MEMBER.';?>
					  </td>
                    </tr>
                    <tr class="active">
                      <td>Account Status</td>
                      <td class="text-right">Active</td>
                    </tr>
                    <tr >
                      <td>Phone Number</td>
                      <td class="text-right">
					  <?=isset($userlogin['phone'])?$userlogin['phone']:' ';?>
					  </td>
                    </tr>
                    <tr class="active">
                      <td colspan=2>Address</td>
                    </tr>
                    <tr>
                      <td colspan=2><?=isset($userlogin['address'])?nl2br($userlogin['address']):' ';?></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="col-md-6 no-padding">
                <table class="table no-margin" id="">
                  <tbody>
                    <tr>
                      <td>Deposit:</td>
                      <td class="text-right">View on account</td>
                    </tr>
                    <tr class="active">
                      <td>Free Margin:</td>
                      <td class="text-right">View on account</td>
                    </tr>
                    <tr>
                      <td>Total Profit:</td>
                      <td class="text-right">View on account</td>
                    </tr>
                    <tr class="active">
                      <td>Total Open Transaction:</td>
                      <td class="text-right">View on account</td>
                    </tr>
                    <tr>
                      <td>Total Close Transaction:</td>
                      <td class="text-right">View on account</td>
                    </tr>
                    <tr class="active">
                      <td>Total Open Volume Transaction:</td>
                      <td class="text-right">View on account</td>
                    </tr>
                    <tr>
                      <td>Total Close Volume Transaction:</td>
                      <td class="text-right">View on account</td>
                    </tr>
                    <tr class="active">
                      <td>Balance</td>
                      <td class="text-right">View on account</td>
                    </tr>
                  </tbody>
                </table>

              </div>
            </div>
          </div>
        </div>
*/
?>
<!--detail-->
      <div class="main col-md-12">
        <div class="panel">
          <div class="panel-heading partition-blue">
            <span class="text-bold">Member Data</span>
          </div>
          <div class="panel-body no-padding partition-light-grey">
            <div class="row no-margin">
             <div class="col-md-3 no-padding">
             <div class="padding-15 text-center">
             	<img class="img-circle" alt="140x140" style="width: 140px; height: 140px;" src="<?=$profile_pic;?>">
							 </div>
             </div>
              <div class="col-md-9 no-padding partition-white">
                <form action="#">
                  <table class="table table-striped editable">
                    <tbody>
                      <tr>
                        <td>Username</td>
                        <td class="text-right"> <?=isset($userlogin['email'])?$userlogin['email']:' ';?></td>
                      </tr>
                      <tr>
                        <td>Name</td>
                        <td class="text-right"><?=$name;?></td>
                      </tr>
<?php
if(defined('LOCAL')){?>
                      <tr class="active">
                      <td>Master Password</td>
                      <td class="text-right"><?=isset($userlogin['users']['u_mastercode'])?$userlogin['users']['u_mastercode']:'-';?></td>
                    </tr>
<?php
}
?>
                    <tr>
                      <td>Account Type</td>
                      <td class="text-right">
					  <?=isset($userlogin['accounttype'])?$userlogin['accounttype']:'MEMBER.';?>
					  </td>
                    </tr>
                    <tr class="active">
                      <td>Account Status</td>
                      <td class="text-right">Active</td>
                    </tr>
                    <tr >
                      <td>Phone Number</td>
                      <td class="text-right">
					  <?=isset($userlogin['phone'])?$userlogin['phone']:' ';?>
					  </td>
                    </tr>
                    <tr class="active">
                      <td colspan=2>Address</td>
                    </tr>
                    <tr>
                      <td colspan=2><?=isset($userlogin['address'])?nl2br($userlogin['address']):' ';?></td>
                    </tr>
                    </tbody>
                  </table>
                </form>
              </div>
            </div>
          </div>
          <div class="panel-footer padding-15 text-right"> <a href='<?=site_url('member/edit');?>' class="btn btn-green edit">Edit Account</a> <a class="btn btn-default cancel hide">Cancel</a> <a class="btn btn-green save hide">Save</a> </div>
        </div>
      </div>
		<div class="main col-md-12">
<?php $this->load->view('depan/inc/account_list'); ?>
		</div>
      </div>
    </div>
  </div>
<?php
/*
if(defined('LOCAL')){
	echo_r($accounts);
	echo_r($userlogin);
}
*/