<?php
$res= _localApi('account','lists',array($session['username']));
//echo_r($res);
$account = isset($res['data'])?$res['data']:array();
if(!isset($controller_main))
	$controller_main='member';
?>
<div class="panel">
  <div class="panel-heading partition-blue">
	<span class="text-bold">Account Management</span>
  </div>
  <div class="panel-body partition-white">

	<table class="table table-hover" id="sample-table-1">
	  <thead>
		<tr>
		  <th>Account</th>
		  <th>Nickname</th>
		  <th>Balance</th>
		  <th>Equity</th>
		  <th>Credits</th>
<?php if($controller_main=='partner0'){?>
			<th>Commision</th>
			<th>Agent Commision</th>
<?php }?>
		  <th class="center">Action</th>
		</tr>
	  </thead>
	  <tbody>
<?php
	foreach($account as $row){
		$res=_localApi('forex','balance',array($row['accountid'],current_url() ));
		$balance=isset($res['data']['margin'])?$res['data']['margin']:array($res);
		if($controller_main=='partner0'){
			$res=_localApi('forex','summary',array($row['accountid']));
			$summary=isset($res['data']['summary'])?$res['data']['summary']:array($res);
		}
		else{
			$summary=array();
		}
/*
{

    "AccountID":"7898902",
    "TotalProfit":"0",
    "TotalTransactions":"5",
    "TotalOpennedTransactions":"0",
    "TotalFloatingTransactions":"0",
    "TotalClosedTransactions":"0",
    "TotalOpennedVolumeTransaction":"0",
    "TotalFloatingVolumeTransaction":"0",
    "TotalClosedVolumeTransaction":"0",
    "TotalVolume":"0",
    "TotalCommission":"0",
    "TotalAgentCommission":"36",
    "TotalWithdrawal":"0",
    "TotalDeposit":"100",
    "ResponseCode":"0",
    "ResponseText":"Success"

}
*/
	$res=array();//_localApi('forex','account',array($row['accountid']));
	//$detail_user_forex = isset($res['data']['summary'])?$res['data']['summary']:array($res);
	//logCreate("who : ".$row['accountid']."|".json_encode($res));
	?>
		<tr>
		  <td><?=$row['accountid'];?></td>
		  <td><?=isset($row['name'])?$row['name']:'';?>
		  </td>
		  <td class="right_number0"><?=!isset($balance['Balance'])?0:number_format($balance['Balance'],2);?>
		  </td>
		  <td class="right_number0"><?=!isset($balance['Equity'])?0:number_format($balance['Equity'],2);?>
		  </td>
		  <td class="right_number0"><?=!isset($balance['Credit'])?0:number_format($balance['Credit'],2);?>
		  </td>
<?php
if($controller_main=='partner0'){?>
			<td  class="right_number"><?=!isset($summary['TotalCommission'])?0:number_format($summary['TotalCommission'],2);?><i class='fa fa-spinner fa-spin'></i></td>
			<td class="right_number"><?=!isset($summary['TotalAgentCommission'])?0: number_format($summary['TotalAgentCommission'], 2) ;?><i class='fa fa-spinner fa-spin'></i></td>

<?php
}
?>
		  <!--td><?=!isset($summary['Credit'])?0:number_format($summary['TotalTransactions'],2);?><i class='fa fa-spinner fa-spin'></i></td-->
		  <td class="center">
		  <a href="<?=site_url($controller_main.'/account_id/'.$row['accountid'])."?accountid=$row[accountid]";?>" class="btn btn-xs btn-orange">Detail</a>
<?php
/*
		  <a href="<?=site_url('deposit-form')."?accountid=$row[accountid]";?>" class="btn btn-xs btn-green">Deposit</a>
		  <a href="<?=site_url('widtdrawal-form')."?accountid=$row[accountid]";?>" class="btn btn-xs btn-blue">Widthdrawal</a>
*/
?>
		  <a href="<?=site_url($controller_main.'/account_password/'.$row['accountid'])."?accountid=$row[accountid]";?>" class="btn btn-xs btn-blue">Change Password</a>
		  <!--<?=isset($show_account_debug)?json_encode($balance):'';?>|<?=isset($show_account_debug)?json_encode($row):'';?> -->
		  </td>
		</tr>
<?php
	}

?>

	  </tbody>
	</table>
	<div class="well text-center nomargin">
	<p class="nomargin marbot-15">Click button to open a new account</p>
	<a href="<?=site_url($controller_main.'/register/account');?>" class="btn btn-red">Open New Account <i class="fa fa-plus"></i></a>
	</div>
  </div>
</div>
