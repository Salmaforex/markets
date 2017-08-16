<?php
//history_view.php
$name=isset($userlogin['name'])&&$userlogin['name']!=''?$userlogin['name']:'User Forex';
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
          <div class="panel-heading partition-blue"> <span class="text-bold"> Summary</span> </div>
          <div class="panel-body no-padding">
            <div class="row no-margin ">
<?php 
	$load_view=isset($baseFolder)?$baseFolder.'inc/leftmenu_view':'leftmenu_view';
	//$this->load->view($load_view);
	
//	$rand_url=url_title("{$detail['accountid']}-{$detail['detail']['firstname']}","-");
//	$urlAffiliation=base_url("register/{$rand_url}");
//$user_id=2325;
//$user_id=$userlogin['id'];
 
if(count($history)==0){?>

			<div class="main col-md-11"> 
<h3>Tidak ada Catatan Kegiatan</h3>

			</div>
<?php
}
else{?>		
			<div class="main col-md-12">
<hr/>	
	<div id='preview'></div>
				<table id="history-table" class="display" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>Date</th>
								<th>ID</th>

								<th>Type</th>
								<th>Transaction</th>
								<th>Rate</th>
								<th>Total</th>				
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
<?php
foreach($history as $row){
	$show=array();
	$show[]=str_replace(' ','&nbsp;',$row['created']);
//	$show[]=$row['modified'];
	$show[]=$row['param']['account'];
//	$show[]=$row['param']['username'];
	//$show[]=$row['param']['name'];
	if($row['types']=='widtdrawal') $row['types']='withdrawal';
	$show[]=$row['types']=='withdrawal'?'<div>Withdrawal&nbsp;<span class="fa fa-money"></span></div>': '<div>Deposit&nbsp;<span class="fa fa-exchange"></span></div>';
	//$show[]=$row['types']=='deposit'?$row['orderDeposit']
	$trans=0;
	if($row['types']=='deposit'){
		$trans=isset($row['param']['orderDeposit'])?$row['param']['orderDeposit']:0;
	}
	if($row['types']=='widtdrawal'||$row['types']=='withdrawal'){
		$trans=isset($row['param']['orderWidtdrawal'])?$row['param']['orderWidtdrawal']:0;
	}
	$show[]="USD&nbsp;".number_format($trans,0);

	$show[]="Rp&nbsp;".number_format($row['param']['rate']);
	$show[]="Rp&nbsp;".number_format($row['param']['rate'] * $trans);

	$status='Other '.$row['status'];//
	if($row['status']==0) $status='NEW';
	if($row['status']==1) $status='APPROVE';
	if($row['status']==2) $status='CANCEL';
	if($row['status']< 0) $status='CANCEL';
	$show[]=$status;
	
	echo '<tr><td>'.implode("</td><td>", $show)."</td></tr>\n";
}
?>
						</tbody>
						<tfooter>
							<tr>
								<th>Date</th>
								<th>ID</th> 
								<th>Type</th>
								<th>Transaction</th>
								<th>Rate</th>
								<th>Total</th>				
								<th>Status</th>
							</tr>
						</tfooter>
				</table>
	</div>
<script>
/*
urlAPI="<?=base_url("member/data?type=history");?>";
urlData="<?=base_url("member/data");?>";
*/
</script>
<?php
}
?>
</div></div>


</div>
</div></div></div>
<?php
//echo '<pre>';print_r($data );echo '</pre>';