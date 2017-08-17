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

<!-----xxx---->
	<div class="panel panel-white">
		<!--PROMOTiON-->
		<div class="panel-heading border-light">
			<!--ul class="pagination pagination-md pull-right no-margin hidden-xs">
			  <li> <a href="#"> Prev </a> </li>
			  <li class="active"><a>1</a></li>
			  <li> <a href="#"> 2 </a> </li>
			  <li> <a href="#"> 3 </a> </li>
			  <li> <a href="#"> Next </a> </li>
			</ul-->
		   <div class="page-heading">
			  <h3>Select ads Media</h3>
			  Choose your Banner and click "Get Embed Code"
			</div>
		</div>
<?php
$active='active';
for($i=1;$i<=6;$i++){?>
        <div class="panel panel-white">
          <div class="col-lg-9 col-md-9 no-padding">
            <div class="panel-heading border-right border-light"> <span class="text-bold">Banner Style <?=$i;?></span> </div>
            <div class="preview padding-15 border-right border-light text-center partition-light-grey"> <img class="img-responsive inline-block"
			src="<?=base_url('media/images/banner/set'.$i.'/set'.$i.'-728x90.png');?>" /> </div>
          </div>
          <div class="panel-body no-padding">
            <div class="col-lg-3 col-md-3 no-padding ">
              <div class="panel-heading border-right border-light visible-lg visible-md"> Select Size </div>
              <div class="options padding-15">
                <button class="btn btn-xs btn-primary <?=$active;?>" 
								data-image="<?=base_url('media/images/banner/set'.$i.'/set'.$i.'-180x150.png');?>" data-image0="images/728x90.jpg"
								data-dimension='180x150'
								data-text="<span class='text-bold'>Leaderboard Banner 180x150</span>, Performs well if placed above main content, and on forum sites."
							>180x150</button>
                <button class="btn btn-xs btn-primary" 
								data-image="<?=base_url('media/images/banner/set'.$i.'/set'.$i.'-336x280.png');?>" data-image0="images/300x250.jpg"
								data-dimension='336x280'
								data-text="<span class='text-bold'>Medium Rectangle Banner 336x280</span>, Performs well if placed above main content, and on forum sites."
							>336x280</button>
                <button class="btn btn-xs btn-primary" 
								data-image="<?=base_url('media/images/banner/set'.$i.'/set'.$i.'-320x50.png');?>" data-image0="images/300x600.jpg"
								data-dimension='320x50'
								data-text="<span class='text-bold'>Half Page Banner 320x50</span>, Performs well if placed above main content, and on forum sites."
							>320x50</button>
                <button class="btn btn-xs btn-primary" 
								data-image="<?=base_url('media/images/banner/set'.$i.'/set'.$i.'-250x250.png');?>" data-image0="images/320x100.jpg"
								data-dimension='320x100'
								data-text="<span class='text-bold'>Medium Banner 250x250</span>, Performs well if placed above main content, and on forum sites."
							>250x250</button>
<?php
	$arSize=array( '728x90','160x600','120x600');
	foreach($arSize as $size){?>
                <button class="btn btn-xs btn-primary" 
								data-image="<?=base_url('media/images/banner/set'.$i.'/set'.$i.'-'.$size.'.png');?>" data-image0="images/<?=$size;?>.jpg"
								data-dimension='<?=$size;?>'
								data-text="<span class='text-bold'>Banner <?=$size;?></span>"
							><?=$size;?></button>
<?php
	}
?>
              </div>
            </div>
          </div>
          <div class="panel-footer border-right border-light clearfix padding-horizontal-15 partition-white">
            <div class="desc col-sm-9 padding-vertical-10 small"> <span class='text-bold'>Leaderboard Banner 180x150</span>, Performs well if placed above main content, and on forum sites. </div>
            <div class="col-sm-3 text-right padding-vertical-10">
              <button class="getcode btn btn-xs btn-green">Get Embed Code</button>
            </div>
          </div>
        </div>
<?php
	$active='';
}
?>
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
<?php 
//$this->load->view('depan/inc/partner_member_lists'); 
?>
             </div>


      </div>
    </div>
  </div>
<?php
if(defined('LOCAL')){
//	echo_r($accounts);
	echo_r($userlogin);
}
