<?php 
//Widthdrawal_view.php
if (  function_exists('logFile')){ logFile('view/member/data','widtdrawalProcess_data.php','data'); };
$name=isset($userlogin['name'])&&$userlogin['name']!=''?$userlogin['name']:'user forex';
$u_detail=isset($userlogin)?$userlogin:false ;
defined('BASEPATH') OR exit('No direct script access allowed');
//if(defined('LOCAL')) echo_r($account_list);
$accounts=array();
if(isset($account_list)){
	foreach($account_list as $row){
		if(isset($row['id'])){
			$acc_detail= $this->account_model->gets($row['id']);
	//		if(defined('LOCAL')) echo_r($acc_detail);
			$accounts[$acc_detail['accountid']]=$name.' ( '.$acc_detail['accountid'].' )';
		}
	}
}
else{}

//=========currency
$currency=array();
$currency_list = $this->forex->currency_list();
foreach($currency_list as $row){
	$currency[$row['code']]=$row['name'].' ( '.$row['symbol'].' )';

}

if(defined('LOCAL')){
//	echo_r($accounts);
//	echo_r($u_detail);
	
}
if(!isset($controller_main))
	$controller_main='member';
//=============

$detail_Widthdrawal = $this->forex->flow_data($flow_id);

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
        <div class="row" style='display:none'>
          <div class="col-md-4">
	<?php
	//$this->load->view('depan/inc/account_balance_view');
	?>

          </div>
          <div class="col-md-4 col-xs-6">
            <ul class="list-group text-dark panel-shadow">
              <a href="<?=base_url('Widthdrawal-form');?>#" class="list-group-item active partition-blue"> Add Widthdrawal <i class="fa fa-15x fa-arrow-circle-up pull-right"></i> </a>
              <li class="list-group-item "> <a href="<?=site_url('Widthdrawal-form');?>#" class="block text-center"><img style="width: 89px;" 
			  src="<?=base_url('media');?>/images/Widthdrawal.png"></a> </li>
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

          </div>
        </div>
        <div class1="panel panel-white">
          <div class1="panel-heading partition-blue"> <!--span class="text-bold"> Summary</span--> </div>
          <div class="panel-body no-padding">
            <div class="row no-margin">
		  <!--start here-->
<?php
//echo_r($detail_Widthdrawal);
?>

<!---NEW------>
			  <div class="main col-md-12">
				<div class="panel">
				  <div class="panel-heading partition-blue">
				   <span class="text-bold">Widthdrawal Option</span>
				  </div>
				  <div class="panel-body no-padding partition-white">
				  <?php
				  $this->load->view('depan/inc/pages/widthdrawal_detail_pages');
				  ?>
				  </div>
				</div>
			  </div>

            </div>
          </div>
        </div>
	</div>
</div>
</div>
<script>
urlWidthdrawal = "<?=base_url("value_Widthdrawal")."?t=".date("ymdhis");?>";
</script>