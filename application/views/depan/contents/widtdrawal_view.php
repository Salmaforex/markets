<?php  
$user_detail= $userlogin;//['detail'];
$name=isset($userlogin['name'])&&$userlogin['name']!=''?$userlogin['name']:'User Forex';
defined('BASEPATH') OR exit('No direct script access allowed');
/*
if(!isset($user_detail['bank'])||$user_detail['bank']==''){
	$notAllow=1;
	$user_detail['bank']='';
}

if(!isset($user_detail['bank_norek'])||$user_detail['bank_norek']==''){
	$notAllow=1;
	$user_detail['bank_norek']='';
}

if(isset($notAllow)){
	$this->session->set_flashdata('notif', array('status' => false, 'msg' => 'Update nomor rekening!'));
	redirect(site_url("member/edit/warning"),1);
}
*/
$name=isset($userlogin['name'])&&$userlogin['name']!=''?$userlogin['name']:'user forex';
//=============
$accounts=array();
foreach($account_list as $row){
	if(isset($row['id'])){
		$acc_detail= $this->account_model->gets($row['id']);
//		if(defined('LOCAL')) echo_r($acc_detail);
		$accounts[$acc_detail['accountid']]=$name.' ( '.$acc_detail['accountid'].' )';
	}
}

if(defined('LOCAL')){
//	echo_r($accounts);
//	echo_r($u_detail);
	
}

//print_r($detail);die();
/*
if(isset($detail['document']['status'])){
	if($detail['document']['status']==1){
		unset($notAllow);
	}
	else{
		$this->session->set_flashdata('notif', array('status' => false, 'msg' => 'Dokumen pendukung sedang di review'));
	}
}
else{
	$this->session->set_flashdata('notif', array('status' => false, 'msg' => 'Upload dokumen pendukung!'));
}
*/
if(isset($notAllow)){
	redirect(site_url("member/uploads/warning")."?s=wd",1);
}

if(!isset($controller_main))
	$controller_main='member';
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
              <a href="<?=site_url('deposit-form');?>#" class="list-group-item active partition-blue"> Add Deposit <i class="fa fa-15x fa-arrow-circle-up pull-right"></i> </a>
              <li class="list-group-item "> <a href="<?=site_url('deposit-form');?>#" class="block text-center"><img style="width: 89px;" 
			  src="<?=site_url('media');?>/images/deposit.png"></a> </li>
            </ul>
          </div>
          <div class="col-md-4 col-xs-6">
            <ul class="list-group text-dark panel-shadow">
              <a href="<?=site_url('withdraw-form');?>#" class="list-group-item active partition-blue"> Make Withdraw <i class="fa fa-15x fa-arrow-circle-down pull-right"></i> </a>
              <li class="list-group-item "> <a href="<?=site_url('withdraw-form');?>#" class="block text-center"><img style="width: 89px;" 
			  src="<?=site_url('media');?>/images/withdraw.png"></a> </li>
            </ul>
          </div>
        </div>
        <div class="panel panel-white">
          <div class="panel-heading border-light">
            <h3><strong>Welcome to secure area</strong></h3>
            
          </div>
        </div>
		<!---NEW------>
<?php
	if(isset($done)&&$done==1){
		$load_view=isset($baseFolder)?$baseFolder.'inc/done_view':'done_view';
		$this->load->view($load_view);
	}
	
	if(isset($show_done)){
		$load_view=isset($baseFolder)?$baseFolder.'inc/widthdrawal_done_view':'widthdrawal_done_view';
		$this->load->view($load_view);
	}
