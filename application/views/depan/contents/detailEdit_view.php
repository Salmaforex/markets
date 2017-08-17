<?php
$name=isset($userlogin['name'])&&$userlogin['name']!=''?$userlogin['name']:'User Forex';
//$userlogin['detail']['firstname']." ".$userlogin['detail']['lastname']; 
?>
  <div class="container">
    <div class="row">
	<?php
	$this->load->view('depan/inc/left_view');
	?>
      <div class="main col-md-9">
	<?php
	$this->load->view('depan/inc/patner_top_view');
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
            <p>Since you have existing live accounts please use this form to create your additional live account.</p>
          </div>
        </div>
        <div class="panel panel-white">
          <div class="panel-heading partition-blue"> <span class="text-bold"> Edit Detail</span> </div>
          <div class="panel-body no-padding">
            <div class="row no-margin">
		  <!--start here-->
<?php 
	$load_view=isset($baseFolder)?$baseFolder.'inc/leftmenu_view':'leftmenu_view';
	//$this->load->view($load_view);
	
	//$rand_url=url_title("{$detail['accountid']}-{$detail['detail']['firstname']}","-");
	//$urlAffiliation=base_url("register/{$rand_url}");
?>		
			<div class="main col-md-12">
				<form   name="frm"  id="frmLiveAccount" method="POST"   role="form">
<?php 
//	$detail1=$detail['detail'];
	callback_submit();
	$allow=false;
	if(isset($detail1['firstname'])&&trim($detail1['firstname'])!=''){
		$allow=1;
	}
?>
	<table class='table-striped table' border="0">
<?php
//ob_start();
$detail = $userlogin;
	$html=form_hidden('email',$userlogin['email']) ;
//	unset($detail['name']);
	$disable=isset($detail['name'])&&$detail['name']!=''?true:false;
	$html.=bsInput('name','detail[name]', isset($detail['name'])?$detail['name']:'' ,'full name',$disable);
    //country
        $disable=isset($detail['country'])&&$detail['country']!=''?true:false;
	$html.=bsInput('country','detail[country]', isset($detail['country'])?$detail['country']:'' ,'country',$disable);

        $html.=bsInput('city','detail[city]', isset($detail['city'])?$detail['city']:'' ,'city');
	$html.=bsInput('state','detail[state]', isset($detail['state'])?$detail['state']:'' ,'state');
	$zipcode=isset($detail['zip'])?$detail['zip']:'';
	$zipcode=isset($detail['zipcode'])?$detail['zipcode']:$zipcode;
	
	$html.=bsInput('zipcode','detail[zipcode]', $zipcode ,'zip code');
	$html.=bsInput('address','detail[address]', isset($detail['address'])?$detail['address']:'' ,'-');
	$html.=bsInput('phone','detail[phone]', isset($detail['phone'])?$detail['phone']:'' ,'-');
	$html.=bsInput('bank','detail[bank]', isset($detail['bank'])?$detail['bank']:'' ,'-');
	$html.=bsInput('rekening','detail[bank_norek]', isset($detail['bank_norek'])?$detail['bank_norek']:'' ,'-');
	//================Tanggal lahir=============
	$dt = array(
			'name'          => '',
			'id'            => 'input_1',
			'value'         => '',
			'class'			=> 'form-control_2',
			'type'			=> 'text',
			'placeholder'	=> "tanggal bulan tahun",
			'style'	=> 'width:50px',
	);
	$inp='';
	$dt['name']='detail[dob1]';
	$dt['value']=isset($detail['dob1'])?$detail['dob1']:'';
	$inp.= form_input($dt);
	$dt['name']='detail[dob2]';
	$dt['value']=isset($detail['dob2'])?$detail['dob2']:'';
	$inp.= form_input($dt);
	$dt['name']='detail[dob3]';
	$dt['value']=isset($detail['dob3'])?$detail['dob3']:'';
	$inp.= form_input($dt);
	$html.='<tr><td>Birth</td><td>:</td><td>'.$inp.'</td></tr>';
//$warn = ob_get_contents();
//ob_end_clean();
echo $html;
?>
			<?=bsButton('Update');?>
			</table>
			<input type='hidden' name='rand' value='<?=dbId('id',22222,3);?>' />
			</form> 
				
			</div>
		</div>
	</div>
<?php
if(defined('LOCAL')){
	echo_r($userlogin);
}
?>
</div>
</div></div></div>