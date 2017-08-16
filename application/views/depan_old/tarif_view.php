<?php 
//deposit_view.php
if (  function_exists('logFile')){ logFile('view/member/data','widtdrawalProcess_data.php','data'); };

defined('BASEPATH') OR exit('No direct script access allowed');
 
?>
<div class="container">
	<div class="row">
<?php 
	$load_view=isset($baseFolder)?$baseFolder.'inc/leftmenu_view':'leftmenu_view';
	$this->load->view($load_view);
	
	$rand_url=url_title("{$detail['accountid']}-{$detail['detail']['firstname']}","-");
	$urlAffiliation=base_url("register/{$rand_url}");
?>		
		<div class="main col-md-8">
			<form   name="frm"  id="frmLiveAccount" method="POST"   role="form">
		<div class="frame-form-basic">
		<h2>Ganti Harga / Rate</h2>
		  <div class="panel-body">
			<table class='table-striped table' border="0">
<?php 
$data =array('widtdrawal'=>'widtdrawal', 'deposit'=>'deposit');
echo bsSelect("Tipe", "types", $data);?>			
<?=bsInput('Rate ','rate', $rate['mean'],'' );?>
<tr><td colspan=3> 
            <button type="submit" class="btn btn-default submitLogin" >
              Submit
            </button></td></tr>
			</table>
			<div> Deposit : <?=number_format($rate['deposit']);?></div>
			<div> Widtdrawal : <?=number_format($rate['widtdrawal']);?></div>
		</div></div>
	</form> 
	<table id="tblTarif" class="display" cellspacing="0" width="100%">
			<thead>
				<tr>
					 <th>Created</th> 
					<th>Type</th>
					<th>Price (Rp)</th>
				   
				</tr>
			</thead>
			<tfooter>
				<tr>
					 <th>Created</th>  
					<th>Type</th>
					<th>Price (Rp)</th>
				   
				</tr>
			</tfooter>
	</table>	

<script>
urlAPI="<?=base_url("member/data?type=tarif");?>";
urlDetail="<?=base_url("member/data");?>";
</script>	
</div></div>