?>
			  <div class="main col-md-12">
				<div class="panel">
				  <div class="panel-heading partition-blue">
				   <span class="text-bold">Withdrawal Option</span>
				  </div>
				  <div class="panel-body no-padding partition-white">
		<?php /*callback_submit();*/ ?>
				  <form class="form-horizontal" method="POST" >
					<div class="vspace-30"></div>
					<div class="row no-margin">
					  <div class="col-md-12 no-padding">
						<div class="content" style="display: block;">
					  <div class="form-group">
						<label class="col-sm-4 control-label"> Account SalmaMarkets <span class="symbol required"></span> </label>
						<div class="col-sm-7">
		<?php
		$name=isset($userlogin['name'])?$userlogin['name']:''; 
		$name1='<input type="hidden" name="name" value="'.$name.'" />
					<input type="hidden" name="username" value="'.$userlogin['email'].'" />';
			$attributes = array(
					'id'            => 'input_'.$name,
					'class'			=> 'form-control',
				);
		$inp=form_dropdown("account",$accounts,'',$attributes);
		echo $inp;
		?>
						</div>
					  </div>
					  <div class="form-group">
						<label class="col-sm-4 control-label">Name <span class="symbol required"></span> </label>
						<div class="col-sm-7">
						  <?=$name.$name1;?>
						</div>
					  </div>
					  <div class="form-group">
						<label class="col-sm-4 control-label">Phone <span class="symbol required"></span> </label>
						<div class="col-sm-7">
						  <input class="form-control" id="accnumber" name="phone" placeholder="Account number" type="text" value='<?=trim($userlogin['phone']);?>' />
						</div>
					  </div>
					  <!--div class="form-group">
						<label class="col-sm-4 control-label"> Deposit Methode <span class="symbol required"></span> </label>
						<div class="col-sm-5">
						  <select class="form-control" id="methode" name="methode">
							<option value="0">Indonesian Bank Transfer</option>
							<option value="0">International Bank Transfer</option>
						  </select>
						</div>
						<div class="col-sm-2">
						  <select class="form-control" id="currency" name="currency">
							<option value="0">IDR</option>
							<option value="0">USD</option>
						  </select>
					   </div>
					  </div-->
					  <div class="form-group">
						<label class="col-sm-4 control-label"> Bank Name <span class="symbol required"></span> </label>
						<div class="col-sm-7">
						  <input class="form-control" id="accnumber" name="bank" placeholder="Account number" type="text" value='<?=trim($userlogin['bank']);?>' />
						</div>
					  </div>
					  <div class="form-group">
						<label class="col-sm-4 control-label"> Bank account number <span class="symbol required"></span> </label>
						<div class="col-sm-7">
						  <input class="form-control" id="accnumber" name="norek" placeholder="Account number" type="text" value='<?=trim($userlogin['bank_norek']);?>'>
						</div>
					  </div>
					  <div class="form-group">
						<label class="col-sm-4 control-label"> Bank account owner Name <span class="symbol required"></span> </label>
						<div class="col-sm-7">
						  <input class="form-control" id="accnumber" name="namerek" placeholder="Account number" type="text" value='<?=trim($name);?>' />
						</div>
					  </div>
		<?php /*
					echo bsInput('Jumlah Transfer (Rp)','order1', '' ,'Nominal Hanya Estimasi Saja' );
		*/ ?>

					  <div class="form-group">
						<label class="col-sm-4 control-label"> Rate <span class="symbol required"></span> </label>
						<div class="col-sm-7">
						  <!--input class="form-control" id="rate" name="rate" placeholder="Rate" type="text" readonly value=''/-->
						  <div id="input_rate">0</div>
						</div>
					  </div>
					  <div class="form-group">
						<label class="col-sm-4 control-label"> Withdrawal amount <span class="symbol required"></span> </label>
						<div class="col-sm-7">
						  <input type="text" name="orderWidtdrawal" value="10" id="input_orderWidtdrawal" class="form-control" placeholder="Minimal $10"  />
						</div>
					  </div>
					  <div class="form-group">
						<label class="col-sm-4 control-label"> Total <span class="symbol required"></span> </label>
						<div class="col-sm-7">
						  <input type="text" name="order1" value="" id="input_order1" class="form-control" placeholder="Nominal Hanya Estimasi Saja" readonly  />
						</div>
					  </div>
					  <div class="form-group">
						<label class="col-sm-4 control-label"> Master Code <span class="symbol required"></span> </label>
						<div class="col-sm-4">
						  <input class="form-control" id="code" name="mastercode" placeholder="Master Code" type="text" required /> 
						</div>
						<div class="col-sm-4">
						  <button type="submit" class="btn btn-green"> Withdrawal <i class="fa fa-arrow-circle-right"></i> </button>
						</div>
					  </div>
					</div>
					  </div>
					</div>
					<div class="vspace-30"></div>
					</form>
				  </div>
				</div>
			  </div>

