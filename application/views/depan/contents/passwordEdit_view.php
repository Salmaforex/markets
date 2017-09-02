<?php
$name=isset($userlogin['name'])&&$userlogin['name']!=''?$userlogin['name']:'User Forex';
//$name=$userlogin['detail']['firstname']." ".$userlogin['detail']['lastname']; 
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
        <div class="panel panel-white">
          <div class="panel-heading border-light">
            <h3><strong>Welcome to secure area</strong></h3>
            <p>Update Your Cabinet Password</p>
          </div>
        </div>
        <div class="panel panel-white">
          <div class="panel-heading partition-blue"> <span class="text-bold"> Change Cabinet Password </span> </div>
          <div class="panel-body no-padding">
            <div class="row no-margin">
<?php 
	$load_view=isset($baseFolder)?$baseFolder.'inc/leftmenu_view':'leftmenu_view';
//	$this->load->view($load_view);
	
//	$rand_url=url_title("{$detail['accountid']}-{$detail['detail']['firstname']}","-");
//	$urlAffiliation=base_url("register/{$rand_url}");
?>		
			<div class="main col-md-12">
                <!--p>Dear <?=isset($name)?$name:'forex user';?>,<br/>
				Your are now logged-in the Secure Area. Here you can view all the Information from your accounts. You can also Update Your Profile before deposit and withdrawn and many more. </p-->
                <div class="vspace-30"></div>
<!--xxxx--->
			  <form action="#" role="form" class="form-horizontal" id="validate" method="POST">
			  <input type='hidden' name='rand' value='<?=dbId('id',22222,3);?>' />
			  <input type='hidden' name='expire' value='<?=date("Y-m-d H:i:s",
					strtotime("+1 hour"));?>' />
				<div class="text-center marbot-30">
					<h3>Change Cabinet Password</h3>
				  <p>Since you have existing live accounts please use this form to update Cabinet Password. Suggestion: Use combination letter and number</p>
				</div>
				<div class="content" style="display: block;">

				  <div class="form-group">
					<label class="col-sm-3 control-label"> New Cabinet Password <span class="symbol required"></span> </label>
					<div class="col-sm-7">
					  <input class="form-control" id="input_main1" name="main1" placeholder="Cabinet Password use combination word and number" type="password">
					</div>
				  </div>
				  <div class="form-group">
					<label class="col-sm-3 control-label"> New Cabinet Password (input again)<span class="symbol required"></span> </label>
					<div class="col-sm-7">
					  <input class="form-control" id="input_main2" name="main2" placeholder="Cabinet Password use combination word and number" type="password">
					</div>
				  </div>
				  <div class="form-group">
					<div class="col-sm-3 col-sm-offset-3">
					  <button type="submit" class="btn btn-green"> Save </button>
					</div>
				  </div>
				</div>
				</form>


<!--xxxx-->

			</div>
		</div>
	</div>

</div>
</div></div></div>
<script>
function turnWhite(){
	pass1a=jQuery("#input_main1");
	pass1b=jQuery("#input_main2");
//	pass2a=jQuery("#input_investor1");
//	pass2b=jQuery("#input_investor2");
	
	pass1b.css('background-color','#fff');
	pass1a.css('background-color','#fff');
//	pass2b.css('background-color','#fff');
//	pass2a.css('background-color','#fff');
}

function checkPass()
{
	pass1a=jQuery("#input_main1");
	pass1b=jQuery("#input_main2");
	
	pass1b.css('background-color','#fff');
	pass1a.css('background-color','#fff');
		
	fail=2;
	if(pass1a.val()!="" && pass1a.val().length > 4){
		fail--;
	}
	else{ 
		//console.log('check input 1 gagal='+pass1a.val().length);
		pass1a.css('background-color','#ff9494');
		pass1b.css('background-color','#ff9494');
	}
	
	if(pass1a.val()==pass1b.val() ){
		fail--;
	}
	else{ 
		//console.log("fail  pass1");
		pass1a.css('background-color','#ff9494');
		pass1b.css('background-color','#ff9494');
	}
	
	if(fail==0){
		console.log("ok");
		jQuery(".modal-title").html("Success");
			jQuery(".modal-body").html("Please Login Again with new Password"); 
		jQuery("#myModal").modal({show: true}).css("height","150%");
		setTimeout( function(){
			jQuery("#frmLiveAccount2").submit();
		}, 2500);
		
	}
	else{
		jQuery(".modal-title").html("warning");
			jQuery(".modal-body").html("Check Again for passsword"); 
		jQuery("#myModal").modal({show: true}).css("height","150%");
		setTimeout(turnWhite(), 2000);
		//console.log(pass1a.val());
		//console.log(pass1b.val());
		//console.log(pass2a.val());
		//console.log(pass2b.val());
	}
}

function comparePass(){
	fail=2;	
	pass1a=jQuery("#input_trading1");
	pass1b=jQuery("#input_trading2");
	pass2a=jQuery("#input_investor1");
	pass2b=jQuery("#input_investor2");
	pass1b.css('background-color','#fff');
	pass1a.css('background-color','#fff');
	pass2b.css('background-color','#fff');
	pass2a.css('background-color','#fff');
	if(pass1a.val()==pass1b.val() ){
		fail--;
	}
	else{ 
		if(pass1a.val()!="" && pass1b.val().length > 4){
	 		//console.log("fail  pass1");
			pass1a.css('background-color','#ff9494');
			pass1b.css('background-color','#ff9494');
		}else{}
	}
	if(pass2a.val()==pass2b.val() ){
		fail--;
	}
	else{
		if(pass2a.val()!="" && pass2b.val().length > 4){
			//console.log("fail  pass2");
		 	pass2b.css('background-color','#ff9494');
			pass2a.css('background-color','#ff9494');
		}else{}
	}
	//console.log(pass1a.val());
	//console.log(pass1b.val());
	//console.log(pass2a.val());
	//console.log(pass2b.val());
}

jQuery("#input_trading1,#input_trading2,#input_investor1,#input_investor2").keyup(
	function(){
		comparePass();
	}
);
</script>