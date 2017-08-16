<?php
$res= _localApi('account','list_simple',array($session['username']));
//echo_r($res);
$account =   isset($res['data'])?$res['data']:array();
?>
<div class="panel" style='width:1000px'>
  <div class="panel-heading partition-blue">
	<span class="text-bold">Account Management</span>
  </div>
  <div class="panel-body partition-white">
<?php
/*
	<table class="table table-hover" id="sample-table-1">
	  <thead>
		<tr>
		  <th>Account</th>
		  <th>Nickname</th>
		  <th>Balance</th>
		  <th>Equity</th>
		  <th>Current Trades</th>
		  <th class="center">Action</th>
		</tr>
	  </thead>
	  ??
	<th>Deposits</th>
	<th class="hidden-xs">Withdrawl</th>
	<th>Balance</th>
	<th>Nb.Trades</th>
	<th>Volumes</th>
	<th>Commission</th>
*/?>
	<div class="col-md-10a" style="overflow: auto;">
	            	<table class="table table-bordered table-striped table-full-width" id="list_member">
						<thead class="partition-dark">
							<tr>
							<th>Login and Username</th>							
							<th>Balance</th>
							<th>Equity</th>
							<th>Credits</th>
							
							<th>Free<br/>Margin</th>
							<th class="hidden-xs">Depo<br/>WD</th>
							<th>Lots</th>
							<th class="hidden-xs">Other</th>
							<th class="center"><?=$controller_main!='partner'?"Action":"Commis sion";?></th>
							</tr>
						</thead>
	  <tbody>
<?php
	foreach($account as $row0){
		$account_id = $row0['accountid'];
		$res0= _localApi('account','list_by_partner',array($account_id));
		$members  =   isset($res0['data'])?$res0['data']:array();
		//echo_r($res0);
		/*
		if(count($members)){
			?><tr><th colspan='6'>Members account id: <?=$account_id;?>  </th></tr><?php
		}
		*/
		foreach($members as $row){
			$res=_localApi('forex','balance',array($row['accountid']));
			$balance=isset($res['data']['margin'])?$res['data']['margin']:array($res);
			
			$res=_localApi('forex','summary',array($row['accountid']));
			$summary=isset($res['data']['summary'])?$res['data']['summary']:array($res);
			
			$row['detail']=$detail=isset($row['reg_detail'])?json_decode($row['reg_detail'],true):array();
			$row['name']=isset($detail['firstname'])?$detail['firstname']:'forex user';
			unset( $row['reg_detail'] );
			//echo_r($row);
			//$res=_localApi('forex','account',array($row['accountid']));
			//$detail_user_forex = isset($res['data']['summary'])?$res['data']['summary']:array($res);
			//logCreate("who : ".$row['accountid']."|".json_encode($res));
		?>
			<tr>
			  <td>accountid: <?=$row['accountid'];?>
			  <br/>username: <?=isset($row['username'])?$row['username']:'';?> </td>
			  
			  <td class="right_number"><?=!isset($balance['Balance'])?0:number_format($balance['Balance'],3);?><!--i class='fa fa-spinner fa-spin'></i--></td>
			  
			  <td class="right_number"><?=!isset($balance['Equity'])?0:number_format($balance['Equity'],2);?><!--i class='fa fa-spinner fa-spin'></i--></td>
			  
			  <td class="right_number"><?=!isset($balance['Credit'])?0:number_format($balance['Credit'],3);?><!--i class='fa fa-spinner fa-spin'></i--></td>
			  
			  <td class="right_number"><?=!isset($balance['FreeMargin'])?0:number_format($balance['FreeMargin'],3);?><!--i class='fa fa-spinner fa-spin'></i--></td>
			  
			  <td class="right_number">Deposit: <?=!isset($summary['TotalDeposit'])?0:number_format($summary['TotalDeposit'],2);?>
			  <br/>WD: <?=!isset($summary['TotalWithdrawal'])?0:number_format($summary['TotalWithdrawal'],2);?><!--i class='fa fa-spinner fa-spin'></i--></td>
			  
			  <td class="right_number"><?=!isset($summary['TotalVolume'])?0:number_format($summary['TotalVolume'],2);?><!--i class='fa fa-spinner fa-spin'></i--></td>
			  
			  <td>
			  Profit: <?=!isset($summary['TotalProfit'])?0: number_format($summary['TotalProfit'],2);?>
			  <br/>Trans: <?=!isset($summary['TotalTransactions'])?0: number_format($summary['TotalTransactions'],2);?>
			  <br/>Open| Float| Close :
			  <?=!isset($summary['TotalOpennedTransactions'])?0: number_format($summary['TotalOpennedTransactions'],2);?>|
			  <?=!isset($summary['TotalFloatingTransactions'])?0: number_format($summary['TotalFloatingTransactions'],2);?>|
			  <?=!isset($summary['TotalClosedVolumeTransaction'])?0: number_format($summary['TotalClosedVolumeTransaction'],2);?>
			  </td>
			  <td class="center">
	<?php
//===========================
	if($controller_main!='partner'){
		?><a href="<?=site_url('member/account_id/'.$row['accountid'])."?accountid=$row[accountid]";?>" class="btn btn-xs btn-orange">Detail</a>

	<?php
	}
	else{
		echo "Commission: ";
		echo !isset($summary['TotalCommission'])?0:number_format($summary['TotalCommission'],2);
		
		echo "<br/>Total Volume (with Commission Agent): ";
		echo !isset($summary['TotalVolumeWithCommissionAgent'])?"0.00": number_format($summary['TotalVolumeWithCommissionAgent'],2);
		
		echo "<br/>Total Commission Agent: ";
		echo !isset($summary['TotalCommissionAgent'])?"0.00": number_format($summary['TotalCommissionAgent'],2);
		
		echo "<br/>Total Agent Commission: ";
		echo !isset($summary['TotalAgentCommission'])?"0.00": number_format($summary['TotalAgentCommission'],2);
		
		?><!--i class='fa fa-spinner fa-spin'></i-->
	<?php
	
	}
/*<!--sum: {"AccountID":"7899280","TotalProfit":"-1893.89","TotalTransactions":"13","TotalOpennedTransactions":"8","TotalFloatingTransactions":"0","TotalClosedTransactions":"8","TotalOpennedVolumeTransaction":"750","TotalFloatingVolumeTransaction":"0","TotalClosedVolumeTransaction":"750","TotalVolume":"750","TotalWithdrawal":"-2000","TotalDeposit":"20000","TotalCommission":"0","TotalVolumeWithCommissionAgent":"300","TotalCommissionAgent":"36","TotalAgentCommission":"0","ResponseCode":"0","ResponseText":"Success"}
bal:{"AccountID":"7899280","Balance":"16106.110000","Credit":"10000.000000","Equity":"26106.110000","FreeMargin":"26106.110000","ResponseCode":"0","ResponseText":"Get margin success"}-->			  </td>
	
	echo "<!--sum: ".json_encode($summary)."\nbal:".json_encode($balance).'-->';
*/
?>
			  </td>
			</tr>
<?php
		}
	}
?>

	  </tbody>
	</table>
	</div>
	<div class="col-md-2a">
	&nbsp;
	</div>
	<div class="col-md-12">
		<div class="well text-center nomargin hide">
			<p class="nomargin marbot-15">Click button to open a new account</p>
			<a href="<?=site_url('member/register/account');?>" class="btn btn-red">Open New Account <i class="fa fa-plus"></i></a>
		</div>
	</div>
  </div>
</div>