<?php
/*
        <div class="panel panel-white" style='display:none'>
          <div class="panel-heading partition-blue"> <span class="text-bold"> Summary</span> </div>
          <div class="panel-body no-padding">
            <div class="row no-margin">
<?php 
	$load_view=isset($baseFolder)?$baseFolder.'inc/leftmenu_view':'leftmenu_view';
//	$this->load->view($load_view);
	
//	$rand_url=url_title("{$detail['accountid']}-{$detail['detail']['firstname']}","-");
//	$urlAffiliation=site_url("register/{$rand_url}");
	
	if(isset($done)&&$done==1){
		$load_view=isset($baseFolder)?$baseFolder.'inc/done_view':'done_view';
		$this->load->view($load_view);
	}
?>
		<div class="main col-md-12">
        <form   name="frm"  id="frmLiveAccount2" method="POST"   role="form">
			<div class="frame-form-basic">
			<h2>Widtdrawal</h2>
			  <div class="panel-body">
				<table class='table-striped table' border="0"> 
	<?php 
	//$name=$userDetail['firstname']." ".$userDetail['lastname']; 
	$name1='<input type="hidden" name="name" value="'.$name.'" />
	<input type="hidden" name="username" value="'.$userlogin['email'].'" />';
	//echo bsInput('Akun Salmaforex','akun', $userlogin['username'] ,'',true);
	echo bsSelect("Account", "account", $accounts,'',2);
	echo bsInput('Name','name', $name.$name1 ,'',true);
	echo bsInput('Phone','phone', trim($user_detail['phone']) ,'Please Input Valid Phonenumber' );
	echo bsInput('Nama Bank','bank', trim($user_detail['bank']) ,'BCA, Mandiri, BNI, BII, etc' );
	echo bsInput('No Rekening','norek', trim($user_detail['bank_norek']) ,'999 999 999 9' );
	echo bsInput('Nama Pemilik Rekening','namerek', trim($name) ,'Please Input Valid Name' );

	echo bsInput('Jumlah Widtdrawal ($)','orderWidtdrawal', 0 ,'Minimal $10' );
	echo bsInput('Rate ($)','orderDeposit', 
	'<div id="input_rate">0</div>' ,'Minimal $10',true );
	echo bsInput('Jumlah Widtdrawal(Rp)','order1', '' ,'Nominal Hanya Estimasi Saja' );

	?>             
				<tr><td colspan=3> 
				<button type="submit" class="btn btn-default submitLogin" >
				  Submit
				</button></td></tr>
				</table>
			  </div>
			  <div class='clear'></div> 
			</div>
			<div class='notice'>
			   <p><br />
					Please wait 1-3 x 24 hours , your order will be forwarded to the Finance Departement for in the process.Salmaforex finance working hours from Monday - Friday at 09:00 am - 17:00 pm .  Hopefully this information is useful .<br />
				  </p>
				  <p id="yui_3_16_0_1_1443010679159_2162">In case you have any questions, please <a rel="nofollow" target="_blank" href="https://www.salmaforex.com/contact/" id="yui_3_16_0_1_1443010679159_2161">contact us</a>, we will be happy to answer them.</p>
				  <p id="yui_3_16_0_1_1443010679159_2163">Wishing you luck and profitable trading! </p>
				  <p><strong>Thank you for choosing SalmaForex to provide you with brokerage services on the forex market! We wish you every success in your trading!</strong></p>
				  <p>Sincerely,<br />
				  Finance Departement</p>
			</div>
		</form>		
		</div>
		
	</div>
</div>

</div>
<?php
*/
?>
</div></div></div>
<script>
urlWidtdrawal = "<?=site_url("rupiah_widtdrawal")."?t=".date("ymdhis");?>";
</script>